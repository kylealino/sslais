<?php
namespace App\Models;
use CodeIgniter\Model;

class MembersManagementModel extends Model
{
    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function members_save() { 
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
        $hash_password = hash('sha512', $password);
        
        // I. Member Information - New Fields
        $date_of_birth = $this->request->getPostGet('date_of_birth');
        $place_of_birth = $this->request->getPostGet('place_of_birth');
        $age = $this->request->getPostGet('age');
        $civil_status = $this->request->getPostGet('civil_status');
        $gender = $this->request->getPostGet('gender');
        $tin = $this->request->getPostGet('tin');
        $gsis_number = $this->request->getPostGet('gsis_number');
        
        // II. Contact Information - Permanent Address
        $permanent_street = $this->request->getPostGet('permanent_street');
        $permanent_barangay = $this->request->getPostGet('permanent_barangay');
        $permanent_city = $this->request->getPostGet('permanent_city');
        $permanent_province = $this->request->getPostGet('permanent_province');
        $permanent_zip = $this->request->getPostGet('permanent_zip');
        
        // II. Contact Information - Present Address
        $present_street = $this->request->getPostGet('present_street');
        $present_barangay = $this->request->getPostGet('present_barangay');
        $present_city = $this->request->getPostGet('present_city');
        $present_province = $this->request->getPostGet('present_province');
        $present_zip = $this->request->getPostGet('present_zip');
        
        // II. Contact Information - Additional Phone Numbers
        $home_phone = $this->request->getPostGet('home_phone');
        $office_phone = $this->request->getPostGet('office_phone');
        
        // III. Employment Information
        $department_agency = $this->request->getPostGet('department_agency');
        $position = $this->request->getPostGet('position');
        $salary_grade = $this->request->getPostGet('salary_grade');
        
        // IV. Beneficiaries
        $beneficiary1_name = $this->request->getPostGet('beneficiary1_name');
        $beneficiary1_address = $this->request->getPostGet('beneficiary1_address');
        $beneficiary1_contact = $this->request->getPostGet('beneficiary1_contact');
        $beneficiary1_relationship = $this->request->getPostGet('beneficiary1_relationship');
        
        $beneficiary2_name = $this->request->getPostGet('beneficiary2_name');
        $beneficiary2_address = $this->request->getPostGet('beneficiary2_address');
        $beneficiary2_contact = $this->request->getPostGet('beneficiary2_contact');
        $beneficiary2_relationship = $this->request->getPostGet('beneficiary2_relationship');

        // Validation
        if (empty($member_no)) {
            return ['status' => 'error', 'message' => 'Member number is required!'];
        }
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
        if (empty($email)) {
            return ['status' => 'error', 'message' => 'Email is required!'];
        }

        if (empty($member_id)) {
            // Insert new member
            $query = $this->db->query("
                INSERT INTO `tbl_members` (
                    `member_no`,
                    `last_name`,
                    `first_name`,
                    `middle_name`,
                    `contact_number`,
                    `address`,
                    `email`,
                    `username`,
                    `password`,
                    `hash_password`,
                    `date_of_birth`,
                    `place_of_birth`,
                    `age`,
                    `civil_status`,
                    `gender`,
                    `tin`,
                    `gsis_number`,
                    `permanent_street`,
                    `permanent_barangay`,
                    `permanent_city`,
                    `permanent_province`,
                    `permanent_zip`,
                    `present_street`,
                    `present_barangay`,
                    `present_city`,
                    `present_province`,
                    `present_zip`,
                    `home_phone`,
                    `office_phone`,
                    `department_agency`,
                    `position`,
                    `salary_grade`,
                    `beneficiary1_name`,
                    `beneficiary1_address`,
                    `beneficiary1_contact`,
                    `beneficiary1_relationship`,
                    `beneficiary2_name`,
                    `beneficiary2_address`,
                    `beneficiary2_contact`,
                    `beneficiary2_relationship`,
                    `created_by`
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ", [
                $member_no,
                $last_name,
                $first_name,
                $middle_name,
                $contact_number,
                $address,
                $email,
                $username,
                $password,
                $hash_password,
                $date_of_birth,
                $place_of_birth,
                $age,
                $civil_status,
                $gender,
                $tin,
                $gsis_number,
                $permanent_street,
                $permanent_barangay,
                $permanent_city,
                $permanent_province,
                $permanent_zip,
                $present_street,
                $present_barangay,
                $present_city,
                $present_province,
                $present_zip,
                $home_phone,
                $office_phone,
                $department_agency,
                $position,
                $salary_grade,
                $beneficiary1_name,
                $beneficiary1_address,
                $beneficiary1_contact,
                $beneficiary1_relationship,
                $beneficiary2_name,
                $beneficiary2_address,
                $beneficiary2_contact,
                $beneficiary2_relationship,
                $this->cuser,
            ]);
            
            if ($query) {
                return ['status' => 'success', 'message' => 'Member Saved Successfully!'];
            } else {
                return ['status' => 'error', 'message' => 'Database error occurred!'];
            }
        } else {
            // Update existing member
            $query = $this->db->query("
                UPDATE `tbl_members`
                SET
                    `member_no` = ?,
                    `last_name` = ?,
                    `first_name` = ?,
                    `middle_name` = ?,
                    `contact_number` = ?,
                    `address` = ?,
                    `email` = ?,
                    `username` = ?,
                    `password` = ?,
                    `hash_password` = ?,
                    `date_of_birth` = ?,
                    `place_of_birth` = ?,
                    `age` = ?,
                    `civil_status` = ?,
                    `gender` = ?,
                    `tin` = ?,
                    `gsis_number` = ?,
                    `permanent_street` = ?,
                    `permanent_barangay` = ?,
                    `permanent_city` = ?,
                    `permanent_province` = ?,
                    `permanent_zip` = ?,
                    `present_street` = ?,
                    `present_barangay` = ?,
                    `present_city` = ?,
                    `present_province` = ?,
                    `present_zip` = ?,
                    `home_phone` = ?,
                    `office_phone` = ?,
                    `department_agency` = ?,
                    `position` = ?,
                    `salary_grade` = ?,
                    `beneficiary1_name` = ?,
                    `beneficiary1_address` = ?,
                    `beneficiary1_contact` = ?,
                    `beneficiary1_relationship` = ?,
                    `beneficiary2_name` = ?,
                    `beneficiary2_address` = ?,
                    `beneficiary2_contact` = ?,
                    `beneficiary2_relationship` = ?,
                    `created_by` = ?
                WHERE `member_id` = ?
            ", [
                $member_no,
                $last_name,
                $first_name,
                $middle_name,
                $contact_number,
                $address,
                $email,
                $username,
                $password,
                $hash_password,
                $date_of_birth,
                $place_of_birth,
                $age,
                $civil_status,
                $gender,
                $tin,
                $gsis_number,
                $permanent_street,
                $permanent_barangay,
                $permanent_city,
                $permanent_province,
                $permanent_zip,
                $present_street,
                $present_barangay,
                $present_city,
                $present_province,
                $present_zip,
                $home_phone,
                $office_phone,
                $department_agency,
                $position,
                $salary_grade,
                $beneficiary1_name,
                $beneficiary1_address,
                $beneficiary1_contact,
                $beneficiary1_relationship,
                $beneficiary2_name,
                $beneficiary2_address,
                $beneficiary2_contact,
                $beneficiary2_relationship,
                $this->cuser,
                $member_id
            ]);
            
            if ($query) {
                return ['status' => 'success', 'message' => 'Member Updated Successfully!'];
            } else {
                return ['status' => 'error', 'message' => 'Database error occurred!'];
            }
        }
    }
}