<?php
namespace App\Models;
use CodeIgniter\Model;

class JournalEntryModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function journalentry_save() { 
        $journal_id   = $this->request->getPostGet('journal_id');
        $journal_no   = $this->request->getPostGet('journal_no');
        $posting_date = $this->request->getPostGet('posting_date');
        $reference_no = $this->request->getPostGet('reference_no');
        $journal_type = $this->request->getPostGet('journal_type');
        $remarks      = $this->request->getPostGet('remarks');
        $status       = $this->request->getPostGet('status');
        $approved_by  = $this->request->getPostGet('approved_by');
        $journaldtdata = $this->request->getPostGet('journaldtdata');

        // Validation
        if (empty($posting_date)) {
            return ['status' => 'error', 'message' => 'Posting date is required!'];
        }
        if (empty($journaldtdata) || count($journaldtdata) == 0) {
            return ['status' => 'error', 'message' => 'At least one journal entry line is required!'];
        }

        // Auto-generate journal number if empty
        if (empty($journal_no)) {
            $year = date('Y');
            $month = date('m');
            $count_query = $this->db->query("SELECT COUNT(*) as count FROM tbl_journal WHERE YEAR(created_at) = '$year' AND MONTH(created_at) = '$month'");
            $count = $count_query->getRow()->count + 1;
            $journal_no = 'JRN-' . $year . $month . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        }

        if (empty($journal_id)) {
            // Insert new journal
            $query = $this->db->query("
                INSERT INTO `tbl_journal` (
                    `journal_no`,
                    `posting_date`,
                    `reference_no`,
                    `journal_type`,
                    `remarks`,
                    `status`,
                    `approved_by`,
                    `created_by`
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ", [
                $journal_no,
                $posting_date,
                $reference_no,
                $journal_type,
                $remarks,
                $status,
                $approved_by,
                $this->cuser
            ]);

            $journal_id = $this->db->insertID();
            
            if (!empty($journaldtdata)) {
                for ($aa = 0; $aa < count($journaldtdata); $aa++) {
                    $medata = explode("x|x", $journaldtdata[$aa]);
                    $account_code  = $medata[0];
                    $account_name  = $medata[1];
                    $debit_amount  = $medata[2] ?: 0;
                    $credit_amount = $medata[3] ?: 0;
                    $description   = $medata[4];
                    $cost_center   = $medata[5];

                    $this->db->query("
                        INSERT INTO tbl_journal_details (
                            `journal_id`,
                            `account_code`,
                            `account_name`,
                            `debit_amount`,
                            `credit_amount`,
                            `description`,
                            `cost_center`,
                            `created_by`
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ", [
                        $journal_id,
                        $account_code,
                        $account_name,
                        $debit_amount,
                        $credit_amount,
                        $description,
                        $cost_center,
                        $this->cuser
                    ]);
                }
            }
            
            if ($query) {
                return ['status' => 'success', 'message' => 'Journal saved successfully!'];
            } else {
                return ['status' => 'error', 'message' => 'An error occurred while saving.'];
            }
        } else {
            // Update existing journal - FIRST DELETE OLD DETAILS
            $this->db->query("DELETE FROM tbl_journal_details WHERE `journal_id` = ?", [$journal_id]);
            
            // Update journal header
            $query = $this->db->query("
                UPDATE `tbl_journal` SET
                    `journal_no`   = ?,
                    `posting_date` = ?,
                    `reference_no` = ?,
                    `journal_type` = ?,
                    `remarks`      = ?,
                    `status`       = ?,
                    `approved_by`  = ?
                WHERE `journal_id` = ?
            ", [
                $journal_no,
                $posting_date,
                $reference_no,
                $journal_type,
                $remarks,
                $status,
                $approved_by,
                $journal_id
            ]);

            // Insert new details from the collected data
            if (!empty($journaldtdata)) {
                for ($aa = 0; $aa < count($journaldtdata); $aa++) {
                    $medata = explode("x|x", $journaldtdata[$aa]);
                    $account_code  = $medata[0];
                    $account_name  = $medata[1];
                    $debit_amount  = $medata[2] ?: 0;
                    $credit_amount = $medata[3] ?: 0;
                    $description   = $medata[4];
                    $cost_center   = $medata[5];

                    $this->db->query("
                        INSERT INTO tbl_journal_details (
                            `journal_id`,
                            `account_code`,
                            `account_name`,
                            `debit_amount`,
                            `credit_amount`,
                            `description`,
                            `cost_center`,
                            `created_by`
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ", [
                        $journal_id,
                        $account_code,
                        $account_name,
                        $debit_amount,
                        $credit_amount,
                        $description,
                        $cost_center,
                        $this->cuser
                    ]);
                }
            }

            if ($query) {
                return ['status' => 'success', 'message' => 'Journal updated successfully!'];
            } else {
                return ['status' => 'error', 'message' => 'An error occurred while updating.'];
            }
        }
    }
}