<?php
namespace App\Models;
use CodeIgniter\Model;

class MembersManagementModel extends Model
{

    protected $db;

    public function __construct(){
        parent::__construct();
        $this->session = session();
        $this->request = \Config\Services::request();
		$this->db = \Config\Database::connect();
		$this->cuser = $this->session->get('__xsys_myuserzicas__');
        
    }

	public function members_save() { 
		$member_id = $this->request->getPostGet('member_id');
		$member_no = $this->request->getPostGet('member_no');
		$last_name = $this->request->getPostGet('last_name');
		$first_name = $this->request->getPostGet('first_name');
		$middle_name = $this->request->getPostGet('middle_name');
		$contact_number = $this->request->getPostGet('contact_number');
		$address = $this->request->getPostGet('address');
		$email = $this->request->getPostGet('email');
		$username = $this->request->getPostGet('username');
		$password = $this->request->getPostGet('password');
		$hash_password = hash('sha512', $password);

		// var_dump(
		// 	$member_id,
		// 	$member_no,
		// 	$last_name,
		// 	$first_name,
		// 	$middle_name,
		// 	$contact_number,
		// 	$address,
		// 	$email
		// );
		// die();

		// if (empty($payee_name)) {
		// 	echo "
		// 	<script>
		// 	toastr.error('Payee name is required!', 'Oops!', {
		// 			progressBar: true,
		// 			closeButton: true,
		// 			timeOut:2000,
		// 		});
		// 	</script>
		// 	";
		// 	die();
		// }

		if (empty($member_id)) {
			$query = $this->db->query("
				INSERT INTO `tbl_members` (
					`member_no`,
					`last_name`,
					`first_name`,
					`middle_name`,
					`contact_number`,
					`address`,
					`email`,
					`username`,
					`password`,
					`hash_password`,
					`created_by`
				)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
			", [
				$member_no,
				$last_name,
				$first_name,
				$middle_name,
				$contact_number,
				$address,
				$email,
				$username,
				$password,
				$hash_password,
				$this->cuser,
			]);
			$status = "Member Saved successfully";
			$color = "success";
		}else{
			$query = $this->db->query("
				UPDATE `tbl_members`
				SET
					`member_no` = ?,
					`last_name` = ?,
					`first_name` = ?,
					`middle_name` = ?,
					`contact_number` = ?,
					`address` = ?,
					`email` = ?,
					`username` = ?,
					`password` = ?,
					`hash_password` = ?,
					`created_by` = ?
				WHERE `member_id` = ?
			", [
				$member_no,
				$last_name,
				$first_name,
				$middle_name,
				$contact_number,
				$address,
				$email,
				$username,
				$password,
				$hash_password,
				$this->cuser,
				$member_id
			]);
			$status = "Member Updated successfully";
			$color = "info";
		}
		
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
						window.location.href = 'mymembers?meaction=MAIN'; // Redirect to MAIN view
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