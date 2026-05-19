<?php
namespace App\Models;
use CodeIgniter\Model;

class LoanAvailmentModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function loanavailment_save() { 
        $loan_id = $this->request->getPostGet('loan_id');
        $member_id = $this->request->getPostGet('member_id');
        $loan_type = $this->request->getPostGet('loan_type');
        $loan_amount = $this->request->getPostGet('loan_amount');
        $interest_rate = $this->request->getPostGet('interest_rate');
        $term_months = $this->request->getPostGet('term_months');
        $start_date = $this->request->getPostGet('start_date');
        $maturity_date = $this->request->getPostGet('maturity_date');
        $loan_comakers = $this->request->getPostGet('loan_comakers');
        $status = $this->request->getPostGet('status');
        $ammortizationdata = $this->request->getPostGet('ammortizationdata');

        // Validation
        if (empty($member_id)) {
            return ['status' => 'error', 'message' => 'Please select a member!'];
        }
        if (empty($loan_type)) {
            return ['status' => 'error', 'message' => 'Please select loan type!'];
        }
        if (empty($loan_amount) || $loan_amount <= 0) {
            return ['status' => 'error', 'message' => 'Please enter valid loan amount!'];
        }
        if (empty($interest_rate)) {
            return ['status' => 'error', 'message' => 'Please enter interest rate!'];
        }
        if (empty($term_months)) {
            return ['status' => 'error', 'message' => 'Please select term!'];
        }
        if (empty($start_date)) {
            return ['status' => 'error', 'message' => 'Please select start date!'];
        }
        if (empty($ammortizationdata)) {
            return ['status' => 'error', 'message' => 'Please generate amortization schedule first!'];
        }

        // Insert Loan
        $query = $this->db->query("
            INSERT INTO `tbl_loans`(
                `member_id`,
                `loan_type`,
                `loan_amount`,
                `interest_rate`,
                `term_months`,
                `start_date`,
                `maturity_date`,
                `status`,
                `loan_comakers`,
                `created_by`
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $member_id,
            $loan_type,
            $loan_amount,
            $interest_rate,
            $term_months,
            $start_date,
            $maturity_date,
            $status ?: 'Pending',
            $loan_comakers,
            $this->cuser
        ]);

        $loan_id = $this->db->insertID();
        
        if (!empty($ammortizationdata)) {
            for($aa = 0; $aa < count($ammortizationdata); $aa++){
                $medata = explode("x|x", $ammortizationdata[$aa]);
                $period = $medata[0]; 
                $payment_date = date('Y-m-d', strtotime($medata[1]));
                $beginning_balance = $medata[2]; 
                $interest = $medata[3];
                $principal = $medata[4];
                $payment = $medata[5];  
                $ending_balance = $medata[6];  

                $this->db->query("
                    INSERT INTO `tbl_loans_ammortization`(
                        `loan_id`,
                        `member_id`,
                        `period`,
                        `payment_date`,
                        `beginning_balance`,
                        `interest`,
                        `principal`,
                        `payment`,
                        `ending_balance`,
                        `created_by`
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", 
                    [
                        $loan_id,
                        $member_id,
                        $period,
                        $payment_date,
                        $beginning_balance,
                        $interest,
                        $principal,
                        $payment,
                        $ending_balance,
                        $this->cuser
                    ]
                );
            }
        }

        if ($query) {
            return ['status' => 'success', 'message' => 'Loan Saved Successfully!'];
        } else {
            return ['status' => 'error', 'message' => 'An error occurred while saving.'];
        }
    }
}