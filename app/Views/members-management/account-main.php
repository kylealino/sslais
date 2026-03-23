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
    <div class="row me-myaccount-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    <div class="row mb-2 mt-0">
        <h4 class="fw-semibold mb-8">Account Settings</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Members Management</li>
                <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">Account Settings</span></li>
            </ol>
        </nav>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-account" role="tabpanel" aria-labelledby="pills-account-tab" tabindex="0">
                    <div class="row">
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <div class="card w-100 border position-relative overflow-hidden">
                            <div class="card-body p-4">
                                <h4 class="card-title">Change Profile</h4>
                                <p class="card-subtitle mb-4">Change your profile picture from here</p>
                                <div class="text-center">
                                <img src="<?=base_url('assets/images/profile/user-1.jpg')?>" alt="flexy-img" class="img-fluid rounded-circle" width="120" height="120">
                                <div class="d-flex align-items-center justify-content-center my-4 gap-6">
                                    <button class="btn btn-primary">Upload</button>
                                    <button class="btn bg-danger-subtle text-danger">Reset</button>
                                </div>
                                <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex align-items-stretch">
                                <div class="card w-100 border position-relative overflow-hidden">
                                <form action="<?=site_url();?>myaccount?meaction=ACCOUNT-SAVE" method="post" class="myaccount-validation">
                                <div class="card-body p-4">
                                    <h4 class="card-title">Change Password</h4>
                                    <p class="card-subtitle mb-4">To change your password please confirm here</p>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Current Password</label>
                                        <div class="input-group input-group-sm">
                                            <input type="password" class="form-control" id="password" value="<?=$password;?>">
                                            <button class="btn btn-light" type="button" id="togglePassword">
                                                <i class="ti ti-eye" id="toggleIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleInputPassword2" class="form-label">New Password</label>
                                        <div class="input-group input-group-sm">
                                            <input type="password" class="form-control" id="newpassword" value="<?=$password;?>">
                                            <button class="btn btn-light" type="button" id="newtogglePassword">
                                                <i class="ti ti-eye" id="toggleIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card w-100 border position-relative overflow-hidden mb-0">
                                <div class="card-body p-4">
                                    <h4 class="card-title">Personal Details</h4>
                                    <p class="card-subtitle mb-4">To change your personal detail , edit and save from here</p>
                                    
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-1">
                                                <label for="exampleInputtext" class="form-label">Username</label>
                                                <input type="text" id="username" name="username" value="<?=$username;?>" disabled class="username form-control form-control-sm"/>
                                            </div>
                                            <div class="mb-1">
                                                <label for="member_no" class="form-label">Member No.</label>
                                                <input type="text" id="member_no" name="member_no" value="<?=$member_no;?>" disabled class="member_no form-control form-control-sm"/>
                                            </div>
                                            <div class="mb-1">
                                                <label for="first_name" class="form-label">First Name</label>
                                                <input type="hidden" class="form-control form-control-sm" id="member_id" name="member_id" value="<?=$member_id;?>"/>
                                                <input type="text" id="first_name" name="first_name" value="<?=$first_name;?>" class="first_name form-control form-control-sm"/>
                                            </div>
                                            <div class="mb-1">
                                                <label class="form-label">Last Name</label>
                                                <input type="text" id="last_name" name="last_name" value="<?=$last_name;?>" class="last_name form-control form-control-sm"/>
                                            </div>
                                            <div class="mb-1">
                                                <label for="middle_name" class="form-label">Middle Name</label>
                                                <input type="text" id="middle_name" name="middle_name" value="<?=$middle_name;?>" class="middle_name form-control form-control-sm"/>
                                            </div>
                                            </div>
                                            <div class="col-lg-6">
                                            <div class="mb-1">
                                                <label for="contact_number" class="form-label">Contact Number</label>
                                                <input type="text" id="contact_number" name="contact_number" value="<?=$contact_number;?>" class="contact_number form-control form-control-sm"/>
                                            </div>
                                            <div class="mb-1">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="text" id="email" name="email" value="<?=$email;?>" class="email form-control form-control-sm"/>
                                            </div>
                                            <div class="mb-1">
                                                <label for="address" class="form-label">Address</label>
                                                <textarea name="address" id="address" placeholder="" rows="5" class="address form-control form-control-sm"><?=$address;?></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
                                                <button type="submit" class="btn bg-success-subtle text-success btn-sm"><i class="ti ti-brand-doctrine mt-1 fs-4 me-1"></i>
                                                    Save
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
