<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class LoanProfileController  extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->myloanprofile = model('App\Models\LoanProfileModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return view('loan-availment/loan-profile-main');
                break;

            case 'LOAN-PAYMENT-SAVE': 
                $this->myloanprofile->loanpayment_save();
                return redirect()->to('myloanprofile?meaction=MAIN');
                break;
            

        }
    }

}
