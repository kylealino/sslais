<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');

$member_id = "";
$member_no = "";
$first_name = "";
$last_name = "";
$middle_name = "";
$address = "";
$contact_number = "";
$email = "";
$username = "";
$password = "";

if(!empty($this->cuser) || !is_null($this->cuser)) { 

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
        `username` = '$this->cuser'"
    );

    $data = $query->getRowArray();
    $member_id = $data['member_id'];
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

<div class="container-fluid">
    <div class="row me-myaccount-outp-msg mx-0"></div>

    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

    <!-- HEADER -->
    <div class="row mb-3">
        <div class="col-12">
            <h4 class="fw-bold">Account Settings</h4>
            <small class="text-muted">Manage your profile, password, and personal details</small>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="row g-4">

                <!-- PROFILE CARD -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm text-center h-100">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Profile Photo</h6>

                            <img src="<?=base_url('assets/images/profile/user-1.jpg')?>"
                                class="rounded-circle border shadow-sm mb-3"
                                width="120" height="120">

                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-primary px-3">
                                    <i class="ti ti-upload"></i> Upload
                                </button>
                                <button class="btn btn-sm btn-light text-danger px-3">
                                    Reset
                                </button>
                            </div>

                            <small class="text-muted d-block mt-2">
                                JPG, PNG (Max: 800KB)
                            </small>
                        </div>
                    </div>
                </div>

                <!-- PASSWORD CARD -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <form action="<?=site_url();?>myaccount?meaction=ACCOUNT-SAVE" method="post" class="myaccount-validation">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Security Settings</h6>

                                <!-- USER INFO (ADDED HERE) -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Username</label>
                                        <input type="text" id="username" name="username"
                                            value="<?=$username;?>" disabled
                                            class="form-control form-control-sm bg-light"/>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Member No.</label>
                                        <input type="text" id="member_no" name="member_no"
                                            value="<?=$member_no;?>" disabled
                                            class="form-control form-control-sm bg-light"/>
                                    </div>
                                </div>

                                <!-- PASSWORD FIELDS -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Current Password</label>
                                        <div class="input-group input-group-sm">
                                            <input type="password" class="form-control" id="password" value="<?=$password;?>">
                                            <button class="btn btn-light" type="button" id="togglePassword">
                                                <i class="ti ti-eye" id="toggleIcon"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">New Password</label>
                                        <div class="input-group input-group-sm">
                                            <input type="password" class="form-control" id="newpassword" value="<?=$password;?>">
                                            <button class="btn btn-light" type="button" id="newtogglePassword">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                <!-- PERSONAL DETAILS -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Personal Information</h6>
                            <div class="row g-3">
                                <input type="hidden" id="member_id" name="member_id" value="<?=$member_id;?>"/>

                                <div class="col-md-4">
                                    <label class="form-label">First Name</label>
                                    <input type="text" id="first_name" name="first_name"
                                        value="<?=$first_name;?>"
                                        class="form-control form-control-sm"/>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" id="last_name" name="last_name"
                                        value="<?=$last_name;?>"
                                        class="form-control form-control-sm"/>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" id="middle_name" name="middle_name"
                                        value="<?=$middle_name;?>"
                                        class="form-control form-control-sm"/>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" id="contact_number" name="contact_number"
                                        value="<?=$contact_number;?>"
                                        class="form-control form-control-sm"/>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="text" id="email" name="email"
                                        value="<?=$email;?>"
                                        class="form-control form-control-sm"/>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" id="address" rows="3"
                                        class="form-control form-control-sm"><?=$address;?></textarea>
                                </div>

                                <!-- SAVE BUTTON -->
                                <div class="col-12 text-end mt-3">
                                    <button type="submit" class="btn btn-success btn-sm px-4">
                                        <i class="ti ti-device-floppy me-1"></i> Save Changes
                                    </button>
                                </div>

                            </div>
                        </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/members-management/myaccount.js?v=1');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>
<script>
    __mysys_account_ent.__account_saving();

    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');

        if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('ti-eye');
        icon.classList.add('ti-eye-off');
        } else {
        input.type = 'password';
        icon.classList.remove('ti-eye-off');
        icon.classList.add('ti-eye');
        }
    });
    document.getElementById('newtogglePassword').addEventListener('click', function () {
        const input = document.getElementById('newpassword');
        const icon = document.getElementById('toggleIcon');

        if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('ti-eye');
        icon.classList.add('ti-eye-off');
        } else {
        input.type = 'password';
        icon.classList.remove('ti-eye-off');
        icon.classList.add('ti-eye');
        }
    });
</script>


<?php
    echo view('templates/myfooter.php');
?>
