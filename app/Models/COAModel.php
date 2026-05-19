<?php
namespace App\Models;
use CodeIgniter\Model;

class COAModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function coa_save() { 
        $account_id   = $this->request->getPostGet('account_id');
        $account_code = $this->request->getPostGet('account_code');
        $account_name = $this->request->getPostGet('account_name');
        $account_type = $this->request->getPostGet('account_type');
        $parent_code  = $this->request->getPostGet('parent_code');
        $is_active    = $this->request->getPostGet('is_active');

        // Validation
        if (empty($account_code)) {
            return ['status' => 'error', 'message' => 'Account Code is required!'];
        }
        if (empty($account_name)) {
            return ['status' => 'error', 'message' => 'Account Name is required!'];
        }
        if (empty($account_type)) {
            return ['status' => 'error', 'message' => 'Account Type is required!'];
        }

        // Check for duplicate account code
        if (empty($account_id)) {
            $check = $this->db->query("SELECT COUNT(*) as count FROM tbl_coa WHERE account_code = ?", [$account_code])->getRow();
            if ($check->count > 0) {
                return ['status' => 'error', 'message' => 'Account Code already exists!'];
            }
        } else {
            $check = $this->db->query("SELECT COUNT(*) as count FROM tbl_coa WHERE account_code = ? AND account_id != ?", [$account_code, $account_id])->getRow();
            if ($check->count > 0) {
                return ['status' => 'error', 'message' => 'Account Code already exists!'];
            }
        }

        if (empty($account_id)) {
            // Insert new account
            $query = $this->db->query("
                INSERT INTO `tbl_coa`(
                    `account_code`,
                    `account_name`,
                    `account_type`,
                    `parent_code`,
                    `is_active`,
                    `created_by`
                ) VALUES (?, ?, ?, ?, ?, ?)
            ", [
                $account_code,
                $account_name,
                $account_type,
                $parent_code ?: null,
                $is_active,
                $this->cuser
            ]);

            if ($query) {
                return ['status' => 'success', 'message' => 'Account saved successfully!'];
            } else {
                return ['status' => 'error', 'message' => 'An error occurred while saving.'];
            }
        } else {
            // Update existing account
            $query = $this->db->query("
                UPDATE `tbl_coa` SET
                    `account_code` = ?,
                    `account_name` = ?,
                    `account_type` = ?,
                    `parent_code` = ?,
                    `is_active` = ?
                WHERE `account_id` = ?
            ", [
                $account_code,
                $account_name,
                $account_type,
                $parent_code ?: null,
                $is_active,
                $account_id
            ]);

            if ($query) {
                return ['status' => 'success', 'message' => 'Account updated successfully!'];
            } else {
                return ['status' => 'error', 'message' => 'An error occurred while updating.'];
            }
        }
    }
}