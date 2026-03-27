<?php
namespace App\Models;
use CodeIgniter\Model;

class JournalEntryModel extends Model
{

    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
		$this->db = \Config\Database::connect();
		$this->cuser = $this->session->get('__xsys_myuserzicas__');
        
    }

	public function journalentry_save() { 
		$journal_id   = $this->request->getPostGet('journal_id');
		$journal_no   = $this->request->getPostGet('journal_no');
		$posting_date = $this->request->getPostGet('posting_date');
		$reference_no = $this->request->getPostGet('reference_no');
		$journal_type = $this->request->getPostGet('journal_type');
		$remarks      = $this->request->getPostGet('remarks');
		$status       = $this->request->getPostGet('status');
		$approved_by  = $this->request->getPostGet('approved_by');
		$journaldtdata = $this->request->getPostGet('journaldtdata'); // array from JS

		if (empty($journal_no)) {
			echo "
			<script>
			toastr.error('Journal No is required!', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}

		if (empty($journal_id)) {
			$query = $this->db->query("
				INSERT INTO `tbl_journal` (
					`journal_no`,
					`posting_date`,
					`reference_no`,
					`journal_type`,
					`remarks`,
					`status`,
					`approved_by`,
					`created_by`
				) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
			", [
				$journal_no,
				$posting_date,
				$reference_no,
				$journal_type,
				$remarks,
				$status,
				$approved_by,
				$this->cuser
			]);

			$journal_id = $this->db->insertID();
			if (!empty($journaldtdata)) {
				// Loop through each line in journaldtdata
				for ($aa = 0; $aa < count($journaldtdata); $aa++) {
					$medata = explode("x|x", $journaldtdata[$aa]);
					$account_code  = $medata[0];
					$account_name  = $medata[1];
					$debit_amount  = $medata[2];
					$credit_amount = $medata[3];
					$description   = $medata[4];
					$cost_center   = $medata[5];

					$query = $this->db->query("
						INSERT INTO tbl_journal_details (
							`journal_id`,
							`account_code`,
							`account_name`,
							`debit_amount`,
							`credit_amount`,
							`description`,
							`cost_center`,
							`created_by`
						) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
					", [
						$journal_id,
						$account_code,
						$account_name,
						$debit_amount,
						$credit_amount,
						$description,
						$cost_center,
						$this->cuser
					]);
				}
			}
			$status = "Journal saved successfully";
			$color = "success";
		}else{

		    $query = $this->db->query("
				UPDATE `tbl_journal` SET
					`journal_no`   = ?,
					`posting_date` = ?,
					`reference_no` = ?,
					`journal_type` = ?,
					`remarks`      = ?,
					`status`       = ?,
					`approved_by`  = ?
				WHERE `journal_id` = ?
			", [
				$journal_no,
				$posting_date,
				$reference_no,
				$journal_type,
				$remarks,
				$status,
				$approved_by,
				$journal_id
			]);

			if (!empty($journaldtdata)) {
				// Delete existing journal details for this journal
				$query = $this->db->query("DELETE FROM tbl_journal_details WHERE `journal_id` = '$journal_id'");

				// Loop through each line in journaldtdata
				for ($aa = 0; $aa < count($journaldtdata); $aa++) {
					$medata = explode("x|x", $journaldtdata[$aa]);
					$account_code  = $medata[0];
					$account_name  = $medata[1];
					$debit_amount  = $medata[2];
					$credit_amount = $medata[3];
					$description   = $medata[4];
					$cost_center   = $medata[5];

					$query = $this->db->query("
						INSERT INTO tbl_journal_details (
							`journal_id`,
							`account_code`,
							`account_name`,
							`debit_amount`,
							`credit_amount`,
							`description`,
							`cost_center`,
							`created_by`
						) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
					", [
						$journal_id,
						$account_code,
						$account_name,
						$debit_amount,
						$credit_amount,
						$description,
						$cost_center,
						$this->cuser
					]);
				}
			} else {
				// If no lines, just delete existing details
				$query = $this->db->query("DELETE FROM tbl_journal_details WHERE `journal_id` = '$journal_id'");
			}

			$status = "Payment updated successfully";
			$color = "info";

		}

		if ($query) {
			// Echo JavaScript to show the toast and then redirect
			echo "
			<script>
				toastr.{$color}('{$status}!', 'Well Done!', {
						progressBar: true,
						closeButton: true,
						timeOut:2500,
					});
				setTimeout(function() {
						window.location.href = 'myjournalentry?meaction=MAIN'; // Redirect to MAIN view
					}, 2500); // 2-second delay for user to see the toast
			</script>
			";
			exit; // Stop further PHP execution after the toast
		} else {
			// If there's an error, show an alert message
			echo "<script type='text/javascript'>
					alert('An error occurred while executing the query.');
				  </script>";
			exit;
		}
	}
	
	
} //end main class
?>