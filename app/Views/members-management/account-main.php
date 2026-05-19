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

<style>
    :root {
        --navy-dark: #0a1a3a;
        --navy-medium: #1a2e5a;
        --navy-light: #2a3e6a;
        --gold-primary: #d4af37;
        --gold-dark: #b8960c;
        --gold-light: #f5e6a3;
        --gold-soft: #fef7e0;
        --white-bg: #ffffff;
        --gray-50: #f8f9fa;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
    }

    body {
        background: var(--gray-50);
        font-family: 'Inter', sans-serif;
    }

    /* Main Card */
    .main-card {
        border: 1px solid var(--gray-200);
        border-radius: 20px;
        background: var(--white-bg);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .card-header {
        background: var(--white-bg);
        border-bottom: 1px solid var(--gray-200);
        padding: 20px 24px;
    }

    .card-header h5 {
        font-weight: 700;
        color: var(--navy-dark);
        margin: 0;
        font-size: 18px;
    }

    .card-body {
        padding: 24px;
    }

    /* Section Titles */
    .section-title {
        font-weight: 600;
        color: var(--navy-dark);
        font-size: 14px;
        letter-spacing: 0.5px;
        border-left: 3px solid var(--gold-primary);
        padding-left: 12px;
        margin-bottom: 20px;
    }

    /* Form Controls */
    .form-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--gold-primary);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        outline: none;
    }

    .form-control:disabled, .form-control[readonly] {
        background: var(--gray-50);
        border-color: var(--gray-200);
        color: var(--gray-600);
    }

    /* Profile Image */
    .profile-img-wrapper {
        text-align: center;
        padding: 20px;
        background: var(--gray-50);
        border-radius: 16px;
        margin-bottom: 20px;
    }

    .profile-img {
        border: 3px solid var(--gold-primary);
        padding: 3px;
        background: var(--white-bg);
        border-radius: 50%;
        width: 120px;
        height: 120px;
        object-fit: cover;
    }

    /* Password Input Group */
    .input-group .btn {
        border: 1.5px solid var(--gray-200);
        border-left: none;
        border-radius: 0 10px 10px 0;
    }

    .input-group .form-control {
        border-radius: 10px 0 0 10px;
    }

    /* Buttons */
    .btn-save {
        background: var(--gold-primary);
        border: none;
        border-radius: 10px;
        padding: 10px 28px;
        font-size: 13px;
        font-weight: 600;
        color: var(--navy-dark);
        transition: all 0.2s;
    }

    .btn-save:hover {
        background: var(--gold-dark);
        transform: translateY(-1px);
        color: white;
    }

    .btn-outline {
        background: transparent;
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-outline:hover {
        background: var(--gold-soft);
        border-color: var(--gold-primary);
        color: var(--gold-dark);
    }

    /* Divider */
    .divider {
        height: 1px;
        background: var(--gray-200);
        margin: 20px 0;
    }

    /* Breadcrumb */
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 1rem;
    }

    .breadcrumb-item a {
        text-decoration: none;
        color: var(--gray-500);
        font-size: 12px;
    }

    .breadcrumb-item.active {
        color: var(--gold-dark);
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 18px;
        }
        .btn-save {
            width: 100%;
        }
        .profile-img-wrapper {
            padding: 15px;
        }
    }
</style>

<div class="container-fluid">
    <div class="row me-myaccount-outp-msg mx-0"></div>

    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

    <!-- HEADER -->
    <div class="row mb-2">
        <div class="col-12">
            <h4 class="fw-semibold my-3">Account Settings</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="text-muted text-decoration-none" href="<?=site_url();?>mydashboard">
                            <i class="ti ti-home fs-5"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">Members Management</li>
                    <li class="breadcrumb-item active">Account Settings</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- SINGLE MAIN CARD -->
    <div class="main-card">
        <div class="card-header">
            <h5><i class="ti ti-settings me-2" style="color: var(--gold-primary);"></i> My Profile</h5>
        </div>
        <div class="card-body">
            <form class="myaccount-validation" id="accountForm">
                <input type="hidden" id="member_id" name="member_id" value="<?=$member_id;?>"/>

                <div class="row">
                    <!-- LEFT COLUMN - PROFILE PHOTO -->
                    <div class="col-lg-3">
                        <div class="profile-img-wrapper">
                            <img src="<?=base_url('assets/images/profile/user-1.jpg')?>"
                                class="profile-img mb-3">
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn-outline btn-sm">
                                    <i class="ti ti-upload"></i> Upload
                                </button>
                                <button type="button" class="btn-outline btn-sm text-danger">
                                    Reset
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2">
                                JPG, PNG (Max: 800KB)
                            </small>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN - ALL FORMS -->
                    <div class="col-lg-9">
                        <!-- Security Information Section -->
                        <h6 class="section-title"><i class="ti ti-lock me-2" style="color: var(--gold-primary);"></i> Security Information</h6>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" id="username" name="username"
                                    value="<?=$username;?>" disabled
                                    class="form-control form-control-sm bg-light"/>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Member No.</label>
                                <input type="text" id="member_no" name="member_no"
                                    value="<?=$member_no;?>" disabled
                                    class="form-control form-control-sm bg-light"/>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Password</label>
                                <div class="input-group input-group-sm">
                                    <input type="password" class="form-control" id="password" value="<?=$password;?>">
                                    <button class="btn btn-outline" type="button" id="togglePassword">
                                        <i class="ti ti-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <div class="input-group input-group-sm">
                                    <input type="password" class="form-control" id="newpassword" value="">
                                    <button class="btn btn-outline" type="button" id="newtogglePassword">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Leave blank to keep current password</small>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- Personal Information Section -->
                        <h6 class="section-title"><i class="ti ti-user me-2" style="color: var(--gold-primary);"></i> Personal Information</h6>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" id="first_name" name="first_name"
                                    value="<?=$first_name;?>"
                                    class="form-control form-control-sm"/>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" id="last_name" name="last_name"
                                    value="<?=$last_name;?>"
                                    class="form-control form-control-sm"/>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name"
                                    value="<?=$middle_name;?>"
                                    class="form-control form-control-sm"/>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Number</label>
                                <input type="text" id="contact_number" name="contact_number"
                                    value="<?=$contact_number;?>"
                                    class="form-control form-control-sm"/>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" id="email" name="email"
                                    value="<?=$email;?>"
                                    class="form-control form-control-sm"/>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" id="address" rows="2"
                                    class="form-control form-control-sm"><?=$address;?></textarea>
                            </div>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="text-end mt-4">
                            <button type="submit" class="btn-save">
                                <i class="ti ti-device-floppy me-1"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/members-management/myaccount.js?v=1');?>"></script>

<script>
// Toggle Password Visibility
document.getElementById('togglePassword')?.addEventListener('click', function () {
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

document.getElementById('newtogglePassword')?.addEventListener('click', function () {
    const input = document.getElementById('newpassword');
    const icon = this.querySelector('i');

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