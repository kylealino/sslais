<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$member_id = $this->request->getPostGet('member_id');

// Get member data if editing
$member_data = [];
if(!empty($member_id)) {
    $query = $this->db->query("SELECT * FROM tbl_members WHERE member_id = ?", [$member_id]);
    $member_data = $query->getRowArray();
}

// Default values
$member_no = $member_data['member_no'] ?? "";
$first_name = $member_data['first_name'] ?? "";
$last_name = $member_data['last_name'] ?? "";
$middle_name = $member_data['middle_name'] ?? "";
$address = $member_data['address'] ?? "";
$contact_number = $member_data['contact_number'] ?? "";
$email = $member_data['email'] ?? "";
$username = $member_data['username'] ?? "";
$password = $member_data['password'] ?? "";

// New fields from the membership profile update form
$date_of_birth = $member_data['date_of_birth'] ?? "";
$place_of_birth = $member_data['place_of_birth'] ?? "";
$age = $member_data['age'] ?? "";
$civil_status = $member_data['civil_status'] ?? "";
$gender = $member_data['gender'] ?? "";
$tin = $member_data['tin'] ?? "";
$gsis_number = $member_data['gsis_number'] ?? "";

$permanent_street = $member_data['permanent_street'] ?? "";
$permanent_barangay = $member_data['permanent_barangay'] ?? "";
$permanent_city = $member_data['permanent_city'] ?? "";
$permanent_province = $member_data['permanent_province'] ?? "";
$permanent_zip = $member_data['permanent_zip'] ?? "";

$present_street = $member_data['present_street'] ?? "";
$present_barangay = $member_data['present_barangay'] ?? "";
$present_city = $member_data['present_city'] ?? "";
$present_province = $member_data['present_province'] ?? "";
$present_zip = $member_data['present_zip'] ?? "";

$home_phone = $member_data['home_phone'] ?? "";
$office_phone = $member_data['office_phone'] ?? "";

$department_agency = $member_data['department_agency'] ?? "";
$position = $member_data['position'] ?? "";
$salary_grade = $member_data['salary_grade'] ?? "";

