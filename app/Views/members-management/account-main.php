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
$profile_photo_path = "";
$profile_photo_name = "";

if(!empty($member_data)) { 
    $member_id = $member_data['member_id'];
    $member_no = $member_data['member_no'];
    $first_name = $member_data['first_name'];
    $last_name = $member_data['last_name'];
    $middle_name = $member_data['middle_name'];
    $address = $member_data['address'];
    $contact_number = $member_data['contact_number'];
    $email = $member_data['email'];
    $username = $member_data['username'];
    $password = $member_data['password'];
    $profile_photo_path = $member_data['profile_photo_path'] ?? '';
    $profile_photo_name = $member_data['profile_photo_name'] ?? '';
}

// Set default or uploaded profile photo
$profile_photo_url = !empty($profile_photo_path) ? base_url($profile_photo_path) : base_url('assets/images/profile/user-1.jpg');

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
        position: relative;
    }

    .profile-img {
        border: 3px solid var(--gold-primary);
        padding: 3px;
        background: var(--white-bg);
        border-radius: 50%;
        width: 140px;
        height: 140px;
        object-fit: cover;
        cursor: pointer;
        transition: all 0.2s;
    }

    .profile-img:hover {
        opacity: 0.8;
        transform: scale(1.02);
    }

    .photo-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 15px;
    }

    .photo-actions button {
        font-size: 11px;
        padding: 5px 12px;
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
    
    .btn-outline-danger {
        background: transparent;
        border: 1.5px solid var(--danger);
        border-radius: 10px;
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 500;
        color: var(--danger);
        transition: all 0.2s;
    }
    
    .btn-outline-danger:hover {
        background: #fee2e2;
        border-color: var(--danger);
        color: #991b1b;
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
    
    /* Hidden file input */
    #profilePhotoInput {
        display: none;
    }
    
    /* Upload preview overlay */
    .upload-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
    }
    
    .upload-overlay.active {
        display: flex;
    }
    
    .upload-spinner {
        background: white;
        padding: 20px 30px;
        border-radius: 16px;
        text-align: center;
    }
    
    .upload-spinner i {
        font-size: 30px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
        .profile-img {
            width: 100px;
            height: 100px;
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
                            <img src="<?=$profile_photo_url?>"
                                class="profile-img mb-3"
                                id="profilePhotoPreview"
                                onclick="document.getElementById('profilePhotoInput').click();"
                                title="Click to change photo">
                            <input type="file" id="profilePhotoInput" accept="image/jpeg,image/jpg,image/png,image/gif">
                            <div class="photo-actions">
                                <button type="button" class="btn-outline btn-sm" id="uploadPhotoBtn">
                                    <i class="ti ti-upload"></i> Upload
                                </button>
                                <?php if(!empty($profile_photo_path)): ?>
                                <button type="button" class="btn-outline-danger btn-sm" id="resetPhotoBtn">
                                    <i class="ti ti-trash"></i> Reset
                                </button>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted d-block mt-2">
                                JPG, PNG (Max: 800KB)<br>
                                Click image to change
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

<!-- Loading Overlay -->
<div class="upload-overlay" id="uploadOverlay">
    <div class="upload-spinner">
        <i class="ti ti-loader"></i>
        <p class="mt-2 mb-0">Uploading...</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/members-management/myaccount.js?v=2');?>"></script>

<script>
// Toggle Password Visibility for current password
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

// Toggle Password Visibility for new password
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

// Profile photo upload handling
const mesiteurl = $('#__siteurl').attr('data-mesiteurl');
const member_id = $('#member_id').val();

$('#uploadPhotoBtn').on('click', function() {
    $('#profilePhotoInput').click();
});

$('#profilePhotoInput').on('change', function(e) {
    if (this.files && this.files[0]) {
        const file = this.files[0];
        const fileType = file.type;
        const fileSize = file.size;
        
        // Validate file type
        if (!fileType.match('image/jpeg') && !fileType.match('image/jpg') && !fileType.match('image/png') && !fileType.match('image/gif')) {
            toastr.error('Only JPG, JPEG, PNG, and GIF files are allowed!');
            return;
        }
        
        // Validate file size (800KB = 819200 bytes)
        if (fileSize > 800 * 1024) {
            toastr.error('File size must be less than 800KB!');
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#profilePhotoPreview').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
        
        // Upload the file
        uploadProfilePhoto(file);
    }
});

function uploadProfilePhoto(file) {
    const formData = new FormData();
    formData.append('member_id', member_id);
    formData.append('profile_photo', file);
    formData.append('meaction', 'UPLOAD-PROFILE-PHOTO');
    
    $('#uploadOverlay').addClass('active');
    
    $.ajax({
        type: "POST",
        url: mesiteurl + 'myaccount',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            $('#uploadOverlay').removeClass('active');
            if (response.status == 'success') {
                toastr.success(response.message);
                // Update the reset button if it doesn't exist
                if ($('#resetPhotoBtn').length === 0) {
                    $('.photo-actions').append('<button type="button" class="btn-outline-danger btn-sm" id="resetPhotoBtn"><i class="ti ti-trash"></i> Reset</button>');
                    bindResetButton();
                }
            } else {
                toastr.error(response.message);
                // Reload original image
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            $('#uploadOverlay').removeClass('active');
            toastr.error("Error uploading photo: " + error);
        }
    });
}

function bindResetButton() {
    $('#resetPhotoBtn').off('click').on('click', function() {
        if (confirm('Are you sure you want to reset your profile photo?')) {
            resetProfilePhoto();
        }
    });
}

function resetProfilePhoto() {
    const formData = new FormData();
    formData.append('member_id', member_id);
    formData.append('meaction', 'RESET-PROFILE-PHOTO');
    
    $('#uploadOverlay').addClass('active');
    
    $.ajax({
        type: "POST",
        url: mesiteurl + 'myaccount',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            $('#uploadOverlay').removeClass('active');
            if (response.status == 'success') {
                toastr.success(response.message);
                // Reset to default image
                $('#profilePhotoPreview').attr('src', mesiteurl + 'assets/images/profile/user-1.jpg');
                $('#resetPhotoBtn').remove();
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr, status, error) {
            $('#uploadOverlay').removeClass('active');
            toastr.error("Error resetting photo: " + error);
        }
    });
}

// Bind reset button if exists
<?php if(!empty($profile_photo_path)): ?>
bindResetButton();
<?php endif; ?>
</script>

<?php
echo view('templates/myfooter.php');
?>