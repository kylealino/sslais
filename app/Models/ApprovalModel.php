<?php
namespace App\Models;
use CodeIgniter\Model;

class ApprovalModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function submit_for_approval() { 
        $loan_id = $this->request->getPostGet('loan_id');
        $remarks = $this->request->getPostGet('remarks');
        
        if(empty($loan_id)) {
            return ['status' => 'error', 'message' => 'Loan ID is required!'];
        }
        
        $check = $this->db->query("
            SELECT workflow_stage FROM tbl_loans 
            WHERE loan_id = ?
        ", [$loan_id])->getRowArray();
        
        if(!$check) {
            return ['status' => 'error', 'message' => 'Loan not found!'];
        }
        
        if($check['workflow_stage'] != 'Pending') {
            return ['status' => 'error', 'message' => 'Loan is not in pending status!'];
        }
        
        $update = $this->db->query("
            UPDATE tbl_loans 
            SET workflow_stage = 'Risk Assessment',
                approval_status = 'Submitted',
                submitted_by = ?,
                submitted_at = NOW()
            WHERE loan_id = ?
        ", [$this->cuser, $loan_id]);
        
        if(!$update) {
            return ['status' => 'error', 'message' => 'Failed to submit for approval!'];
        }
        
        $this->db->query("
            INSERT INTO tbl_approval_logs 
            (loan_id, action, status_from, status_to, remarks, created_by) 
            VALUES (?, 'SUBMIT', 'Pending', 'Risk Assessment', ?, ?)
        ", [$loan_id, $remarks, $this->cuser]);
        
        return ['status' => 'success', 'message' => 'Loan submitted for risk assessment!'];
    }

    public function approve_loan() { 
        $loan_id = $this->request->getPostGet('loan_id');
        $remarks = $this->request->getPostGet('remarks');
        
        if(empty($loan_id)) {
            return ['status' => 'error', 'message' => 'Loan ID is required!'];
        }
        
        $check = $this->db->query("
            SELECT workflow_stage FROM tbl_loans 
            WHERE loan_id = ?
        ", [$loan_id])->getRowArray();
        
        if(!$check) {
            return ['status' => 'error', 'message' => 'Loan not found!'];
        }
        
        if($check['workflow_stage'] != 'Decision') {
            return ['status' => 'error', 'message' => 'Loan must be in Decision stage!'];
        }
        
        $update = $this->db->query("
            UPDATE tbl_loans 
            SET approval_status = 'Approved',
                status = 'Approved',
                workflow_stage = 'Completed',
                approval_by = ?,
                approval_at = NOW(),
                approval_remarks = ?
            WHERE loan_id = ?
        ", [$this->cuser, $remarks, $loan_id]);
        
        if(!$update) {
            return ['status' => 'error', 'message' => 'Failed to approve loan!'];
        }
        
        $this->db->query("
            INSERT INTO tbl_approval_logs 
            (loan_id, action, status_from, status_to, remarks, created_by) 
            VALUES (?, 'APPROVE', 'Decision', 'Approved', ?, ?)
        ", [$loan_id, $remarks, $this->cuser]);
        
        return ['status' => 'success', 'message' => 'Loan approved successfully!'];
    }

    public function decline_loan() { 
        $loan_id = $this->request->getPostGet('loan_id');
        $remarks = $this->request->getPostGet('remarks');
        
        if(empty($loan_id)) {
            return ['status' => 'error', 'message' => 'Loan ID is required!'];
        }
        
        $check = $this->db->query("
            SELECT workflow_stage FROM tbl_loans 
            WHERE loan_id = ?
        ", [$loan_id])->getRowArray();
        
        if(!$check) {
            return ['status' => 'error', 'message' => 'Loan not found!'];
        }
        
        if(!in_array($check['workflow_stage'], ['Pending', 'Risk Assessment', 'Credit Assessment', 'Decision'])) {
            return ['status' => 'error', 'message' => 'Loan cannot be declined in current stage!'];
        }
        
        $update = $this->db->query("
            UPDATE tbl_loans 
            SET approval_status = 'Declined',
                status = 'Declined',
                workflow_stage = 'Declined',
                approval_by = ?,
                approval_at = NOW(),
                approval_remarks = ?
            WHERE loan_id = ?
        ", [$this->cuser, $remarks, $loan_id]);
        
        if(!$update) {
            return ['status' => 'error', 'message' => 'Failed to decline loan!'];
        }
        
        $this->db->query("
            INSERT INTO tbl_approval_logs 
            (loan_id, action, status_from, status_to, remarks, created_by) 
            VALUES (?, 'DECLINE', ?, 'Declined', ?, ?)
        ", [$loan_id, $check['workflow_stage'], $remarks, $this->cuser]);
        
        return ['status' => 'success', 'message' => 'Loan declined successfully!'];
    }

    public function request_revision() { 
        $loan_id = $this->request->getPostGet('loan_id');
        $remarks = $this->request->getPostGet('remarks');
        
        if(empty($loan_id)) {
            return ['status' => 'error', 'message' => 'Loan ID is required!'];
        }
        
        if(empty($remarks)) {
            return ['status' => 'error', 'message' => 'Remarks are required for revision request!'];
        }
        
        $check = $this->db->query("
            SELECT workflow_stage FROM tbl_loans 
            WHERE loan_id = ?
        ", [$loan_id])->getRowArray();
        
        if(!$check) {
            return ['status' => 'error', 'message' => 'Loan not found!'];
        }
        
        if($check['workflow_stage'] != 'Credit Assessment') {
            return ['status' => 'error', 'message' => 'Loan must be in Credit Assessment stage!'];
        }
        
        $update = $this->db->query("
            UPDATE tbl_loans 
            SET workflow_stage = 'Pending',
                approval_status = 'Pending',
                approval_remarks = ?
            WHERE loan_id = ?
        ", [$remarks, $loan_id]);
        
        if(!$update) {
            return ['status' => 'error', 'message' => 'Failed to request revision!'];
        }
        
        $this->db->query("
            INSERT INTO tbl_approval_logs 
            (loan_id, action, status_from, status_to, remarks, created_by) 
            VALUES (?, 'REVISE', 'Credit Assessment', 'Pending', ?, ?)
        ", [$loan_id, $remarks, $this->cuser]);
        
        return ['status' => 'success', 'message' => 'Revision requested successfully!'];
    }
}