$beneficiary1_name = $member_data['beneficiary1_name'] ?? "";
$beneficiary1_address = $member_data['beneficiary1_address'] ?? "";
$beneficiary1_contact = $member_data['beneficiary1_contact'] ?? "";
$beneficiary1_relationship = $member_data['beneficiary1_relationship'] ?? "";
$beneficiary2_name = $member_data['beneficiary2_name'] ?? "";
$beneficiary2_address = $member_data['beneficiary2_address'] ?? "";
$beneficiary2_contact = $member_data['beneficiary2_contact'] ?? "";
$beneficiary2_relationship = $member_data['beneficiary2_relationship'] ?? "";

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

    /* Status Pill */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 30px;
        letter-spacing: 0.3px;
    }

    .status-pill::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-active {
        background: #ecfdf5;
        color: #10b981;
    }
    .status-active::before {
        background: #10b981;
    }

    /* Form Sections */
    .form-section {
        border-bottom: 1px solid var(--gray-200);
        padding-bottom: 20px;
        margin-bottom: 20px;
    }
    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .form-section h6 {
        font-weight: 600;
        margin-bottom: 18px;
        color: var(--navy-dark);
        font-size: 14px;
        letter-spacing: 0.5px;
        border-left: 3px solid var(--gold-primary);
        padding-left: 12px;
    }

    /* Cards */
    .card {
        border: 1px solid var(--gray-200);
        border-radius: 20px;
        background: var(--white-bg);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .card-header {
        background: var(--white-bg);
        border-bottom: 1px solid var(--gray-200);
        padding: 16px 20px;
    }

    .card-body {
        padding: 20px;
    }

    /* Form Controls */
    .form-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 4px;
    }

    .form-control, .form-select {
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--gold-primary);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        outline: none;
    }

    /* Buttons */
    .btn-save {
        background: var(--gold-primary);
        border: none;
        border-radius: 10px;
        padding: 8px 24px;
        font-size: 12px;
        font-weight: 600;
        color: var(--navy-dark);
        transition: all 0.2s;
    }

    .btn-save:hover {
        background: var(--gold-dark);
        transform: translateY(-1px);
        color: white;
    }

    .btn-update {
        background: var(--navy-dark);
        border: none;
        border-radius: 10px;
        padding: 8px 24px;
        font-size: 12px;
        font-weight: 600;
        color: white;
        transition: all 0.2s;
    }

    .btn-update:hover {
        background: var(--navy-medium);
        transform: translateY(-1px);
    }

    .btn-light-custom {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-light-custom:hover {
        background: var(--gold-soft);
        border-color: var(--gold-primary);
        color: var(--gold-dark);
    }

    /* Action Icons */
    .nav-icon-hover {
        transition: all 0.2s;
        display: inline-block;
        font-size: 18px;
    }

    .nav-icon-hover:hover {
        transform: scale(1.1);
        color: var(--gold-primary) !important;
    }

    /* ============================================= */
    /* DATATABLES - PROPER PADDING & STYLING */
    /* ============================================= */
    .dataTables_wrapper {
        font-family: 'Inter', sans-serif;
        padding: 0 10px;
    }
    
    /* Table container */
    .table-responsive {
        overflow-x: auto;
    }
    
    /* Main table styling */
    .table.dataTable {
        width: 100% !important;
        margin: 0 !important;
        border-collapse: collapse;
    }
    
    /* Table header cells */
    .table.dataTable thead th {
        padding: 14px 12px !important;
        font-size: 12px;
        font-weight: 600;
        color: var(--navy-dark);
        border-bottom: 2px solid var(--gold-primary);
        text-align: center;
        background: var(--gray-50);
    }
    
    /* Table body cells */
    .table.dataTable tbody td {
        padding: 12px 12px !important;
        font-size: 13px;
        color: var(--gray-700);
        vertical-align: middle;
        text-align: center;
        border-bottom: 1px solid var(--gray-100);
    }
    
    /* Action column specific - less padding */
    .table.dataTable tbody td:first-child {
        padding: 8px 8px !important;
    }
    
    /* Hover effect */
    .table.dataTable tbody tr:hover td {
        background: var(--gold-soft);
    }
    
    /* Search input styling */
    .dataTables_filter {
        margin-bottom: 20px;
        padding: 0 10px;
    }
    
    .dataTables_filter label {
        font-size: 12px;
        font-weight: 500;
        color: var(--gray-600);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }
    
    .dataTables_filter input {
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        padding: 8px 14px;
        width: 250px;
        font-size: 13px;
        transition: all 0.2s;
    }
    
    .dataTables_filter input:focus {
        border-color: var(--gold-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }
    
    /* Pagination styling */
    .dataTables_paginate {
        margin-top: 20px;
        padding: 0 10px;
    }
    
    .dataTables_paginate .paginate_button {
        padding: 8px 14px !important;
        margin: 0 3px !important;
        border-radius: 8px !important;
        border: 1px solid var(--gray-200) !important;
        background: var(--white-bg) !important;
        color: var(--gray-600) !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .dataTables_paginate .paginate_button.current {
        background: var(--gold-primary) !important;
        border-color: var(--gold-primary) !important;
        color: var(--navy-dark) !important;
    }
    
    .dataTables_paginate .paginate_button:hover {
        background: var(--gold-soft) !important;
        border-color: var(--gold-primary) !important;
        color: var(--gold-dark) !important;
    }
    
    /* Info text */
    .dataTables_info {
        padding: 0 10px;
        margin-top: 20px;
        font-size: 12px;
        color: var(--gray-500);
    }
    
    /* Length menu */
    .dataTables_length {
        padding: 0 10px;
        margin-bottom: 20px;
    }
    
    .dataTables_length label {
        font-size: 12px;
        color: var(--gray-600);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .dataTables_length select {
        border: 1.5px solid var(--gray-200);
        border-radius: 8px;
        padding: 5px 8px;
        margin: 0 5px;
        font-size: 12px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table.dataTable thead th,
        .table.dataTable tbody td {
            padding: 8px 8px !important;
            font-size: 11px;
        }
        
        .dataTables_filter input {
            width: 180px;
        }
        
        .dataTables_paginate .paginate_button {
            padding: 5px 10px !important;
            font-size: 10px !important;
        }
        
        .dataTables_filter label {
            justify-content: center;
        }
        
        .dataTables_length label {
            justify-content: center;
        }
        
        .dataTables_info {
            text-align: center;
        }
        
        .dataTables_paginate {
            text-align: center;
            float: none !important;
        }
        
        .dataTables_info {
            float: none !important;
            margin-bottom: 15px;
        }
    }
    
    /* Loading overlay */
    .preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.9);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
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
</style>

<div class="container-fluid">
    <div class="row me-mymembers-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    
    <div class="row mb-2">
        <div class="col-12">
            <h4 class="fw-semibold my-3">List of Members</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="text-muted text-decoration-none" href="<?=site_url();?>mydashboard">
                            <i class="ti ti-home fs-5"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">Members Management</li>
                    <li class="breadcrumb-item active">List of Members</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <!-- Member Form Card -->
    <div class="card">
        <div class="card-header">
            <h6 class="fw-semibold mb-0">
                <i class="ti ti-user-plus me-2" style="color: var(--gold-primary);"></i>
                <?= empty($member_id) ? 'Add New Member' : 'Edit Member' ?>
            </h6>
        </div>
        <div class="card-body">
            <form class="mymembers-validation" id="memberForm">
                <input type="hidden" id="member_id" name="member_id" value="<?=$member_id;?>"/>
                
                <!-- I. Member Information -->
                <div class="form-section">
                    <h6><i class="ti ti-user me-2"></i> I. Member Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Member No.:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="member_no" name="member_no" value="<?=$member_no;?>" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Last Name:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="last_name" name="last_name" value="<?=$last_name;?>" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">First Name:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="first_name" name="first_name" value="<?=$first_name;?>" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Middle Name:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="middle_name" name="middle_name" value="<?=$middle_name;?>" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Date of Birth:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="date" id="date_of_birth" name="date_of_birth" value="<?=$date_of_birth;?>" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Place of Birth:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="place_of_birth" name="place_of_birth" value="<?=$place_of_birth;?>" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Age:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" id="age" name="age" value="<?=$age;?>" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Civil Status:</label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="civil_status" name="civil_status" class="form-select form-select-sm">
                                        <option value="">Select</option>
                                        <option value="Single" <?= $civil_status == 'Single' ? 'selected' : '' ?>>Single</option>
                                        <option value="Married" <?= $civil_status == 'Married' ? 'selected' : '' ?>>Married</option>
                                        <option value="Widowed" <?= $civil_status == 'Widowed' ? 'selected' : '' ?>>Widowed</option>
                                        <option value="Divorced" <?= $civil_status == 'Divorced' ? 'selected' : '' ?>>Divorced</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Gender:</label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="gender" name="gender" class="form-select form-select-sm">
                                        <option value="">Select</option>
                                        <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                                        <option value="Other" <?= $gender == 'Other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">TIN:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="tin" name="tin" value="<?=$tin;?>" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">GSIS Number:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="gsis_number" name="gsis_number" value="<?=$gsis_number;?>" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- II. Contact Information -->
                <div class="form-section">
                    <h6><i class="ti ti-phone me-2"></i> II. Contact Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Permanent Address:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="permanent_street" placeholder="Street" value="<?=$permanent_street;?>" class="form-control form-control-sm mb-2">
                                    <input type="text" name="permanent_barangay" placeholder="Barangay" value="<?=$permanent_barangay;?>" class="form-control form-control-sm mb-2">
                                    <input type="text" name="permanent_city" placeholder="City/Municipality" value="<?=$permanent_city;?>" class="form-control form-control-sm mb-2">
                                    <div class="row g-2">
                                        <div class="col-7">
                                            <input type="text" name="permanent_province" placeholder="Province" value="<?=$permanent_province;?>" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-5">
                                            <input type="text" name="permanent_zip" placeholder="Zip Code" value="<?=$permanent_zip;?>" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Present Address:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="present_street" placeholder="Street" value="<?=$present_street;?>" class="form-control form-control-sm mb-2">
                                    <input type="text" name="present_barangay" placeholder="Barangay" value="<?=$present_barangay;?>" class="form-control form-control-sm mb-2">
                                    <input type="text" name="present_city" placeholder="City/Municipality" value="<?=$present_city;?>" class="form-control form-control-sm mb-2">
                                    <div class="row g-2">
                                        <div class="col-7">
                                            <input type="text" name="present_province" placeholder="Province" value="<?=$present_province;?>" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-5">
                                            <input type="text" name="present_zip" placeholder="Zip Code" value="<?=$present_zip;?>" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Mobile Number:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="contact_number" name="contact_number" value="<?=$contact_number;?>" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Email Address:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="email" id="email" name="email" value="<?=$email;?>" class="form-control form-control-sm" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Home Phone No.:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="home_phone" value="<?=$home_phone;?>" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Office Phone No.:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="office_phone" value="<?=$office_phone;?>" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- III. Employment Information -->
                <div class="form-section">
                    <h6><i class="ti ti-briefcase me-2"></i> III. Employment Information</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <div class="col-sm-5">
                                    <label class="form-label">Department/Agency:</label>
                                </div>
                                <div class="col-sm-7">
                                    <select name="department_agency" class="form-select form-select-sm">
                                        <option value="">Select</option>
                                        <option value="DOST-FNRI" <?= $department_agency == 'DOST-FNRI' ? 'selected' : '' ?>>DOST-FNRI</option>
                                        <option value="DOST-ITDI" <?= $department_agency == 'DOST-ITDI' ? 'selected' : '' ?>>DOST-ITDI</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <div class="col-sm-5">
                                    <label class="form-label">Position:</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" name="position" value="<?=$position;?>" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <div class="col-sm-5">
                                    <label class="form-label">Salary Grade:</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" name="salary_grade" value="<?=$salary_grade;?>" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- IV. Contact Person(s)/Beneficiaries -->
                <div class="form-section">
                    <h6><i class="ti ti-users me-2"></i> IV. Contact Person(s)/Beneficiaries</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded mb-2">
                                <label class="form-label fw-semibold mb-2">Beneficiary 1</label>
                                <div class="mb-2">
                                    <input type="text" name="beneficiary1_name" placeholder="Full Name" value="<?=$beneficiary1_name;?>" class="form-control form-control-sm mb-2">
                                    <input type="text" name="beneficiary1_address" placeholder="Address" value="<?=$beneficiary1_address;?>" class="form-control form-control-sm mb-2">
                                    <input type="text" name="beneficiary1_contact" placeholder="Contact No." value="<?=$beneficiary1_contact;?>" class="form-control form-control-sm mb-2">
                                    <input type="text" name="beneficiary1_relationship" placeholder="Relationship" value="<?=$beneficiary1_relationship;?>" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded mb-2">
                                <label class="form-label fw-semibold mb-2">Beneficiary 2</label>
                                <div class="mb-2">
                                    <input type="text" name="beneficiary2_name" placeholder="Full Name" value="<?=$beneficiary2_name;?>" class="form-control form-control-sm mb-2">
                                    <input type="text" name="beneficiary2_address" placeholder="Address" value="<?=$beneficiary2_address;?>" class="form-control form-control-sm mb-2">
                                    <input type="text" name="beneficiary2_contact" placeholder="Contact No." value="<?=$beneficiary2_contact;?>" class="form-control form-control-sm mb-2">
                                    <input type="text" name="beneficiary2_relationship" placeholder="Relationship" value="<?=$beneficiary2_relationship;?>" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- V. Login Information -->
                <div class="form-section">
                    <h6><i class="ti ti-lock me-2"></i> V. Login Information</h6>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Username:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="username" name="username" value="<?=$username;?>" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Password:</label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group input-group-sm">
                                        <input type="password" id="password" name="password" value="<?=$password;?>" class="form-control">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="ti ti-eye" id="toggleIcon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-sm-12 text-end">
                        <button type="submit" class="<?= empty($member_id) ? 'btn-save' : 'btn-update' ?>">
                            <i class="ti ti-device-floppy me-1"></i>
                            <?= empty($member_id) ? 'Save Member' : 'Update Member' ?>
                        </button>
                        <a href="<?=site_url();?>mymembers?meaction=MAIN" class="btn btn-light border ms-2" style="border-radius: 10px;">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Member List Card -->
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="fw-semibold mb-0">
                <i class="ti ti-list me-2" style="color: var(--gold-primary);"></i>
                Member List
            </h6>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="80">Action</th>
                            <th>Member No.</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Contact No.</th>
                            <th>Email</th>
                            <th>Loan Count</th>
                            <th>Loan Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($membersdata)):
                            foreach ($membersdata as $data):
                                $mid = $data['member_id'];
                                $member_no = $data['member_no'];
                                $first_name = $data['first_name'];
                                $last_name = $data['last_name'];
                                $contact_number = $data['contact_number'];
                                $email = $data['email'];
                                $loan_count = $data['loan_count'];
                                $loan_amount = $data['loan_amount'];
                        ?>
                        <tr>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="text-primary nav-icon-hover" href="mymembers?meaction=MAIN&member_id=<?= $mid ?>" title="Edit Member">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <button class="btn btn-sm text-warning p-0 border-0 bg-transparent" 
                                            onclick="__mysys_members_ent.__showPdfInModal('<?= base_url('mymembers?meaction=MEMBERS-PRINT&member_id='.$mid) ?>')" 
                                            title="Print Members Profile">
                                        <i class="ti ti-printer"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-center"><?=$member_no;?></td>
                            <td class="text-center"><?=$last_name;?></td>
                            <td class="text-center"><?=$first_name;?></td>
                            <td class="text-center"><?=$contact_number;?></td>
                            <td class="text-center"><?=$email;?></td>
                            <td class="text-center"><?=$loan_count;?></td>
                            <td class="text-center">₱<?=number_format($loan_amount,2);?></td>
                            <td class="text-center"><span class="status-pill status-active">Active</span></td>
                        </tr>
                        <?php endforeach; endif;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- PDF Modal -->
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">Membership Profile Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="pdfFrame" src="" style="width: 100%; height: 80vh;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/members-management/mymembers.js?v=4');?>"></script>

<script>
$(document).ready(function () {
    $('#datatablesSimple').DataTable({
        pageLength: 10,
        lengthChange: true,
        order: [[1, 'asc']],
        language: {
            search: "Search Member:",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries found",
            lengthMenu: "Show _MENU_ entries"
        },
        columnDefs: [
            { className: "text-center", targets: [0, 1, 2, 3, 4, 5, 6, 7, 8] }
        ],
        autoWidth: false,
        responsive: true
    });
});

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

// Auto-calculate age from date of birth
document.getElementById('date_of_birth')?.addEventListener('change', function() {
    const dob = new Date(this.value);
    if (dob) {
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        if (age > 0 && age < 120) {
            document.getElementById('age').value = age;
        }
    }
});
</script>

<?php
echo view('templates/myfooter.php');
?>