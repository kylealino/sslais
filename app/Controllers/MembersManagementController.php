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
                return redirect()->to('myua?meaction=MAIN');
                break;
            
            case 'MAIN-ACCESS-SAVE': 
                $this->mymembers->user_access_save();
                return redirect()->to('myua?meaction=MAIN');
                break;

        }
    }

    private function loadMembersView() {

        $membersdataquery = $this->db->query("
            SELECT
                `member_id`,
                `member_no`,
                `first_name`,
                `last_name`,
                `middle_name`,
                `address`,
                `contact_number`,
                `email`
            FROM
                `tbl_members`
            ORDER BY
                member_id DESC
        ");
        $membersdata = $membersdataquery->getResultArray();

        return view('members-management/members-main', [
            'membersdata' => $membersdata
        ]);
    }
    
    
}
