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
        
        // Check if already submitted
        $check = $this->db->query("
            SELECT approval_status FROM tbl_loans 
            WHERE loan_id = ?
        ", [$loan_id])->getRowArray();
        
        if(!$check) {
            return ['status' => 'error', 'message' => 'Loan not found!'];
        }
        
        if($check['approval_status'] != 'Pending') {
            return ['status' => 'error', 'message' => 'Loan is not in pending status!'];
        }
        
        // Update loan status
        $update = $this->db->query("
            UPDATE tbl_loans 
            SET approval_status = 'Submitted',
                submitted_by = ?,
                submitted_at = NOW()
            WHERE loan_id = ?
        ", [$this->cuser, $loan_id]);
        
        if(!$update) {
            return ['status' => 'error', 'message' => 'Failed to submit for approval!'];
        }
        
        // Log approval action
        $this->db->query("
            INSERT INTO tbl_approval_logs 
            (loan_id, action, status_from, status_to, remarks, created_by) 
            VALUES (?, 'SUBMIT', 'Pending', 'Submitted', ?, ?)
        ", [$loan_id, $remarks, $this->cuser]);
        
        return ['status' => 'success', 'message' => 'Loan submitted for approval successfully!'];
    }

    public function approve_loan() { 
        $loan_id = $this->request->getPostGet('loan_id');
        $remarks = $this->request->getPostGet('remarks');
        
        if(empty($loan_id)) {
            return ['status' => 'error', 'message' => 'Loan ID is required!'];
        }
        
        // Check if loan is submitted or under review
        $check = $this->db->query("
            SELECT approval_status FROM tbl_loans 
            WHERE loan_id = ?
        ", [$loan_id])->getRowArray();
        
        if(!$check) {
            return ['status' => 'error', 'message' => 'Loan not found!'];
        }
        
        if(!in_array($check['approval_status'], ['Submitted', 'Under Review'])) {
            return ['status' => 'error', 'message' => 'Loan must be submitted or under review!'];
        }
        
        // Update loan status
        $update = $this->db->query("
            UPDATE tbl_loans 
            SET approval_status = 'Approved',
                status = 'Approved',
                approval_by = ?,
                approval_at = NOW(),
                approval_remarks = ?
            WHERE loan_id = ?
        ", [$this->cuser, $remarks, $loan_id]);
        
        if(!$update) {
            return ['status' => 'error', 'message' => 'Failed to approve loan!'];
        }
        
        // Log approval action
        $this->db->query("
            INSERT INTO tbl_approval_logs 
            (loan_id, action, status_from, status_to, remarks, created_by) 
            VALUES (?, 'APPROVE', ?, 'Approved', ?, ?)
        ", [$loan_id, $check['approval_status'], $remarks, $this->cuser]);
        
        return ['status' => 'success', 'message' => 'Loan approved successfully!'];
    }

    public function decline_loan() { 
        $loan_id = $this->request->getPostGet('loan_id');
        $remarks = $this->request->getPostGet('remarks');
        
        if(empty($loan_id)) {
            return ['status' => 'error', 'message' => 'Loan ID is required!'];
        }
        
        // Check if loan is pending, submitted, or under review
        $check = $this->db->query("
            SELECT approval_status FROM tbl_loans 
            WHERE loan_id = ?
        ", [$loan_id])->getRowArray();
        
        if(!$check) {
            return ['status' => 'error', 'message' => 'Loan not found!'];
        }
        
        if(!in_array($check['approval_status'], ['Pending', 'Submitted', 'Under Review'])) {
            return ['status' => 'error', 'message' => 'Loan cannot be declined in current status!'];
        }
        
        // Update loan status
        $update = $this->db->query("
            UPDATE tbl_loans 
            SET approval_status = 'Declined',
                status = 'Declined',
                approval_by = ?,
                approval_at = NOW(),
                approval_remarks = ?
            WHERE loan_id = ?
        ", [$this->cuser, $remarks, $loan_id]);
        
        if(!$update) {
            return ['status' => 'error', 'message' => 'Failed to decline loan!'];
        }
        
        // Log approval action
        $this->db->query("
            INSERT INTO tbl_approval_logs 
            (loan_id, action, status_from, status_to, remarks, created_by) 
            VALUES (?, 'DECLINE', ?, 'Declined', ?, ?)
        ", [$loan_id, $check['approval_status'], $remarks, $this->cuser]);
        
        return ['status' => 'success', 'message' => 'Loan declined successfully!'];
    }

    public function review_loan() { 
        $loan_id = $this->request->getPostGet('loan_id');
        $remarks = $this->request->getPostGet('remarks');
        
        if(empty($loan_id)) {
            return ['status' => 'error', 'message' => 'Loan ID is required!'];
        }
        
        // Check if loan is submitted
        $check = $this->db->query("
            SELECT approval_status FROM tbl_loans 
            WHERE loan_id = ?
        ", [$loan_id])->getRowArray();
        
        if(!$check) {
            return ['status' => 'error', 'message' => 'Loan not found!'];
        }
        
        if($check['approval_status'] != 'Submitted') {
            return ['status' => 'error', 'message' => 'Loan must be in submitted status!'];
        }
        
        // Update loan status
        $update = $this->db->query("
            UPDATE tbl_loans 
            SET approval_status = 'Under Review',
                approval_remarks = ?
            WHERE loan_id = ?
        ", [$remarks, $loan_id]);
        
        if(!$update) {
            return ['status' => 'error', 'message' => 'Failed to start review!'];
        }
        
        // Log approval action
        $this->db->query("
            INSERT INTO tbl_approval_logs 
            (loan_id, action, status_from, status_to, remarks, created_by) 
            VALUES (?, 'REVIEW', 'Submitted', 'Under Review', ?, ?)
        ", [$loan_id, $remarks, $this->cuser]);
        
        return ['status' => 'success', 'message' => 'Loan is now under review!'];
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
        
        // Check if loan is under review
        $check = $this->db->query("
            SELECT approval_status FROM tbl_loans 
            WHERE loan_id = ?
        ", [$loan_id])->getRowArray();
        
        if(!$check) {
            return ['status' => 'error', 'message' => 'Loan not found!'];
        }
        
        if($check['approval_status'] != 'Under Review') {
            return ['status' => 'error', 'message' => 'Loan must be under review!'];
        }
        
        // Update loan status
        $update = $this->db->query("
            UPDATE tbl_loans 
            SET approval_status = 'Pending',
                approval_remarks = ?
            WHERE loan_id = ?
        ", [$remarks, $loan_id]);
        
        if(!$update) {
            return ['status' => 'error', 'message' => 'Failed to request revision!'];
        }
        
        // Log approval action
        $this->db->query("
            INSERT INTO tbl_approval_logs 
            (loan_id, action, status_from, status_to, remarks, created_by) 
            VALUES (?, 'REVISE', 'Under Review', 'Pending', ?, ?)
        ", [$loan_id, $remarks, $this->cuser]);
        
        return ['status' => 'success', 'message' => 'Revision requested successfully!'];
    }
}