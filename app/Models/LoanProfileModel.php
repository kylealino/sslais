<?php
namespace App\Models;
use CodeIgniter\Model;

class LoanProfileModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function loanpayment_save() { 
        $loan_id = $this->request->getPostGet('loan_id');
        $member_id = $this->request->getPostGet('member_id');
        $interest = $this->request->getPostGet('interest');
        $principal = $this->request->getPostGet('principal');
        $total_payment = $this->request->getPostGet('total_payment');
        $payment_date = $this->request->getPostGet('payment_date');
        $ammortization_id = $this->request->getPostGet('ammortization_id');

        // Validation
        if (empty($loan_id)) {
            return ['status' => 'error', 'message' => 'Loan ID is required!'];
        }
        if (empty($ammortization_id)) {
            return ['status' => 'error', 'message' => 'Please select an amortization schedule to pay!'];
        }
        if (empty($total_payment) || $total_payment <= 0) {
            return ['status' => 'error', 'message' => 'Invalid payment amount!'];
        }

        // Insert payment record
        $query1 = $this->db->query("
            INSERT INTO `tbl_loans_payment`(
                `loan_id`,
                `member_id`,
                `interest`,
                `principal`,
                `total_payment`,
                `payment_date`,
                `created_by`
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ", [
            $loan_id,
            $member_id,
            $interest,
            $principal,
            $total_payment,
            $payment_date,
            $this->cuser
        ]);

        // Update amortization status to Paid
        $query2 = $this->db->query("
            UPDATE `tbl_loans_ammortization`
            SET `payment_status` = 'Paid'
            WHERE `ammortization_id` = ?
        ", [$ammortization_id]);

        if ($query1 && $query2) {
            return ['status' => 'success', 'message' => 'Payment Saved Successfully!', 'loan_id' => $loan_id];
        } else {
            return ['status' => 'error', 'message' => 'An error occurred while saving payment.'];
        }
    }
}