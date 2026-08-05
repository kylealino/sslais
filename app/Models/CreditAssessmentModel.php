<?php
namespace App\Models;
use CodeIgniter\Model;

class CreditAssessmentModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    /**
     * Save credit assessment with committee approval
     */
    public function save_assessment()
    {
        $loan_id = $this->request->getPostGet('loan_id');
        
        // Get form data
        $member_name = $this->request->getPostGet('member_name');
        $annual_salary = (float)$this->request->getPostGet('annual_salary');
        
        // Loan balances
        $rl = (float)$this->request->getPostGet('rl_balance');
        $el = (float)$this->request->getPostGet('el_balance');
        $mpl = (float)$this->request->getPostGet('mpl_balance');
        $sal1 = (float)$this->request->getPostGet('sal1_balance');
        $sal2 = (float)$this->request->getPostGet('sal2_balance');
        $sal3 = (float)$this->request->getPostGet('sal3_balance');
        $exl = (float)$this->request->getPostGet('exl_balance');
        $b2b = (float)$this->request->getPostGet('b2b_balance');
        $compl = (float)$this->request->getPostGet('compl_balance');
        
        // Auto-calculated totals
        $total_loan_balance = $rl + $el + $mpl + $sal1 + $sal2 + $sal3 + $exl + $b2b + $compl;
        $loanable_amount = (float)$this->request->getPostGet('loanable_amount');
        $withdrawable_capcon = (float)$this->request->getPostGet('withdrawable_capcon');
        $total_loanable_amount = $loanable_amount + $withdrawable_capcon;
        
        $computed_by = $this->request->getPostGet('computed_by');
        $amortization_applied = $this->request->getPostGet('amortization_applied');
        $amortization_per_month = $this->request->getPostGet('amortization_per_month');
        $remarks = $this->request->getPostGet('remarks');
        
        // Get committee approval checkboxes - use correct field names
        $committee_sharra = $this->request->getPostGet('committee_sharra_credit') ? 1 : 0;
        $committee_roseann = $this->request->getPostGet('committee_roseann') ? 1 : 0;
        $committee_gerry = $this->request->getPostGet('committee_gerry_credit') ? 1 : 0;
        $committee_michael = $this->request->getPostGet('committee_michael_credit') ? 1 : 0;
        $committee_jovelyn = $this->request->getPostGet('committee_jovelyn') ? 1 : 0;
        
        // Validate
        if(empty($loan_id)) {
            return ['status' => 'error', 'message' => 'Loan ID is required!'];
        }
        
        // Check if all committee members approved
        $missing = [];
        if($committee_sharra == 0) $missing[] = 'Sharra A. Taywan (Chair)';
        if($committee_roseann == 0) $missing[] = 'Rose Ann H Bonto (Co-Chair)';
        if($committee_gerry == 0) $missing[] = 'Gerry Boy Garinggan (Member)';
        if($committee_michael == 0) $missing[] = 'Michael Serafico (Member)';
        if($committee_jovelyn == 0) $missing[] = 'Jovelyn E. Pareja (Member)';
        
        if(!empty($missing)) {
            return ['status' => 'error', 'message' => 'All committee members must approve. Missing: ' . implode(', ', $missing)];
        }
        
        // Check if loan is in correct stage
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
        
        // Begin transaction
        $this->db->transBegin();
        
        try {
            // Insert credit assessment
            $insert = $this->db->query("
                INSERT INTO tbl_credit_assessment (
                    loan_id,
                    member_name,
                    annual_salary,
                    rl_balance,
                    el_balance,
                    mpl_balance,
                    sal1_balance,
                    sal2_balance,
                    sal3_balance,
                    exl_balance,
                    b2b_balance,
                    compl_balance,
                    total_loan_balance,
                    loanable_amount,
                    withdrawable_capcon,
                    total_loanable_amount,
                    committee_approval_status,
                    approved_by,
                    approved_at,
                    remarks,
                    computed_by,
                    computed_at,
                    amortization_applied,
                    amortization_per_month,
                    status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ", [
                $loan_id,
                $member_name,
                $annual_salary,
                $rl,
                $el,
                $mpl,
                $sal1,
                $sal2,
                $sal3,
                $exl,
                $b2b,
                $compl,
                $total_loan_balance,
                $loanable_amount,
                $withdrawable_capcon,
                $total_loanable_amount,
                'Approved',
                $this->cuser,
                date('Y-m-d H:i:s'),
                $remarks,
                $computed_by,
                date('Y-m-d H:i:s'),
                $amortization_applied,
                $amortization_per_month,
                'Completed'
            ]);
            
            if(!$insert) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Failed to save credit assessment! Database insert failed.'];
            }
            
            $credit_id = $this->db->insertID();
            
            // Update loan workflow
            $update = $this->db->query("
                UPDATE tbl_loans 
                SET workflow_stage = 'Decision',
                    credit_assessment_id = ?
                WHERE loan_id = ?
            ", [$credit_id, $loan_id]);
            
            if(!$update) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Failed to update loan workflow!'];
            }
            
            // Log action with committee details
            $log_remarks = 'Credit assessment completed. Total Loan Balance: ₱' . number_format($total_loan_balance, 2) . "\n";
            $log_remarks .= 'Total Loanable Amount: ₱' . number_format($total_loanable_amount, 2) . "\n";
            $log_remarks .= 'Committee Members Approved: Sharra A. Taywan (Chair), Rose Ann H Bonto (Co-Chair), Gerry Boy Garinggan (Member), Michael Serafico (Member), Jovelyn E. Pareja (Member)';
            
            $log = $this->db->query("
                INSERT INTO tbl_approval_logs 
                (loan_id, action, status_from, status_to, remarks, created_by) 
                VALUES (?, 'CREDIT-ASSESS-COMPLETE', 'Credit Assessment', 'Decision', ?, ?)
            ", [$loan_id, $log_remarks, $this->cuser]);
            
            if(!$log) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Failed to log approval action!'];
            }
            
            $this->db->transCommit();
            
            return [
                'status' => 'success',
                'message' => 'Credit assessment completed and approved by all committee members! Total Loanable: ₱' . number_format($total_loanable_amount, 2),
                'total_loan_balance' => $total_loan_balance,
                'total_loanable_amount' => $total_loanable_amount
            ];
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            return ['status' => 'error', 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    public function getAssessment($loan_id)
    {
        return $this->db->query("
            SELECT * FROM tbl_credit_assessment 
            WHERE loan_id = ? 
            ORDER BY credit_id DESC LIMIT 1
        ", [$loan_id])->getRowArray();
    }
}