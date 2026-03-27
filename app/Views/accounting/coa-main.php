<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$member_id = $this->request->getPostGet('member_id');

$member_no = "";
$first_name = "";
$last_name = "";
$middle_name = "";
$address = "";
$contact_number = "";
$email = "";
$username = "";
$password = "";

if(!empty($member_id) || !is_null($member_id)) { 

    $query = $this->db->query("
    SELECT
        `member_id`,
        `member_no`,
        `first_name`,
        `last_name`,
        `middle_name`,
        `address`,
        `contact_number`,
        `email`,
        `username`,
        `password`,
        `created_by`,
        `created_at`
    FROM
        `tbl_members`
    WHERE
        `member_id` = '$member_id'"
    );

    $data = $query->getRowArray();
    $member_no = $data['member_no'];
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $middle_name = $data['middle_name'];
    $address = $data['address'];
    $contact_number = $data['contact_number'];
    $email = $data['email'];
    $username = $data['username'];
    $password = $data['password'];

}
echo view('templates/myheader.php');
?>
<style>
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 50px;
    letter-spacing: 0.3px;
}

/* Dot indicator */
.status-pill::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

/* ACTIVE (Green - subtle) */
.status-active {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
}
.status-active::before {
    background: #198754;
}

/* INACTIVE (Blue - subtle) */
.status-inactive {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}
.status-inactive::before {
    background: #0d6efd;
}
</style>
<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold">Chart of Accounts</h4>
        <button class="btn btn-success btn-sm">
            + Add New
        </button>
    </div>

    <!-- ASSETS -->
    <div class="card mb-4">
        <div class="card-header py-2">
            <h6 class="mb-0 fw-semibold">Assets</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="100">Code</th>
                        <th>Name</th>
                        <th width="180">Type</th>
                        <th width="120">Status</th>
                        <th width="80">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>120</td>
                        <td>Accounts Receivable</td>
                        <td>Current Asset</td>
                        <td><span class="status-pill status-active">Enabled</span></td>
                        <td><button class="btn btn-light btn-sm">...</button></td>
                    </tr>

                    <tr>
                        <td>150</td>
                        <td>Office Equipment</td>
                        <td>Fixed Asset</td>
                        <td><span class="status-pill status-active">Enabled</span></td>
                        <td><button class="btn btn-light btn-sm">...</button></td>
                    </tr>

                    <tr>
                        <td>151</td>
                        <td>Less Accumulated Depreciation</td>
                        <td>Fixed Asset</td>
                        <td><span class="status-pill status-active">Enabled</span></td>
                        <td><button class="btn btn-light btn-sm">...</button></td>
                    </tr>

                    <tr>
                        <td>140</td>
                        <td>Inventory</td>
                        <td>Inventory</td>
                        <td><span class="status-pill status-active">Enabled</span></td>
                        <td><button class="btn btn-light btn-sm">...</button></td>
                    </tr>

                    <tr>
                        <td>836</td>
                        <td>Cash</td>
                        <td>Bank & Cash</td>
                        <td><span class="status-pill status-active">Enabled</span></td>
                        <td><button class="btn btn-light btn-sm">...</button></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <!-- LIABILITIES -->
    <div class="card">
        <div class="card-header py-2">
            <h6 class="mb-0 fw-semibold">Liabilities</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="100">Code</th>
                        <th>Name</th>
                        <th width="180">Type</th>
                        <th width="120">Status</th>
                        <th width="80">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>200</td>
                        <td>Accounts Payable</td>
                        <td>Current Liability</td>
                        <td><span class="status-pill status-active">Enabled</span></td>
                        <td><button class="btn btn-light btn-sm">...</button></td>
                    </tr>

                    <tr>
                        <td>205</td>
                        <td>Accrued Expenses</td>
                        <td>Current Liability</td>
                        <td><span class="status-pill status-active">Enabled</span></td>
                        <td><button class="btn btn-light btn-sm">...</button></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/members-management/mymembers.js?v=2');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>

<?php
    echo view('templates/myfooter.php');
?>


