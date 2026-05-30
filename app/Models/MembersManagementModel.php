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

    // FIXED METHOD FOR DOCUMENT UPLOAD
    public function upload_documents() {
        $member_id = $this->request->getPostGet('member_id');
        
        if (empty($member_id)) {
            return ['status' => 'error', 'message' => 'Member ID is required!'];
        }
        
        // Define document types and their field names
        $document_types = [
            'gov_id' => 'Government-issued ID',
            'proof_of_group' => 'Proof of Group Belonging',
            'id_photo' => 'ID Photo',
            'tin_gsis_proof' => 'TIN/GSIS Number Proof',
            'signed_membership' => 'Signed Membership Form',
            'proof_of_income' => 'Proof of Income',
            'bank_statement' => 'Bank Statement',
            'collateral' => 'Collateral Documents',
            'salary_deduction_auth' => 'Salary Deduction Authorization',
            'loan_purpose_declaration' => 'Loan Purpose Declaration'
        ];
        
        $uploaded_count = 0;
        $errors = [];
        
        // Create upload directory if not exists
        $upload_path = FCPATH . 'uploads/documents/' . $member_id . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        
        // Check if any file was uploaded
        $has_files = false;
        foreach ($document_types as $field_name => $doc_label) {
            $file = $this->request->getFile($field_name);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $has_files = true;
                break;
            }
        }
        
        if (!$has_files) {
            return ['status' => 'error', 'message' => 'No files selected for upload!'];
        }
        
        foreach ($document_types as $field_name => $doc_label) {
            $file = $this->request->getFile($field_name);
            
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Check file size (max 5MB)
                if ($file->getSize() > 5 * 1024 * 1024) {
                    $errors[] = $doc_label . ' exceeds 5MB limit.';
                    continue;
                }
                
                // Get file info
                $original_name = $file->getClientName();
                $file_size = $file->getSize();
                $extension = $file->getExtension();
                
                // Generate unique filename
                $timestamp = date('Ymd_His');
                $random = bin2hex(random_bytes(4));
                $new_filename = $field_name . '_' . $timestamp . '_' . $random . '.' . $extension;
                $file_path = 'uploads/documents/' . $member_id . '/' . $new_filename;
                
                // Move file
                if ($file->move($upload_path, $new_filename)) {
                    // Check if document already exists for this member and type
                    $existing = $this->db->query(
                        "SELECT doc_id, document_path FROM tbl_member_documents 
                         WHERE member_id = ? AND document_type = ? AND status = 'active'",
                        [$member_id, $field_name]
                    )->getRowArray();
                    
                    if ($existing) {
                        // Delete old file if exists
                        $old_path = FCPATH . $existing['document_path'];
                        if (file_exists($old_path)) {
                            @unlink($old_path);
                        }
                        
                        // Update existing record
                        $update = $this->db->query("
                            UPDATE tbl_member_documents 
                            SET document_name = ?, document_path = ?, file_size = ?, file_type = ?, updated_date = NOW()
                            WHERE doc_id = ?
                        ", [
                            $original_name,
                            $file_path,
                            $file_size,
                            $extension,
                            $existing['doc_id']
                        ]);
                        
                        if ($update) {
                            $uploaded_count++;
                        } else {
                            $errors[] = 'Failed to update database for ' . $doc_label;
                        }
                    } else {
                        // Insert new record
                        $insert = $this->db->query("
                            INSERT INTO tbl_member_documents 
                            (member_id, document_type, document_name, document_path, file_size, file_type, uploaded_by)
                            VALUES (?, ?, ?, ?, ?, ?, ?)
                        ", [
                            $member_id,
                            $field_name,
                            $original_name,
                            $file_path,
                            $file_size,
                            $extension,
                            $this->cuser
                        ]);
                        
                        if ($insert) {
                            $uploaded_count++;
                        } else {
                            $errors[] = 'Failed to insert database for ' . $doc_label;
                        }
                    }
                } else {
                    $errors[] = 'Failed to move uploaded file for ' . $doc_label;
                }
            }
        }
        
        if ($uploaded_count > 0) {
            $message = $uploaded_count . ' document(s) uploaded successfully!';
            if (!empty($errors)) {
                $message .= ' However: ' . implode(', ', $errors);
            }
            return ['status' => 'success', 'message' => $message];
        } else {
            $error_msg = 'No documents were uploaded. ';
            if (!empty($errors)) {
                $error_msg .= implode(', ', $errors);
            } else {
                $error_msg .= 'Please check file types and sizes.';
            }
            return ['status' => 'error', 'message' => $error_msg];
        }
    }
    
    // Helper method to get documents for a member
    public function get_member_documents($member_id) {
        return $this->db->query(
            "SELECT * FROM tbl_member_documents WHERE member_id = ? AND status = 'active' ORDER BY upload_date DESC",
            [$member_id]
        )->getResultArray();
    }
}