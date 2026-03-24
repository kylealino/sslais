<?php
namespace App\Models;
use CodeIgniter\Model;

class LoanProfileModel extends Model
{

    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
		$this->db = \Config\Database::connect();
		$this->cuser = $this->session->get('__xsys_myuserzicas__');
        
    }

	public function loanpayment_save() { 
		$loan_id = $this->request->getPostGet('loan_id');
		$member_id = $this->request->getPostGet('member_id');
		$interest = $this->request->getPostGet('interest');
		$principal = $this->request->getPostGet('principal');
		$total_payment = $this->request->getPostGet('total_payment');
		$payment_date = $this->request->getPostGet('payment_date');
		$ammortization_id = $this->request->getPostGet('ammortization_id');

		// var_dump(
		// 	$loan_id,
		// 	$member_id,
		// 	$interest,
		// 	$principal,
		// 	$total_payment,
		// 	$payment_date,
		// 	$ammortization_id
		// );
		// die();

		$query = $this->db->query("
			INSERT INTO `tbl_loans_payment`(
				`loan_id`,
				`member_id`,
				`interest`,
				`principal`,
				`total_payment`,
				`payment_date`,
				`created_by`
			)
			VALUES (?, ?, ?, ?, ?, ?, ?)
		", [
			$loan_id,
			$member_id,
			$interest,
			$principal,
			$total_payment,
			$payment_date,
			$this->cuser
		]);

		$query = $this->db->query("
			UPDATE `tbl_loans_ammortization`
			SET
				`payment_status` = ?
			WHERE `ammortization_id` = ?
		", [
			'Paid',
			$ammortization_id
		]);

		$status = "Payment Saved successfully";
		$color = "success";

		if ($query) {
			// Echo JavaScript to show the toast and then redirect
			echo "
			<script>
				toastr.$color('{$status}!', 'Well Done!', {
						progressBar: true,
						closeButton: true,
						timeOut:2500,
					});
				setTimeout(function() {
						window.location.href = 'myloanprofile?meaction=MAIN&loan_id=$loan_id'; // Redirect to MAIN view
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
	
	public function payee_delete() { 
		$recid = $this->request->getPostGet('recid');

		$accessquery = $this->db->query("
			SELECT `recid`FROM tbl_user_access WHERE `username` = '{$this->cuser}' AND `access_code` = '5004' AND `is_active` = '1'
		");
		if ($accessquery->getNumRows() == 0) {
			echo "
			<script>
			toastr.error('Deleting Access Denied! Please Contact the Administrator.', 'Oops!', {
					progressBar: true,
					closeButton: true,
					timeOut:2000,
				});
			</script>
			";
			die();
		}


		$query = $this->db->query("
			DELETE FROM `tbl_payee` WHERE `recid` = '$recid'
		");
		$status = "Payee deleted successfully";
		
		if ($query) {
			// Echo JavaScript to show the toast and then redirect
			echo "
			<script>
				toastr.warning('{$status}!', 'Well Done!', {
						progressBar: true,
						closeButton: true,
						timeOut:2500,
					});
				setTimeout(function() {
						window.location.href = 'mypayee?meaction=MAIN'; // Redirect to MAIN view
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