<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AccountSettingsController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->myaccount = model('App\Models\AccountSettingsModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return $this->loadAccountView();
                break;

            case 'ACCOUNT-SAVE': 
                $result = $this->myaccount->account_save();
                return $this->response->setJSON($result);
                break;

            case 'UPLOAD-PROFILE-PHOTO':
                $result = $this->myaccount->upload_profile_photo();
                return $this->response->setJSON($result);
                break;

            case 'RESET-PROFILE-PHOTO':
                $result = $this->myaccount->reset_profile_photo();
                return $this->response->setJSON($result);
                break;
        }
    }

    private function loadAccountView() {
        // Get member data including profile photo
        $member_data = [];
        if(!empty($this->cuser)) {
            $query = $this->db->query("
                SELECT 
                    `member_id`,
                    `member_no`,
                    `first_name`,
                    `last_name`,
                    `middle_name`,
                    `address`,
                    `contact_number`,
                    `email`,
                    `username`,
                    `password`,
                    `profile_photo_path`,
                    `profile_photo_name`,
                    `created_by`,
                    `created_at`
                FROM `tbl_members`
                WHERE `username` = ?", [$this->cuser]
            );
            $member_data = $query->getRowArray();
        }
        
        return view('members-management/account-main', ['member_data' => $member_data]);
    }
}