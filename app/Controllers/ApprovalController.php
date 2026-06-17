<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ApprovalController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->approval = model('App\Models\ApprovalModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function index()
    {
        $meaction = $this->request->getPostGet('meaction');
        $loan_id = $this->request->getGet('loan_id');
        
        switch ($meaction) {
            case 'SUBMIT-APPROVAL':
                $result = $this->approval->submit_for_approval();
                return $this->response->setJSON($result);
                break;
                
            case 'APPROVE-LOAN':
                $result = $this->approval->approve_loan();
                return $this->response->setJSON($result);
                break;
                
            case 'DECLINE-LOAN':
                $result = $this->approval->decline_loan();
                return $this->response->setJSON($result);
                break;
                
            case 'REVIEW-LOAN':
                $result = $this->approval->review_loan();
                return $this->response->setJSON($result);
                break;
                
            case 'REVISE-LOAN':
                $result = $this->approval->request_revision();
                return $this->response->setJSON($result);
                break;
                
            default:
                return $this->loadApprovalView($loan_id);
                break;
        }
    }

    private function loadApprovalView($loan_id = null)
    {
        // Get statistics
        $pendingCount = $this->db->query("
            SELECT COUNT(*) as total 
            FROM tbl_loans 
            WHERE approval_status = 'Pending'
        ")->getRowArray()['total'];
        
        $submittedCount = $this->db->query("
            SELECT COUNT(*) as total 
            FROM tbl_loans 
            WHERE approval_status = 'Submitted'
        ")->getRowArray()['total'];
        
        $underReviewCount = $this->db->query("
            SELECT COUNT(*) as total 
            FROM tbl_loans 
            WHERE approval_status = 'Under Review'
        ")->getRowArray()['total'];
        
        $approvedCount = $this->db->query("
            SELECT COUNT(*) as total 
            FROM tbl_loans 
            WHERE approval_status = 'Approved'
        ")->getRowArray()['total'];
        
        $declinedCount = $this->db->query("
            SELECT COUNT(*) as total 
            FROM tbl_loans 
            WHERE approval_status = 'Declined'
        ")->getRowArray()['total'];
        
        // Get ALL loans
        $allLoans = $this->db->query("
            SELECT l.loan_id, l.loan_type, l.loan_amount, l.approval_status,
                   m.first_name, m.last_name, m.member_no
            FROM tbl_loans l
            LEFT JOIN tbl_members m ON l.member_id = m.member_id
            ORDER BY l.created_at DESC
        ")->getResultArray();
        
        // Get loan details if loan_id is provided
        $loan_data = null;
        $approval_logs = null;
        $member = null;
        $amortizationSched = null;
        $member_documents = null;
        
        if(!empty($loan_id)) {
            $loan_data = $this->db->query("
                SELECT * FROM tbl_loans 
                WHERE loan_id = ?
            ", [$loan_id])->getRowArray();
            
            if($loan_data) {
                // Get member details
                $member = $this->db->query("
                    SELECT first_name, last_name, member_no, contact_number, email 
                    FROM tbl_members 
                    WHERE member_id = ?
                ", [$loan_data['member_id']])->getRowArray();
                
                // Get approval logs
                $approval_logs = $this->db->query("
                    SELECT * FROM tbl_approval_logs 
                    WHERE loan_id = ? 
                    ORDER BY created_at DESC
                ", [$loan_id])->getResultArray();
                
                // Get amortization schedule
                $amortizationSched = $this->db->query("
                    SELECT * FROM tbl_loans_ammortization 
                    WHERE loan_id = ?
                    ORDER BY period ASC
                ", [$loan_id])->getResultArray();
                
                // Get member documents (for the member who owns this loan)
                $member_documents = $this->db->query("
                    SELECT * FROM tbl_member_documents 
                    WHERE member_id = ? 
                    AND status = 'active'
                    ORDER BY upload_date DESC
                ", [$loan_data['member_id']])->getResultArray();
                
                // Get outstanding balance
                $balanceQuery = $this->db->query("
                    SELECT ending_balance 
                    FROM tbl_loans_ammortization 
                    WHERE loan_id = ? 
                    AND LOWER(payment_status) = 'paid'
                    ORDER BY ammortization_id DESC LIMIT 1
                ", [$loan_id])->getRowArray();
                
                $loan_data['outstanding_balance'] = isset($balanceQuery['ending_balance']) ? 
                    (float)$balanceQuery['ending_balance'] : (float)$loan_data['loan_amount'];
            }
        }
        
        $data = [
            'pendingCount' => $pendingCount,
            'submittedCount' => $submittedCount,
            'underReviewCount' => $underReviewCount,
            'approvedCount' => $approvedCount,
            'declinedCount' => $declinedCount,
            'allLoans' => $allLoans,
            'loan_data' => $loan_data,
            'member' => $member,
            'approval_logs' => $approval_logs,
            'amortizationSched' => $amortizationSched,
            'member_documents' => $member_documents,
            'loan_id' => $loan_id
        ];
        
        return view('approval/approval-main', $data);
    }
}