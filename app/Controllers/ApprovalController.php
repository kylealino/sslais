<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ApprovalController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->approval = model('App\Models\ApprovalModel');
        $this->riskModel = model('App\Models\RiskAssessmentModel');
        $this->creditModel = model('App\Models\CreditAssessmentModel');
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
                
            case 'SAVE-RISK-ASSESSMENT':
                $result = $this->riskModel->save_assessment();
                return $this->response->setJSON($result);
                
            case 'SAVE-CREDIT-ASSESSMENT':
                $result = $this->creditModel->save_assessment();
                return $this->response->setJSON($result);
                
            case 'APPROVE-LOAN':
                $result = $this->approval->approve_loan();
                return $this->response->setJSON($result);
                
            case 'DECLINE-LOAN':
                $result = $this->approval->decline_loan();
                return $this->response->setJSON($result);
                
            case 'REVISE-LOAN':
                $result = $this->approval->request_revision();
                return $this->response->setJSON($result);
                
            default:
                return $this->loadApprovalView($loan_id);
        }
    }

    private function loadApprovalView($loan_id = null)
    {
        // Statistics
        $stats = $this->db->query("
            SELECT 
                COUNT(CASE WHEN workflow_stage = 'Pending' THEN 1 END) as pending,
                COUNT(CASE WHEN workflow_stage = 'Risk Assessment' THEN 1 END) as risk_assessment,
                COUNT(CASE WHEN workflow_stage = 'Credit Assessment' THEN 1 END) as credit_assessment,
                COUNT(CASE WHEN workflow_stage = 'Decision' THEN 1 END) as decision,
                COUNT(CASE WHEN approval_status = 'Approved' THEN 1 END) as approved,
                COUNT(CASE WHEN approval_status = 'Declined' THEN 1 END) as declined
            FROM tbl_loans
        ")->getRowArray();
        
        // All loans
        $allLoans = $this->db->query("
            SELECT l.loan_id, l.loan_type, l.loan_amount, l.approval_status, l.workflow_stage,
                   m.first_name, m.last_name, m.member_no
            FROM tbl_loans l
            LEFT JOIN tbl_members m ON l.member_id = m.member_id
            ORDER BY l.created_at DESC
        ")->getResultArray();
        
        // Loan details
        $loan_data = null;
        $member = null;
        $approval_logs = null;
        $amortizationSched = null;
        $member_documents = null;
        $risk_assessment = null;
        $credit_assessment = null;
        
        if(!empty($loan_id)) {
            $loan_data = $this->db->query("
                SELECT * FROM tbl_loans 
                WHERE loan_id = ?
            ", [$loan_id])->getRowArray();
            
            if($loan_data) {
                $member = $this->db->query("
                    SELECT first_name, last_name, member_no, contact_number, email 
                    FROM tbl_members 
                    WHERE member_id = ?
                ", [$loan_data['member_id']])->getRowArray();
                
                $approval_logs = $this->db->query("
                    SELECT * FROM tbl_approval_logs 
                    WHERE loan_id = ? 
                    ORDER BY created_at DESC
                ", [$loan_id])->getResultArray();
                
                $amortizationSched = $this->db->query("
                    SELECT * FROM tbl_loans_ammortization 
                    WHERE loan_id = ?
                    ORDER BY period ASC
                ", [$loan_id])->getResultArray();
                
                $member_documents = $this->db->query("
                    SELECT * FROM tbl_member_documents 
                    WHERE member_id = ? 
                    AND status = 'active'
                    ORDER BY upload_date DESC
                ", [$loan_data['member_id']])->getResultArray();
                
                $risk_assessment = $this->riskModel->getAssessment($loan_id);
                $credit_assessment = $this->creditModel->getAssessment($loan_id);
                
                // Outstanding balance
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
            'stats' => $stats,
            'allLoans' => $allLoans,
            'loan_data' => $loan_data,
            'member' => $member,
            'approval_logs' => $approval_logs,
            'amortizationSched' => $amortizationSched,
            'member_documents' => $member_documents,
            'risk_assessment' => $risk_assessment,
            'credit_assessment' => $credit_assessment,
            'loan_id' => $loan_id,
            'cuser' => $this->cuser
        ];
        
        return view('approval/approval-main', $data);
    }
}