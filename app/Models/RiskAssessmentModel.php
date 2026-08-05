<?php
namespace App\Models;
use CodeIgniter\Model;

class RiskAssessmentModel extends Model
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
     * Calculate numerical rating (1-5) based on criteria
     */
    private function calculateNumericalRating($field, $value)
    {
        switch($field) {
            case 'leave_credits':
                if($value <= 20) return 5;
                if($value <= 30) return 4;
                if($value <= 40) return 3;
                if($value <= 50) return 2;
                return 1;
                
            case 'capital_contribution':
                if($value <= 20000) return 5;
                if($value <= 30000) return 4;
                if($value <= 40000) return 3;
                if($value <= 50000) return 2;
                return 1;
                
            case 'take_home_pay':
                if($value <= 5000) return 5;
                if($value <= 7500) return 4;
                if($value <= 10000) return 3;
                if($value <= 12500) return 2;
                return 1;
                
            case 'existing_sslai_loans':
                if($value > 50000) return 5;
                if($value > 35000) return 4;
                if($value > 25000) return 3;
                if($value > 15000) return 2;
                if($value > 0) return 1;
                return 0;
                
            case 'years_in_service':
                if($value < 3) return 5;
                if($value <= 6) return 4;
                if($value <= 8) return 3;
                if($value <= 10) return 2;
                return 1;
                
            case 'other_loans':
                if($value > 50000) return 5;
                if($value > 35000) return 4;
                if($value > 25000) return 3;
                if($value > 15000) return 2;
                if($value > 0) return 1;
                return 0;
                
            default:
                return 0;
        }
    }

    /**
     * Get descriptive rating based on total score
     */
    private function getDescriptiveRating($score)
    {
        if($score > 35) {
            return ['quantitative' => 'High', 'descriptive' => 'High Risk'];
        } elseif($score >= 26 && $score <= 35) {
            return ['quantitative' => 'Medium', 'descriptive' => 'Cautionary'];
        } elseif($score >= 16 && $score <= 25) {
            return ['quantitative' => 'Medium', 'descriptive' => 'Moderate Risk'];
        } elseif($score >= 0 && $score <= 15) {
            return ['quantitative' => 'Low', 'descriptive' => 'Low Risk'];
        }
        return ['quantitative' => 'Undefined', 'descriptive' => 'Undefined'];
    }

    /**
     * Save risk assessment with committee approval
     */
    public function save_assessment()
    {
        $loan_id = $this->request->getPostGet('loan_id');
        
        // Get quantitative values (user input)
        $leave_credits = (float)$this->request->getPostGet('leave_credits_quant');
        $capital_contribution = (float)$this->request->getPostGet('capital_contribution_quant');
        $take_home_pay = (float)$this->request->getPostGet('take_home_pay_quant');
        $existing_sslai_loans = (float)$this->request->getPostGet('existing_sslai_loans_quant');
        $years_in_service = (float)$this->request->getPostGet('years_in_service_quant');
        $other_loans = (float)$this->request->getPostGet('other_loans_quant');
        
        // Other factors
        $health_condition = $this->request->getPostGet('health_condition');
        $civil_status = $this->request->getPostGet('civil_status');
        $age = (int)$this->request->getPostGet('age');
        $dependents = (int)$this->request->getPostGet('dependents');
        $delinquency_months = (int)$this->request->getPostGet('delinquency_months');
        $remarks = $this->request->getPostGet('remarks');
        
        // Get committee approval checkboxes
        $committee_michael = $this->request->getPostGet('committee_michael') ? 1 : 0;
        $committee_rosela = $this->request->getPostGet('committee_rosela') ? 1 : 0;
        $committee_gerry = $this->request->getPostGet('committee_gerry') ? 1 : 0;
        $committee_sharra = $this->request->getPostGet('committee_sharra') ? 1 : 0;
        
        // Calculate numerical ratings (auto-calculated)
        $leave_credits_rating = $this->calculateNumericalRating('leave_credits', $leave_credits);
        $capital_contribution_rating = $this->calculateNumericalRating('capital_contribution', $capital_contribution);
        $take_home_pay_rating = $this->calculateNumericalRating('take_home_pay', $take_home_pay);
        $existing_sslai_loans_rating = $this->calculateNumericalRating('existing_sslai_loans', $existing_sslai_loans);
        $years_in_service_rating = $this->calculateNumericalRating('years_in_service', $years_in_service);
        $other_loans_rating = $this->calculateNumericalRating('other_loans', $other_loans);
        
        // Health Condition Rating
        $health_rating = 1;
        if($health_condition == 'severe') $health_rating = 3;
        elseif($health_condition == 'mild') $health_rating = 2;
        
        // Status/Dependents Rating
        $status_rating = 1;
        if($dependents > 3) $status_rating = 3;
        elseif($dependents >= 1 && $dependents <= 3) $status_rating = 2;
        
        // Age Rating
        $age_rating = 1;
        if($age >= 55) $age_rating = 5;
        elseif($age >= 50) $age_rating = 4;
        elseif($age >= 41) $age_rating = 3;
        elseif($age >= 31) $age_rating = 2;
        
        // Credit Standing Rating
        $credit_standing_rating = 0;
        if($delinquency_months > 4) $credit_standing_rating = 5;
        elseif($delinquency_months == 4) $credit_standing_rating = 4;
        elseif($delinquency_months == 3) $credit_standing_rating = 3;
        elseif($delinquency_months == 2) $credit_standing_rating = 2;
        elseif($delinquency_months == 1) $credit_standing_rating = 1;
        
        // Calculate total numerical score
        $total_score = $leave_credits_rating + $capital_contribution_rating + $take_home_pay_rating + 
                       $existing_sslai_loans_rating + $years_in_service_rating + $other_loans_rating +
                       $health_rating + $status_rating + $age_rating + $credit_standing_rating;
        
        // Get descriptive rating
        $rating = $this->getDescriptiveRating($total_score);
        
        // Validate
        if(empty($loan_id)) {
            return ['status' => 'error', 'message' => 'Loan ID is required!'];
        }
        
        // Check if all committee members approved
        if($committee_michael == 0 || $committee_rosela == 0 || $committee_gerry == 0 || $committee_sharra == 0) {
            $missing = [];
            if($committee_michael == 0) $missing[] = 'Michael Serafico';
            if($committee_rosela == 0) $missing[] = 'Rosela M. Gomez';
            if($committee_gerry == 0) $missing[] = 'Gerry Boy Garinggan';
            if($committee_sharra == 0) $missing[] = 'Sharra A. Taywan';
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
        
        if($check['workflow_stage'] != 'Risk Assessment') {
            return ['status' => 'error', 'message' => 'Loan must be in Risk Assessment stage!'];
        }
        
        // Begin transaction
        $this->db->transBegin();
        
        try {
            // Insert risk assessment with simplified column names
            $insert = $this->db->query("
                INSERT INTO tbl_risk_assessment (
                    loan_id,
                    leave_credits_value,
                    capital_contribution_value,
                    take_home_pay_value,
                    existing_sslai_loans_value,
                    years_in_service_value,
                    other_loans_value,
                    leave_credits_rating,
                    capital_contribution_rating,
                    take_home_pay_rating,
                    existing_sslai_loans_rating,
                    years_in_service_rating,
                    other_loans_rating,
                    health_condition,
                    health_condition_rating,
                    civil_status,
                    dependents,
                    status_rating,
                    age,
                    age_rating,
                    delinquency_months,
                    credit_standing_rating,
                    total_numerical_score,
                    quantitative_rating,
                    descriptive_rating,
                    committee_approval_status,
                    approved_by,
                    approved_at,
                    remarks,
                    assessed_by,
                    assessed_at,
                    status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ", [
                $loan_id,
                $leave_credits,
                $capital_contribution,
                $take_home_pay,
                $existing_sslai_loans,
                $years_in_service,
                $other_loans,
                $leave_credits_rating,
                $capital_contribution_rating,
                $take_home_pay_rating,
                $existing_sslai_loans_rating,
                $years_in_service_rating,
                $other_loans_rating,
                $health_condition,
                $health_rating,
                $civil_status,
                $dependents,
                $status_rating,
                $age,
                $age_rating,
                $delinquency_months,
                $credit_standing_rating,
                $total_score,
                $rating['quantitative'],
                $rating['descriptive'],
                'Approved',
                $this->cuser,
                date('Y-m-d H:i:s'),
                $remarks,
                $this->cuser,
                date('Y-m-d H:i:s'),
                'Completed'
            ]);
            
            if(!$insert) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Failed to save risk assessment! Database insert failed.'];
            }
            
            $risk_id = $this->db->insertID();
            
            // Update loan workflow
            $update = $this->db->query("
                UPDATE tbl_loans 
                SET workflow_stage = 'Credit Assessment',
                    risk_assessment_id = ?
                WHERE loan_id = ?
            ", [$risk_id, $loan_id]);
            
            if(!$update) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Failed to update loan workflow!'];
            }
            
            // Log action with committee details
            $log_remarks = 'Risk assessment completed. Score: ' . $total_score . ' - ' . $rating['descriptive'] . "\n";
            $log_remarks .= 'Committee Members Approved: Michael Serafico, Rosela M. Gomez, Gerry Boy Garinggan, Sharra A. Taywan';
            
            $log = $this->db->query("
                INSERT INTO tbl_approval_logs 
                (loan_id, action, status_from, status_to, remarks, created_by) 
                VALUES (?, 'RISK-ASSESS-COMPLETE', 'Risk Assessment', 'Credit Assessment', ?, ?)
            ", [$loan_id, $log_remarks, $this->cuser]);
            
            if(!$log) {
                $this->db->transRollback();
                return ['status' => 'error', 'message' => 'Failed to log approval action!'];
            }
            
            $this->db->transCommit();
            
            return [
                'status' => 'success', 
                'message' => 'Risk assessment completed and approved by all committee members! Score: ' . $total_score . ' - ' . $rating['descriptive'],
                'score' => $total_score,
                'rating' => $rating['descriptive']
            ];
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            return ['status' => 'error', 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function getAssessment($loan_id)
    {
        return $this->db->query("
            SELECT * FROM tbl_risk_assessment 
            WHERE loan_id = ? 
            ORDER BY risk_id DESC LIMIT 1
        ", [$loan_id])->getRowArray();
    }
}