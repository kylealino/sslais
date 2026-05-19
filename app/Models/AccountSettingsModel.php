<?php
namespace App\Models;
use CodeIgniter\Model;

class AccountSettingsModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function account_save() { 
        $member_id = $this->request->getPostGet('member_id');
        $member_no = $this->request->getPostGet('member_no');
        $last_name = $this->request->getPostGet('last_name');
        $first_name = $this->request->getPostGet('first_name');
        $middle_name = $this->request->getPostGet('middle_name');
        $contact_number = $this->request->getPostGet('contact_number');
        $address = $this->request->getPostGet('address');
        $email = $this->request->getPostGet('email');
        $username = $this->request->getPostGet('username');
        $password = $this->request->getPostGet('password');
        $newpassword = $this->request->getPostGet('newpassword');
        $hash_password = hash('sha512', $newpassword);
        
        // Validation
        if (empty($last_name)) {
            return ['status' => 'error', 'message' => 'Last name is required!'];
        }
        if (empty($first_name)) {
            return ['status' => 'error', 'message' => 'First name is required!'];
        }
        if (empty($middle_name)) {
            return ['status' => 'error', 'message' => 'Middle name is required!'];
        }
        if (empty($contact_number)) {
            return ['status' => 'error', 'message' => 'Contact number is required!'];
        }
        if (empty($address)) {
            return ['status' => 'error', 'message' => 'Address is required!'];
        }
        if (empty($email)) {
            return ['status' => 'error', 'message' => 'Email is required!'];
        }

		if (empty($email)) {
            return ['status' => 'error', 'message' => 'Email is required!'];
        }

		if (empty($newpassword)) {
			$query = $this->db->query("
				UPDATE `tbl_members`
				SET
					`last_name` = ?,
					`first_name` = ?,
					`middle_name` = ?,
					`contact_number` = ?,
					`address` = ?,
					`email` = ?
				WHERE `member_id` = ?
			", [
				$last_name,
				$first_name,
				$middle_name,
				$contact_number,
				$address,
				$email,
				$member_id
			]);
		}else{
			$query = $this->db->query("
				UPDATE `tbl_members`
				SET
					`last_name` = ?,
					`first_name` = ?,
					`middle_name` = ?,
					`contact_number` = ?,
					`address` = ?,
					`email` = ?,
					`password` = ?,
					`hash_password` = ?
				WHERE `member_id` = ?
			", [
				$last_name,
				$first_name,
				$middle_name,
				$contact_number,
				$address,
				$email,
				$newpassword,
				$hash_password,
				$member_id
			]);
		}

        
        if ($query) {
            return ['status' => 'success', 'message' => 'Account Updated Successfully!'];
        } else {
            return ['status' => 'error', 'message' => 'An error occurred while updating.'];
        }
    }
}