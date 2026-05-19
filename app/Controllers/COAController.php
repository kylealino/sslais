<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class COAController extends BaseController
{
    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->mycoa = model('App\Models\COAModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return view('accounting/coa-main');
                break;

            case 'COA-SAVE': 
                $result = $this->mycoa->coa_save();
                return $this->response->setJSON($result);
                break;
        }
    }
}