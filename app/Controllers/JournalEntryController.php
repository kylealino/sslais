<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class JournalEntryController  extends BaseController
{
    public function __construct()
	{
		$this->request = \Config\Services::request();
        $this->myjournalentry = model('App\Models\JournalEntryModel');
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
	}

    public function index() {
        
        $meaction = $this->request->getPostGet('meaction');
    
        switch ($meaction) {
            case 'MAIN': 
                return $this->loadJournalsView();
                break;

            case 'JOURNAL-ENTRY-SAVE': 
                $this->myjournalentry->journalentry_save();
                return redirect()->to('myjournalentry?meaction=MAIN');
                break;
            

        }
    }

    private function loadJournalsView() {

        $journaldataquery = $this->db->query("
            SELECT
                `journal_id`,
                `journal_no`,
                `posting_date`,
                `reference_no`,
                `journal_type`,
                `remarks`,
                `status`,
                `approved_by`,
                `created_by`,
                `created_at`
            FROM
                `tbl_journal`
            ORDER BY
                journal_id DESC
        ");
        $journaldata = $journaldataquery->getResultArray();

        return view('accounting/journal-entry-main', [
            'journaldata' => $journaldata
        ]);
    }

}
