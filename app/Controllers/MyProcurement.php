<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MyProcurement extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->myprocurement = model('App\Models\MyProcurementModel');
        $this->session = session();
        $this->db = \Config\Database::connect();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'PR-MAIN': 
                $accessQuery = $this->db->query("
                    SELECT `recid`FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '8001' AND `is_active` = '1'
                ");
                if ($accessQuery->getNumRows() > 0) {
                    return $this->loadPRView();
                    break;
                }else {
                    return view('errors/html/access-restricted');
                }
    
			case 'PR-PRINT':
				return view('procurement/pr/pr-pdf');
				break;

            case 'RFQ-PRINT':
				return view('procurement/pr/rfq-pdf');
				break;

            case 'PR-SAVE': 
                $this->myprocurement->pr_save();
                return redirect()->to('myprocurement?meaction=PR-MAIN');
                break;

            case 'PR-RFQ-SAVE': 
                $this->myprocurement->pr_rfq_save();
                return redirect()->to('myprocurement?meaction=PR-MAIN');
                break;

        }
    }
    

    private function loadPRView() {

        $prhddataquery = $this->db->query("
            SELECT
                `recid`,
                `entity_name`,
                `office`,
                `prno`,
                `responsibility_code`,
                `fund_cluster`,
                `pr_date`,
                `end_user`,
                `position`,
                `charge_to`,
                `purpose`,
                `estimated_cost`,
                `added_by`,
                `added_at`
            FROM
                `tbl_pr_hd`
            ORDER BY
                `recid` DESC
        ");
        $prhddata = $prhddataquery->getResultArray();

        $productsdataquery = $this->db->query("
            SELECT
                `product_code`,
                `product_desc`,
                `uom`,
                `price`,
                `quantity`,
                `remarks`
            FROM
                `mst_products`
            ORDER BY
                `product_desc` ASC
        ");
        $productsdata = $productsdataquery->getResultArray();

        // //hd lookup data
        // $fundclusterquery = $this->db->query("SELECT `fund_cluster_code` FROM tbl_fundcluster");
        // $fundclusterdata = $fundclusterquery->getResultArray();

        // $divisionquery = $this->db->query("SELECT `division_name` FROM tbl_division");
        // $divisiondata = $divisionquery->getResultArray();

        // $psuacsquery = $this->db->query("SELECT * FROM mst_uacs WHERE allotment_class = 'Personnel Services' ORDER BY TRIM(sub_object_code) ASC");
        // $psuacsdata = $psuacsquery->getResultArray();

        // $psobjectquery = $this->db->query("SELECT DISTINCT object_code FROM mst_uacs WHERE allotment_class = 'Personnel Services'  ORDER BY TRIM(object_code) ASC");
        // $psobjectdata = $psobjectquery->getResultArray();

        // $mooeuacsquery = $this->db->query("SELECT * FROM mst_uacs WHERE allotment_class = 'Maintenance and Other Operating Expenses' ORDER BY TRIM(sub_object_code) ASC");
        // $mooeuacsdata = $mooeuacsquery->getResultArray();

        // $mooeobjectquery = $this->db->query("SELECT DISTINCT object_code FROM mst_uacs WHERE allotment_class = 'Maintenance and Other Operating Expenses' ORDER BY TRIM(object_code) ASC");
        // $mooeobjectdata = $mooeobjectquery->getResultArray();
        // $mooeobjectdata[] = ['object_code' => 'General Services'];

        // $couacsquery = $this->db->query("SELECT * FROM mst_uacs WHERE allotment_class = 'Capital Outlay' ORDER BY TRIM(sub_object_code) ASC");
        // $couacsdata = $couacsquery->getResultArray();

        // $coobjectquery = $this->db->query("SELECT DISTINCT object_code FROM mst_uacs WHERE allotment_class = 'Capital Outlay' ORDER BY TRIM(object_code) ASC");
        // $coobjectdata = $coobjectquery->getResultArray();

        // $programtitlequery = $this->db->query("SELECT program_title FROM tbl_budget_hd WHERE fund_cluster_code = '01'  GROUP BY program_title ORDER BY recid DESC");
        // $programtitledata = $programtitlequery->getResultArray();

        // $saobhdquery = $this->db->query("SELECT * FROM tbl_saob_hd ORDER BY recid DESC");
        // $saobhddata = $saobhdquery->getResultArray();


        //reference/project title lookup
        // $projectquery = $this->db->query("
        //     SELECT
        //         a.`fundcluster_id`,
        //         b.`fund_cluster_code`,
        //         a.`division_id`,
        //         c.`division_name`,
        //         a.`responsibility_code`,
        //         a.`project_title`
        //     FROM
        //         `tbl_reference_project` a
        //     JOIN
        //         `tbl_fundcluster`b
        //     ON 
        //         a.fundcluster_id = b.`recid`
        //     JOIN
        //         `tbl_division` c
        //     ON
        //         a.`division_id` = c.recid
        //     ORDER BY a.`project_title` ASC
        // ");
        // $projectdata = $projectquery->getResultArray();

        return view('procurement/pr/pr-main', [
            // 'fundclusterdata' => $fundclusterdata,
            // 'divisiondata' => $divisiondata,
            // 'psobjectdata' => $psobjectdata,
            // 'mooeobjectdata' => $mooeobjectdata,
            // 'coobjectdata' => $coobjectdata,
            // 'psuacsdata' => $psuacsdata,
            // 'mooeuacsdata' => $mooeuacsdata,
            // 'couacsdata' => $couacsdata,
            // 'budgetdtdata' => $budgetdtdata,
            // 'projectdata' => $projectdata,
            // // 'programtitledata' => $programtitledata,
            'prhddata' => $prhddata,
            'productsdata' => $productsdata
        ]);
    }
    
    
}
