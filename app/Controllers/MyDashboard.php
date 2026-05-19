<?php

namespace App\Controllers;

class MyDashboard extends BaseController
{
    public function __construct(){
		$this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
	}

    public function index(){
        if (!session('__xsys_myuserzicas_is_logged__')) {
            return view('MyLogin');
        }

        return view('MyDashboard'); 
    }

}
