<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MembersManagementController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->mymembers = model('App\Models\MembersManagementModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return $this->loadMembersView();
                break;

            case 'MEMBERS-SAVE': 
                $result = $this->mymembers->members_save();
                return $this->response->setJSON($result);
                break;

            case 'MEMBERS-PRINT':
                return view('members-management/members-profile-pdf');
                break;
        }
    }

    private function loadMembersView() {
        
        $member_id = $this->request->getPostGet('member_id');
        
        $membersdataquery = $this->db->query("
        SELECT
            a.member_id,
            a.member_no,
            a.first_name,
            a.last_name,
            a.middle_name,
            a.address,
            a.contact_number,
            a.email,

            COUNT(l.loan_id) AS loan_count,
            COALESCE(SUM(l.loan_amount), 0) AS loan_amount

        FROM tbl_members a
        LEFT JOIN tbl_loans l 
            ON l.member_id = a.member_id

        GROUP BY 
            a.member_id,
            a.member_no,
            a.first_name,
            a.last_name,
            a.middle_name,
            a.address,
            a.contact_number,
            a.email

        ORDER BY a.member_id DESC;
        ");
        $membersdata = $membersdataquery->getResultArray();
        
        // Get member data for edit
        $member_data = [];
        if(!empty($member_id)) {
            $member_query = $this->db->query("SELECT * FROM tbl_members WHERE member_id = ?", [$member_id]);
            $member_data = $member_query->getRowArray();
        }
        
        return view('members-management/members-main', [
            'membersdata' => $membersdata,
            'member_data' => $member_data,
            'member_id' => $member_id
        ]);
    }
}