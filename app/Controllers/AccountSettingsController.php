<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AccountSettingsController  extends BaseController
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
                return view('members-management/account-main')
                break;

            case 'ACCOUNT-SAVE': 
                $this->mymembers->members_save();
                return redirect()->to('myua?meaction=MAIN');
                break;
            

        }
    }

}
