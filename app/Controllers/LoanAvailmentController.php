<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class LoanAvailmentController  extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->myloanavailment = model('App\Models\LoanAvailmentModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return view('loan-availment/loan-main');
                break;

            case 'LOAN-AVAILMENT-SAVE': 
                $this->myloanavailment->loanavailment_save();
                return redirect()->to('myloanavailment?meaction=MAIN');
                break;
            

        }
    }

}
