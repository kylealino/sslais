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
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return $this->loadMembersView();
                break;

            case 'MEMBERS-SAVE': 
                $this->mymembers->members_save();
                return redirect()->to('mymembers?meaction=MAIN');
                break;
            
        }
    }

    private function loadMembersView() {

        $membersdataquery = $this->db->query("
            SELECT
                a.`member_id`,
                a.`member_no`,
                a.`first_name`,
                a.`last_name`,
                a.`middle_name`,
                a.`address`,
                a.`contact_number`,
                a.`email`,
                (select loan_amount from tbl_loans where member_id = a.`member_id`) loan_amount
            FROM
                `tbl_members` a
            ORDER BY
                member_id DESC
        ");
        $membersdata = $membersdataquery->getResultArray();

        return view('members-management/members-main', [
            'membersdata' => $membersdata
        ]);
    }
    
    
}
