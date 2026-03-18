<?php namespace App\Controllers;
  
use CodeIgniter\Controller;
  
class MyLogIn extends BaseController
{
	
	public function __construct()
	{
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->session = session();
	}
		
    public function index()
    {
        echo view('mylogin');
    } 

    public function auth()
    {
        $meusername = $this->request->getPostGet('MyUsername');
        $password = $this->request->getPostGet('MyPassword');
        $data = $this->Verify_User($meusername)->getRowArray();

        if($data) {

            $passdb = $data['hash_password'];
            $verify_pass = $this->Verify_Password($passdb, $password);
            if($verify_pass) { 
                $ses_data = array(
                '__xsys_myuserzicas_is_logged__' => TRUE,
                '__xsys_myuserzicas__' => $meusername 
                );

                $this->session->set($ses_data);
                return redirect()->to('/mydashboard');

            } else {
                $this->session->setFlashdata('mesyszicas_memsg_login', 'Wrong Password');
                return redirect()->to('/');
            }
        } else {
            $this->session->setFlashdata('mesyszicas_memsg_login', 'User Name not Found');
            return redirect()->to('/');
        }
    }
  
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }

    public function Verify_User($cuser='') { 
		$q = $this->db->query("select `username`,`hash_password` FROM myua_user where username = '{$cuser}' limit 1 ");
		return $q;
	}

    public function Verify_Password($cuserpassdb='',$cuserpass='') { 
		$query = $this->db->query("select if('{$cuserpassdb}' = sha2('{$cuserpass}',512),1,0) metruefalse limit 1 ");
		$row = $query->getRowArray();
		$query->freeResult();
		return $row['metruefalse'];
	} 
}
