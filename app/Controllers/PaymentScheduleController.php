<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class PaymentScheduleController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function index()
    {
        $meaction = $this->request->getPostGet('meaction');
        $loan_id = $this->request->getGet('loan_id');
        
        switch ($meaction) {
            case 'GENERATE-SCHEDULE':
                $result = $this->generate_schedule();
                return $this->response->setJSON($result);
                break;
                
            default:
                return $this->loadPaymentScheduleView($loan_id);
                break;
        }
    }

    private function loadPaymentScheduleView($loan_id = null)
    {
        // Get statistics
        $totalLoans = $this->db->query("
            SELECT COUNT(*) as total 
            FROM tbl_loans
        ")->getRowArray()['total'];
        
        $activeLoans = $this->db->query("
            SELECT COUNT(*) as total 
            FROM tbl_loans 
            WHERE status != 'Paid' AND status != 'Completed' AND status != 'Closed'
        ")->getRowArray()['total'];
        
        $totalPayments = $this->db->query("
            SELECT COUNT(*) as total 
            FROM tbl_loans_payment
        ")->getRowArray()['total'];
        
        $totalAmortization = $this->db->query("
            SELECT COUNT(*) as total 
            FROM tbl_loans_ammortization
        ")->getRowArray()['total'];
        
        // Get list of loans with amortization
        $loanList = $this->db->query("
            SELECT DISTINCT l.loan_id, l.loan_type, l.loan_amount, l.status,
                   m.first_name, m.last_name, m.member_no,
                   (SELECT COUNT(*) FROM tbl_loans_ammortization WHERE loan_id = l.loan_id) as amort_count
            FROM tbl_loans l
            LEFT JOIN tbl_members m ON l.member_id = m.member_id
            ORDER BY l.loan_id DESC
        ")->getResultArray();
        
        // Get loan details if loan_id is provided
        $loan_data = null;
        $amortizationSched = null;
        $payments = null;
        $member = null;
        
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
                
                // Get amortization schedule
                $amortizationSched = $this->db->query("
                    SELECT * FROM tbl_loans_ammortization 
                    WHERE loan_id = ?
                    ORDER BY period ASC
                ", [$loan_id])->getResultArray();
                
                // Get payment history
                $payments = $this->db->query("
                    SELECT payment_id, total_payment, payment_date, created_by
                    FROM tbl_loans_payment
                    WHERE loan_id = ?
                    ORDER BY payment_date DESC
                ", [$loan_id])->getResultArray();
                
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
            'totalLoans' => $totalLoans,
            'activeLoans' => $activeLoans,
            'totalPayments' => $totalPayments,
            'totalAmortization' => $totalAmortization,
            'loanList' => $loanList,
            'loan_data' => $loan_data,
            'member' => $member,
            'amortizationSched' => $amortizationSched,
            'payments' => $payments,
            'loan_id' => $loan_id
        ];
        
        return view('paymentschedule/payment-schedule-main', $data);
    }

    public function generate_schedule()
    {
        $loan_id = $this->request->getPostGet('loan_id');
        
        if(empty($loan_id)) {
            return ['status' => 'error', 'message' => 'Loan ID is required!'];
        }
        
        // Get loan details
        $loan = $this->db->query("
            SELECT * FROM tbl_loans 
            WHERE loan_id = ?
        ", [$loan_id])->getRowArray();
        
        if(!$loan) {
            return ['status' => 'error', 'message' => 'Loan not found!'];
        }
        
        // Check if schedule already exists
        $existing = $this->db->query("
            SELECT COUNT(*) as total 
            FROM tbl_loans_ammortization 
            WHERE loan_id = ?
        ", [$loan_id])->getRowArray();
        
        if($existing['total'] > 0) {
            // Delete existing schedule
            $this->db->query("DELETE FROM tbl_loans_ammortization WHERE loan_id = ?", [$loan_id]);
        }
        
        // Calculate amortization schedule
        $loanAmount = (float)$loan['loan_amount'];
        $interestRate = (float)$loan['interest_rate'];
        $termMonths = (int)$loan['term_months'];
        $startDate = $loan['start_date'];
        $member_id = $loan['member_id'];
        
        $monthlyRate = ($interestRate / 100) / 12;
        $payment = ($loanAmount * $monthlyRate * pow(1 + $monthlyRate, $termMonths)) / (pow(1 + $monthlyRate, $termMonths) - 1);
        
        $balance = $loanAmount;
        $currentDate = new \DateTime($startDate);
        
        for($i = 1; $i <= $termMonths; $i++) {
            $interest = $balance * $monthlyRate;
            $principal = $payment - $interest;
            $endingBalance = $balance - $principal;
            
            $paymentDate = clone $currentDate;
            $paymentDate->modify('+' . $i . ' months');
            $dateStr = $paymentDate->format('Y-m-d');
            
            $this->db->query("
                INSERT INTO tbl_loans_ammortization 
                (loan_id, member_id, period, payment_date, beginning_balance, 
                 interest, principal, payment, payment_status, ending_balance, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'unpaid', ?, ?)
            ", [
                $loan_id,
                $member_id,
                $i,
                $dateStr,
                $balance,
                $interest,
                $principal,
                $payment,
                $endingBalance,
                $this->cuser
            ]);
            
            $balance = $endingBalance;
            if($balance < 0) $balance = 0;
        }
        
        return ['status' => 'success', 'message' => 'Amortization schedule generated successfully!'];
    }
}