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

// Get documents for this member
$doc_by_type = [];
if(!empty($member_id)) {
    $doc_query = $this->db->query("SELECT * FROM tbl_member_documents WHERE member_id = ? AND status = 'active'", [$member_id]);
    $member_documents = $doc_query->getResultArray();
    foreach($member_documents as $doc) {
        $doc_by_type[$doc['document_type']] = $doc;
    }
}

function format_file_size($bytes) {
    if ($bytes >= 1048576) {
        return round($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return round($bytes / 1024, 2) . ' KB';
    }
    return $bytes . ' bytes';
}

function get_file_icon($file_path) {
    $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    switch($extension) {
        case 'pdf': return 'ti-file-pdf';
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'webp': return 'ti-photo';
        case 'doc':
        case 'docx': return 'ti-file-description';
        case 'xls':
        case 'xlsx': return 'ti-chart-bar';
        default: return 'ti-file';
    }
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

    body { background: var(--gray-50); font-family: 'Inter', sans-serif; }

    .status-pill { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; font-size: 11px; font-weight: 600; border-radius: 30px; letter-spacing: 0.3px; }
    .status-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
    .status-active { background: #ecfdf5; color: #10b981; }
    .status-active::before { background: #10b981; }
    .status-pending { background: #fef3c7; color: #d97706; }
    .status-pending::before { background: #d97706; }
    .status-default { background: #fee2e2; color: #dc2626; }
    .status-default::before { background: #dc2626; }
    .status-paid { background: #d1fae5; color: #065f46; }
    .status-paid::before { background: #065f46; }

    .tab-pane .form-section { border-bottom: 1px solid var(--gray-200); padding-bottom: 20px; margin-bottom: 20px; }
    .tab-pane .form-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .tab-pane .form-section h6 { font-weight: 600; margin-bottom: 18px; color: var(--navy-dark); font-size: 14px; letter-spacing: 0.5px; border-left: 3px solid var(--gold-primary); padding-left: 12px; }

    .documents-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 20px; margin-top: 15px; }
    .document-card { background: var(--white-bg); border: 1px solid var(--gray-200); border-radius: 16px; padding: 16px; transition: all 0.2s ease; position: relative; }
    .document-card:hover { border-color: var(--gold-primary); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .document-card .doc-icon { width: 40px; height: 40px; background: var(--gold-soft); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
    .document-card .doc-icon i { font-size: 20px; color: var(--gold-primary); }
    .document-card .doc-title { font-weight: 600; font-size: 14px; color: var(--navy-dark); margin-bottom: 4px; }
    .document-card .doc-purpose { font-size: 11px; color: var(--gray-500); margin-bottom: 12px; line-height: 1.4; }
    .document-card .doc-badge { display: inline-block; background: var(--gray-100); font-size: 9px; padding: 2px 8px; border-radius: 30px; font-weight: 600; color: var(--gray-600); margin-bottom: 12px; }
    .document-card .doc-badge.required { background: #fee2e2; color: var(--danger); }
    .document-card .doc-badge.optional { background: #e0f2fe; color: var(--info); }
    .document-card .existing-file { font-size: 11px; background: var(--gray-50); padding: 8px 10px; border-radius: 10px; margin-top: 10px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .document-card .existing-file i { color: var(--success); font-size: 14px; }
    .document-card .existing-file a { color: var(--info); text-decoration: none; font-weight: 500; cursor: pointer; }
    .document-card .existing-file a:hover { text-decoration: underline; color: var(--gold-dark); }
    .document-card .existing-file .view-link { background: var(--info); color: white; padding: 2px 8px; border-radius: 6px; font-size: 10px; margin-left: 8px; text-decoration: none; }
    .document-card .existing-file .view-link:hover { background: var(--gold-primary); color: var(--navy-dark); }
    .document-card .existing-file .file-info { font-size: 10px; color: var(--gray-500); margin-top: 2px; width: 100%; }
    .document-card input[type="file"] { font-size: 11px; padding: 6px 0; }
    .document-card input[type="file"]::file-selector-button { background: var(--gray-100); border: 1px solid var(--gray-200); border-radius: 8px; padding: 5px 12px; font-size: 11px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
    .document-card input[type="file"]::file-selector-button:hover { background: var(--gold-soft); border-color: var(--gold-primary); }

    .card { border: 1px solid var(--gray-200); border-radius: 20px; background: var(--white-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .card-header { background: var(--white-bg); border-bottom: 1px solid var(--gray-200); padding: 16px 20px; }
    .card-body { padding: 20px; }

    .form-label { font-size: 11px; font-weight: 600; color: var(--gray-600); text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 4px; }
    .form-control, .form-select { border: 1.5px solid var(--gray-200); border-radius: 10px; padding: 8px 12px; font-size: 13px; transition: all 0.2s; }
    .form-control:focus, .form-select:focus { border-color: var(--gold-primary); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); outline: none; }

    .btn-save { background: var(--gold-primary); border: none; border-radius: 10px; padding: 8px 24px; font-size: 12px; font-weight: 600; color: var(--navy-dark); transition: all 0.2s; }
    .btn-save:hover { background: var(--gold-dark); transform: translateY(-1px); color: white; }
    .btn-update { background: var(--navy-dark); border: none; border-radius: 10px; padding: 8px 24px; font-size: 12px; font-weight: 600; color: white; transition: all 0.2s; }
    .btn-update:hover { background: var(--navy-medium); transform: translateY(-1px); }
    .btn-document-upload { background: var(--success); border: none; border-radius: 10px; padding: 8px 24px; font-size: 12px; font-weight: 600; color: white; transition: all 0.2s; }
    .btn-document-upload:hover { background: #0d9488; transform: translateY(-1px); }
    .btn-light-custom { background: var(--gray-50); border: 1px solid var(--gray-200); border-radius: 10px; padding: 8px 16px; font-size: 12px; font-weight: 500; transition: all 0.2s; }
    .btn-light-custom:hover { background: var(--gold-soft); border-color: var(--gold-primary); color: var(--gold-dark); }

    .capital-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .capital-stat-card { background: var(--white-bg); border-radius: 16px; padding: 1rem 1.25rem; border: 1px solid var(--gray-200); transition: all 0.2s ease; }
    .capital-stat-card:hover { border-color: var(--gold-primary); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .capital-stat-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--gray-500); margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
    .capital-stat-value { font-size: 24px; font-weight: 700; color: var(--navy-dark); line-height: 1.2; }
    .capital-stat-sub { font-size: 10px; color: var(--gray-400); margin-top: 6px; }

    .transaction-table-wrapper { overflow-x: auto; margin-top: 1rem; }
    .transaction-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .transaction-table th { text-align: left; padding: 12px 10px; background: var(--gray-50); border-bottom: 2px solid var(--gold-primary); font-weight: 600; color: var(--navy-dark); }
    .transaction-table td { padding: 10px; border-bottom: 1px solid var(--gray-200); color: var(--gray-600); }
    .transaction-table tr:hover td { background: var(--gold-soft); }

    .badge-contribution { background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 20px; font-size: 10px; font-weight: 600; }
    .badge-deposit { background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 20px; font-size: 10px; font-weight: 600; }
    .badge-withdrawal { background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 20px; font-size: 10px; font-weight: 600; }

    .btn-capital { background: var(--navy-dark); border: none; border-radius: 10px; padding: 8px 20px; font-size: 12px; font-weight: 500; color: white; transition: all 0.2s; }
    .btn-capital:hover { background: var(--navy-medium); transform: translateY(-1px); }
    .btn-capital-primary { background: var(--gold-primary); color: var(--navy-dark); }
    .btn-capital-primary:hover { background: var(--gold-dark); color: white; }

    .co-maker-card { background: var(--gray-50); border-radius: 12px; padding: 12px; margin-bottom: 10px; border-left: 3px solid var(--gold-primary); }
    .member-search-dropdown { position: absolute; z-index: 1000; background: white; border: 1px solid var(--gray-200); border-radius: 12px; max-height: 300px; overflow-y: auto; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; }
    .search-result-item { padding: 10px 15px; cursor: pointer; border-bottom: 1px solid var(--gray-100); }
    .search-result-item:hover { background: var(--gold-soft); }

    .timeline-item { display: flex; gap: 15px; padding: 15px 0; border-bottom: 1px solid var(--gray-200); }
    .timeline-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .timeline-icon.contribution { background: #dbeafe; color: #1e40af; }
    .timeline-icon.deposit { background: #d1fae5; color: #065f46; }
    .timeline-icon.withdrawal { background: #fee2e2; color: #991b1b; }
    .timeline-icon.loan { background: #fef3c7; color: #d97706; }
    .timeline-icon.payment { background: #e0e7ff; color: #3730a3; }
    .timeline-content { flex: 1; }
    .timeline-title { font-weight: 600; font-size: 13px; color: var(--navy-dark); }
    .timeline-date { font-size: 10px; color: var(--gray-500); margin-top: 2px; }
    .timeline-amount { font-weight: 700; font-size: 14px; }
    .timeline-reference { font-size: 10px; color: var(--gray-500); margin-top: 4px; }
    
    .filter-buttons .btn-filter { background: var(--gray-100); border: 1px solid var(--gray-200); border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 500; transition: all 0.2s; cursor: pointer; }
    .filter-buttons .btn-filter.active { background: var(--gold-primary); border-color: var(--gold-primary); color: var(--navy-dark); }
    .filter-buttons .btn-filter:hover { background: var(--gold-soft); border-color: var(--gold-primary); }

    .nav-icon-hover { transition: all 0.2s; display: inline-block; font-size: 18px; }
    .nav-icon-hover:hover { transform: scale(1.1); color: var(--gold-primary) !important; }

    .nav-tabs { border-bottom: 2px solid var(--gray-200); padding: 0 20px; background: var(--white-bg); border-radius: 20px 20px 0 0; flex-wrap: wrap; }
    .nav-tabs .nav-link { border: none; color: var(--gray-600); font-weight: 500; font-size: 13px; padding: 12px 20px; margin-right: 5px; transition: all 0.2s; position: relative; }
    .nav-tabs .nav-link i { margin-right: 8px; font-size: 16px; }
    .nav-tabs .nav-link:hover { color: var(--gold-primary); background: transparent; border: none; }
    .nav-tabs .nav-link.active { color: var(--gold-primary); background: transparent; border: none; font-weight: 600; }
    .nav-tabs .nav-link.active::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 100%; height: 2px; background: var(--gold-primary); }
    .tab-content { padding: 25px 20px; background: var(--white-bg); border-radius: 0 0 20px 20px; }
    
    .info-alert { background: var(--gold-soft); border-left: 4px solid var(--gold-primary); padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; }
    .info-alert i { color: var(--gold-primary); margin-right: 10px; }
    .info-alert span { font-size: 12px; color: var(--gray-700); }

    .breadcrumb { background: transparent; padding: 0; margin-bottom: 1rem; }
    .breadcrumb-item a { text-decoration: none; color: var(--gray-500); font-size: 12px; }
    .breadcrumb-item.active { color: var(--gold-dark); font-weight: 600; }

    .member-avatar { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; transition: all 0.2s ease; }
    .member-avatar.has-photo { border: 2px solid var(--gold-primary); overflow: hidden; padding: 0; }
    .member-avatar.has-photo img { width: 100%; height: 100%; object-fit: cover; }
    .member-avatar.default { background: var(--gold-soft); color: var(--gold-dark); font-size: 16px; border: 2px solid var(--gold-primary); }
    .member-avatar.default i { font-size: 18px; }
    .member-avatar:hover { transform: scale(1.05); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    
    .guarantor-network { background: var(--white-bg); border-radius: 12px; padding: 15px; margin-bottom: 15px; border: 1px solid var(--gray-200); }
    .guarantor-node { display: inline-block; background: var(--gold-soft); border-radius: 30px; padding: 5px 12px; margin: 3px; font-size: 11px; font-weight: 500; }
    .liability-summary { background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%); border-radius: 12px; padding: 12px 15px; margin-bottom: 15px; }

    /* Document viewer modal */
    .document-viewer-modal .modal-dialog { max-width: 90%; width: 90%; margin: 1.75rem auto; }
    .document-viewer-modal .modal-content { border-radius: 20px; }
    .document-viewer-modal .modal-body { padding: 0; min-height: 80vh; }
    .document-viewer-modal .document-frame { width: 100%; min-height: 80vh; border: none; }
    .doc-action-buttons { display: flex; gap: 10px; margin-top: 10px; }
    .doc-action-buttons .btn-sm { font-size: 11px; padding: 4px 10px; }

    @media (max-width: 768px) {
        .nav-tabs .nav-link { padding: 8px 12px; font-size: 11px; }
        .nav-tabs .nav-link i { font-size: 12px; }
        .documents-grid, .capital-stats-grid { grid-template-columns: 1fr; }
        .document-viewer-modal .modal-dialog { max-width: 95%; width: 95%; }
    }
</style>

<div class="pe-3 ps-3">
    <div class="row me-mymembers-outp-msg mx-0"></div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    
    <div class="row mb-2">
        <div class="col-12">
            <h4 class="fw-semibold">List of Members</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="<?=site_url();?>mydashboard"><i class="ti ti-home fs-5"></i></a></li>
                    <li class="breadcrumb-item">Members Management</li>
                    <li class="breadcrumb-item active">List of Members</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h6 class="fw-semibold mb-0">
                <i class="ti ti-user-plus me-2" style="color: var(--gold-primary);"></i>
                <?= empty($member_id) ? 'Add New Member' : 'Edit Member Information' ?>
            </h6>
        </div>
        <div class="card-body p-0">
            <form class="mymembers-validation" id="memberForm">
                <input type="hidden" id="member_id" name="member_id" value="<?=$member_id;?>"/>
                
                <ul class="nav nav-tabs" id="memberTabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active" id="basic-info-tab" data-bs-toggle="tab" data-bs-target="#basic-info" type="button"><i class="ti ti-user"></i> Basic Information</button></li>
                    <li class="nav-item"><button class="nav-link" id="contact-info-tab" data-bs-toggle="tab" data-bs-target="#contact-info" type="button"><i class="ti ti-phone"></i> Contact</button></li>
                    <li class="nav-item"><button class="nav-link" id="employment-tab" data-bs-toggle="tab" data-bs-target="#employment" type="button"><i class="ti ti-briefcase"></i> Employment</button></li>
                    <li class="nav-item"><button class="nav-link" id="beneficiaries-tab" data-bs-toggle="tab" data-bs-target="#beneficiaries" type="button"><i class="ti ti-users"></i> Beneficiaries</button></li>
                    <li class="nav-item"><button class="nav-link" id="login-info-tab" data-bs-toggle="tab" data-bs-target="#login-info" type="button"><i class="ti ti-lock"></i> Login</button></li>
                    <?php if(!empty($member_id)): ?>
                    <li class="nav-item"><button class="nav-link" id="capital-tab" data-bs-toggle="tab" data-bs-target="#capital-management" type="button"><i class="ti ti-wallet"></i> Capital / Shares</button></li>
                    <li class="nav-item"><button class="nav-link" id="comaker-tab" data-bs-toggle="tab" data-bs-target="#comaker-management" type="button"><i class="ti ti-handshake"></i> Co-Maker / Guarantor</button></li>
                    <li class="nav-item"><button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#account-history" type="button"><i class="ti ti-history"></i> Account History</button></li>
                    <?php endif; ?>
                </ul>
                
                <div class="tab-content" id="memberTabsContent">
                    <!-- Basic Information Tab -->
                    <div class="tab-pane fade show active" id="basic-info">
                        <div class="form-section"><h6><i class="ti ti-id me-2"></i> Personal Details</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3"><div class="col-sm-4"><label class="form-label">Member No.:</label></div><div class="col-sm-8"><input type="text" id="member_no" name="member_no" value="<?=$member_no;?>" class="form-control form-control-sm"></div></div>
                                    <div class="row mb-3"><div class="col-sm-4"><label class="form-label">Last Name:</label></div><div class="col-sm-8"><input type="text" id="last_name" name="last_name" value="<?=$last_name;?>" class="form-control form-control-sm"></div></div>
                                    <div class="row mb-3"><div class="col-sm-4"><label class="form-label">First Name:</label></div><div class="col-sm-8"><input type="text" id="first_name" name="first_name" value="<?=$first_name;?>" class="form-control form-control-sm"></div></div>
                                    <div class="row mb-3"><div class="col-sm-4"><label class="form-label">Middle Name:</label></div><div class="col-sm-8"><input type="text" id="middle_name" name="middle_name" value="<?=$middle_name;?>" class="form-control form-control-sm"></div></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3"><div class="col-sm-4"><label class="form-label">Date of Birth:</label></div><div class="col-sm-8"><input type="date" id="date_of_birth" name="date_of_birth" value="<?=$date_of_birth;?>" class="form-control form-control-sm"></div></div>
                                    <div class="row mb-3"><div class="col-sm-4"><label class="form-label">Place of Birth:</label></div><div class="col-sm-8"><input type="text" id="place_of_birth" name="place_of_birth" value="<?=$place_of_birth;?>" class="form-control form-control-sm"></div></div>
                                    <div class="row mb-3"><div class="col-sm-4"><label class="form-label">Age:</label></div><div class="col-sm-8"><input type="number" id="age" name="age" value="<?=$age;?>" class="form-control form-control-sm" readonly></div></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-section"><h6><i class="ti ti-info-circle me-2"></i> Demographic Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3"><div class="col-sm-4"><label class="form-label">Civil Status:</label></div><div class="col-sm-8"><select id="civil_status" name="civil_status" class="form-select form-select-sm"><option>Single</option><option>Married</option><option>Widowed</option></select></div></div>
                                    <div class="row mb-3"><div class="col-sm-4"><label class="form-label">Gender:</label></div><div class="col-sm-8"><select id="gender" name="gender" class="form-select form-select-sm"><option>Male</option><option>Female</option></select></div></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3"><div class="col-sm-4"><label class="form-label">TIN:</label></div><div class="col-sm-8"><input type="text" id="tin" name="tin" value="<?=$tin;?>" class="form-control form-control-sm"></div></div>
                                    <div class="row mb-3"><div class="col-sm-4"><label class="form-label">GSIS Number:</label></div><div class="col-sm-8"><input type="text" id="gsis_number" name="gsis_number" value="<?=$gsis_number;?>" class="form-control form-control-sm"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Information Tab -->
                    <div class="tab-pane fade" id="contact-info">
                        <div class="form-section"><h6><i class="ti ti-home me-2"></i> Permanent Address</h6>
                            <div class="row"><div class="col-md-12">
                                <div class="row mb-3"><div class="col-sm-2"><label class="form-label">Street:</label></div><div class="col-sm-10"><input type="text" name="permanent_street" value="<?=$permanent_street;?>" class="form-control form-control-sm"></div></div>
                                <div class="row mb-3"><div class="col-sm-2"><label class="form-label">Barangay:</label></div><div class="col-sm-10"><input type="text" name="permanent_barangay" value="<?=$permanent_barangay;?>" class="form-control form-control-sm"></div></div>
                                <div class="row mb-3"><div class="col-sm-2"><label class="form-label">City:</label></div><div class="col-sm-10"><input type="text" name="permanent_city" value="<?=$permanent_city;?>" class="form-control form-control-sm"></div></div>
                                <div class="row mb-3"><div class="col-sm-2"><label class="form-label">Province:</label></div><div class="col-sm-5"><input type="text" name="permanent_province" value="<?=$permanent_province;?>" class="form-control form-control-sm"></div><div class="col-sm-1"><label class="form-label">Zip:</label></div><div class="col-sm-4"><input type="text" name="permanent_zip" value="<?=$permanent_zip;?>" class="form-control form-control-sm"></div></div>
                            </div></div>
                        </div>
                        <div class="form-section"><h6><i class="ti ti-map-pin me-2"></i> Present Address</h6>
                            <div class="row"><div class="col-md-12">
                                <div class="row mb-3"><div class="col-sm-2"><label class="form-label">Street:</label></div><div class="col-sm-10"><input type="text" name="present_street" value="<?=$present_street;?>" class="form-control form-control-sm"></div></div>
                                <div class="row mb-3"><div class="col-sm-2"><label class="form-label">Barangay:</label></div><div class="col-sm-10"><input type="text" name="present_barangay" value="<?=$present_barangay;?>" class="form-control form-control-sm"></div></div>
                                <div class="row mb-3"><div class="col-sm-2"><label class="form-label">City:</label></div><div class="col-sm-10"><input type="text" name="present_city" value="<?=$present_city;?>" class="form-control form-control-sm"></div></div>
                                <div class="row mb-3"><div class="col-sm-2"><label class="form-label">Province:</label></div><div class="col-sm-5"><input type="text" name="present_province" value="<?=$present_province;?>" class="form-control form-control-sm"></div><div class="col-sm-1"><label class="form-label">Zip:</label></div><div class="col-sm-4"><input type="text" name="present_zip" value="<?=$present_zip;?>" class="form-control form-control-sm"></div></div>
                            </div></div>
                        </div>
                        <div class="form-section"><h6><i class="ti ti-device-mobile me-2"></i> Phone & Email</h6>
                            <div class="row">
                                <div class="col-md-6"><div class="row mb-3"><div class="col-sm-4"><label class="form-label">Mobile No.:</label></div><div class="col-sm-8"><input type="text" id="contact_number" name="contact_number" value="<?=$contact_number;?>" class="form-control form-control-sm"></div></div>
                                <div class="row mb-3"><div class="col-sm-4"><label class="form-label">Home Phone:</label></div><div class="col-sm-8"><input type="text" name="home_phone" value="<?=$home_phone;?>" class="form-control form-control-sm"></div></div></div>
                                <div class="col-md-6"><div class="row mb-3"><div class="col-sm-4"><label class="form-label">Email:</label></div><div class="col-sm-8"><input type="email" id="email" name="email" value="<?=$email;?>" class="form-control form-control-sm"></div></div>
                                <div class="row mb-3"><div class="col-sm-4"><label class="form-label">Office Phone:</label></div><div class="col-sm-8"><input type="text" name="office_phone" value="<?=$office_phone;?>" class="form-control form-control-sm"></div></div></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Employment Tab -->
                    <div class="tab-pane fade" id="employment">
                        <div class="form-section"><h6><i class="ti ti-building me-2"></i> Work Details</h6>
                            <div class="row">
                                <div class="col-md-6"><div class="row mb-3"><div class="col-sm-4"><label class="form-label">Department/Agency:</label></div><div class="col-sm-8"><select name="department_agency" class="form-select form-select-sm"><option>DOST-FNRI</option><option>DOST-ITDI</option></select></div></div></div>
                                <div class="col-md-6"><div class="row mb-3"><div class="col-sm-4"><label class="form-label">Position:</label></div><div class="col-sm-8"><input type="text" name="position" value="<?=$position;?>" class="form-control form-control-sm"></div></div></div>
                                <div class="col-md-6"><div class="row mb-3"><div class="col-sm-4"><label class="form-label">Salary Grade:</label></div><div class="col-sm-8"><input type="text" name="salary_grade" value="<?=$salary_grade;?>" class="form-control form-control-sm"></div></div></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Beneficiaries Tab -->
                    <div class="tab-pane fade" id="beneficiaries">
                        <div class="row">
                            <div class="col-md-6"><div class="form-section"><h6><i class="ti ti-star me-2"></i> Primary Beneficiary</h6>
                                <input type="text" name="beneficiary1_name" class="form-control form-control-sm mb-2" placeholder="Full Name" value="<?=$beneficiary1_name;?>">
                                <input type="text" name="beneficiary1_address" class="form-control form-control-sm mb-2" placeholder="Address" value="<?=$beneficiary1_address;?>">
                                <input type="text" name="beneficiary1_contact" class="form-control form-control-sm mb-2" placeholder="Contact" value="<?=$beneficiary1_contact;?>">
                                <input type="text" name="beneficiary1_relationship" class="form-control form-control-sm" placeholder="Relationship" value="<?=$beneficiary1_relationship;?>">
                            </div></div>
                            <div class="col-md-6"><div class="form-section"><h6><i class="ti ti-star me-2"></i> Secondary Beneficiary</h6>
                                <input type="text" name="beneficiary2_name" class="form-control form-control-sm mb-2" placeholder="Full Name" value="<?=$beneficiary2_name;?>">
                                <input type="text" name="beneficiary2_address" class="form-control form-control-sm mb-2" placeholder="Address" value="<?=$beneficiary2_address;?>">
                                <input type="text" name="beneficiary2_contact" class="form-control form-control-sm mb-2" placeholder="Contact" value="<?=$beneficiary2_contact;?>">
                                <input type="text" name="beneficiary2_relationship" class="form-control form-control-sm" placeholder="Relationship" value="<?=$beneficiary2_relationship;?>">
                            </div></div>
                        </div>
                    </div>
                    
                    <!-- Login Tab -->
                    <div class="tab-pane fade" id="login-info">
                        <div class="form-section"><h6><i class="ti ti-key me-2"></i> Account Credentials</h6>
                            <div class="row">
                                <div class="col-md-6"><div class="row mb-3"><div class="col-sm-4"><label class="form-label">Username:</label></div><div class="col-sm-8"><input type="text" id="username" name="username" value="<?=$username;?>" class="form-control form-control-sm"></div></div></div>
                                <div class="col-md-6"><div class="row mb-3"><div class="col-sm-4"><label class="form-label">Password:</label></div><div class="col-sm-8"><div class="input-group input-group-sm"><input type="password" id="password" name="password" value="<?=$password;?>" class="form-control"><button class="btn btn-outline-secondary" type="button" id="togglePassword"><i class="ti ti-eye" id="toggleIcon"></i></button></div></div></div></div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(!empty($member_id)): ?>
                    <!-- CAPITAL MANAGEMENT TAB - FULL MONITORING WITH ALL TRANSACTION TYPES -->
                    <div class="tab-pane fade" id="capital-management">
                        <div class="capital-stats-grid">
                            <div class="capital-stat-card">
                                <div class="capital-stat-label"><i class="ti ti-coins" style="color: var(--gold-primary);"></i> Share Capital</div>
                                <div class="capital-stat-value">₱ <span id="total_contributions">12,500.00</span></div>
                                <div class="capital-stat-sub">Total capital contributions</div>
                            </div>
                            <div class="capital-stat-card">
                                <div class="capital-stat-label"><i class="ti ti-pig-money" style="color: var(--success);"></i> Savings Deposits</div>
                                <div class="capital-stat-value">₱ <span id="total_deposits">6,500.00</span></div>
                                <div class="capital-stat-sub">Total member deposits</div>
                            </div>
                            <div class="capital-stat-card">
                                <div class="capital-stat-label"><i class="ti ti-arrow-back-up" style="color: var(--danger);"></i> Withdrawals</div>
                                <div class="capital-stat-value">₱ <span id="total_withdrawals">2,000.00</span></div>
                                <div class="capital-stat-sub">Total withdrawn amount</div>
                            </div>
                            <div class="capital-stat-card">
                                <div class="capital-stat-label"><i class="ti ti-chart-pie" style="color: var(--info);"></i> Net Share Balance</div>
                                <div class="capital-stat-value">₱ <span id="share_balance">17,000.00</span></div>
                                <div class="capital-stat-sub">Capital + Deposits - Withdrawals</div>
                            </div>
                        </div>
                        
                        <h6 class="fw-semibold mb-3"><i class="ti ti-list me-2" style="color: var(--gold-primary);"></i> Transaction History</h6>
                        <div class="transaction-table-wrapper">
                            <table class="transaction-table" id="transactionsTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Reference No.</th>
                                        <th>Notes</th>
                                        <th width="60">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="transactionsList">
                                    <tr>
                                        <td>2025-01-15</span></td>
                                        <td><span class="badge-contribution">Capital Contribution</span></span></td>
                                        <td>₱ 5,000.00</span></span></td>
                                        <td>CAP-001</span></span></td>
                                        <td>Initial share capital</span></span></td>
                                        <td><i class="ti ti-trash text-danger" style="cursor:pointer" onclick="deleteTransaction(1)"></i></span></span></td>
                                    </tr>
                                    <tr>
                                        <td>2025-02-10</span></td>
                                        <td><span class="badge-deposit">Savings Deposit</span></span></span></td>
                                        <td>₱ 2,000.00</span></span></span></td>
                                        <td>DEP-001</span></span></span></td>
                                        <td>Monthly savings deposit</span></span></span></td>
                                        <td><i class="ti ti-trash text-danger" style="cursor:pointer" onclick="deleteTransaction(2)"></i></span></span></span></td>
                                    </tr>
                                    <tr>
                                        <td>2025-03-05</span></td>
                                        <td><span class="badge-contribution">Capital Contribution</span></span></span></td>
                                        <td>₱ 3,000.00</span></span></td>
                                        <td>CAP-002</span></span></td>
                                        <td>Additional capital contribution</span></span></td>
                                        <td><i class="ti ti-trash text-danger" style="cursor:pointer" onclick="deleteTransaction(3)"></i></span></span></td>
                                    </tr>
                                    <tr>
                                        <td>2025-04-20</span></td>
                                        <td><span class="badge-deposit">Savings Deposit</span></span></span></td>
                                        <td>₱ 1,500.00</span></span></td>
                                        <td>DEP-002</span></span></td>
                                        <td>Bonus deposit</span></span></td>
                                        <td><i class="ti ti-trash text-danger" style="cursor:pointer" onclick="deleteTransaction(4)"></i></span></span></td>
                                    </tr>
                                    <tr>
                                        <td>2025-05-12</span></td>
                                        <td><span class="badge-withdrawal">Withdrawal</span></span></span></td>
                                        <td>₱ 1,000.00</span></span></td>
                                        <td>WDL-001</span></span></td>
                                        <td>Emergency withdrawal</span></span></td>
                                        <td><i class="ti ti-trash text-danger" style="cursor:pointer" onclick="deleteTransaction(5)"></i></span></span></td>
                                    </tr>
                                    <tr>
                                        <td>2025-06-18</span></td>
                                        <td><span class="badge-contribution">Capital Contribution</span></span></span></td>
                                        <td>₱ 2,500.00</span></span></td>
                                        <td>CAP-003</span></span></td>
                                        <td>Quarterly capital contribution</span></span></td>
                                        <td><i class="ti ti-trash text-danger" style="cursor:pointer" onclick="deleteTransaction(6)"></i></span></span></td>
                                    </tr>
                                    <tr>
                                        <td>2025-07-22</span></td>
                                        <td><span class="badge-deposit">Savings Deposit</span></span></span></td>
                                        <td>₱ 3,000.00</span></span></td>
                                        <td>DEP-003</span></span></td>
                                        <td>Bonus deposit from 13th month</span></span></td>
                                        <td><i class="ti ti-trash text-danger" style="cursor:pointer" onclick="deleteTransaction(7)"></i></span></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="info-alert mt-3"><i class="ti ti-info-circle"></i><span>For adding new transactions, please use the dedicated Capital Transaction module.</span></div>
                        <input type="hidden" id="capital_member_id" value="<?= $member_id ?>">
                    </div>
                    
                    <!-- CO-MAKER / GUARANTOR MAPPING & LIABILITY MONITORING TAB -->
                    <div class="tab-pane fade" id="comaker-management">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="capital-stat-card liability-summary" style="background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%);">
                                    <div class="capital-stat-label"><i class="ti ti-alert-triangle" style="color: var(--warning);"></i> Total Contingent Liability</div>
                                    <div class="capital-stat-value" style="color: #d97706;">₱ <span id="total_contingent_liability">115,000.00</span></div>
                                    <div class="capital-stat-sub">As guarantor/co-maker for other members' loans</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="capital-stat-card" style="background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);">
                                    <div class="capital-stat-label"><i class="ti ti-shield" style="color: var(--info);"></i> Covered by Guarantors</div>
                                    <div class="capital-stat-value" style="color: #1e40af;">₱ <span id="total_covered_by_guarantors">65,000.00</span></div>
                                    <div class="capital-stat-sub">Total loan amount backed by co-makers/guarantors</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h6><i class="ti ti-share-alt me-2"></i> Guarantor Network Map</h6>
                            <div class="guarantor-network">
                                <div class="mb-2"><strong>This member is a guarantor for:</strong></div>
                                <div id="guarantor_for_list">
                                    <span class="guarantor-node">Juan Cruz (M002) - ₱25,000 outstanding</span>
                                    <span class="guarantor-node">Maria Reyes (M005) - ₱30,000 outstanding</span>
                                    <span class="guarantor-node">Pedro Santos (M008) - ₱60,000 outstanding</span>
                                </div>
                                <div class="mt-3 mb-2"><strong>Guarantors for this member:</strong></div>
                                <div id="guarantor_of_list">
                                    <span class="guarantor-node">Ana Dela Cruz (M003)</span>
                                    <span class="guarantor-node">Roberto Garcia (M007)</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h6><i class="ti ti-handshake me-2"></i> As Co-Maker / Guarantor for Others</h6>
                            <p class="text-muted small mb-3">This member has guaranteed loans for the following borrowers</p>
                            <div class="transaction-table-wrapper">
                                <table class="transaction-table">
                                    <thead>
                                        <tr><th>Borrower</th><th>Loan Reference</th><th>Loan Amount</th><th>Outstanding Balance</th><th>Loan Status</th><th>Relationship</th><th width="60">Action</th></tr>
                                    </thead>
                                    <tbody id="comakerForOthersList">
                                        <tr>
                                            <td><strong>Cruz, Juan</strong><br><small class="text-muted">M002</small></span></td>
                                            <td>LN-2024-001</span></td>
                                            <td>₱ 50,000.00</span></td>
                                            <td class="text-danger fw-bold">₱ 25,000.00</span></td>
                                            <td><span class="status-pill status-active">Active</span></span></td>
                                            <td>Co-Maker</span></td>
                                            <td class="text-center"><i class="ti ti-trash text-danger" style="cursor:pointer"></i></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Reyes, Maria</strong><br><small class="text-muted">M005</small></span></td>
                                            <td>LN-2024-003</span></td>
                                            <td>₱ 30,000.00</span></td>
                                            <td class="text-warning fw-bold">₱ 30,000.00</span></td>
                                            <td><span class="status-pill status-pending">Pending</span></span></td>
                                            <td>Guarantor</span></td>
                                            <td class="text-center"><i class="ti ti-trash text-danger" style="cursor:pointer"></i></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Santos, Pedro</strong><br><small class="text-muted">M008</small></span></td>
                                            <td>LN-2023-012</span></td>
                                            <td>₱ 100,000.00</span></td>
                                            <td class="text-danger fw-bold">₱ 60,000.00</span></td>
                                            <td><span class="status-pill status-default">Default</span></span></td>
                                            <td>Co-Maker</span></td>
                                            <td class="text-center"><i class="ti ti-trash text-danger" style="cursor:pointer"></i></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h6><i class="ti ti-users me-2"></i> Co-Makers / Guarantors for This Member</h6>
                            <p class="text-muted small mb-3">These members guarantee loans taken by this member</p>
                            <div class="transaction-table-wrapper">
                                <table class="transaction-table">
                                    <thead>
                                        <tr><th>Guarantor</th><th>Loan Reference</th><th>Loan Amount</th><th>Outstanding Balance</th><th>Loan Status</th><th>Relationship</th><th width="60">Status</th></tr>
                                    </thead>
                                    <tbody id="guarantorsForMemberList">
                                        <tr>
                                            <td><strong>Dela Cruz, Ana</strong><br><small class="text-muted">M003</small></span></td>
                                            <td>LN-2024-002</span></span></td>
                                            <td>₱ 40,000.00</span></span></td>
                                            <td>₱ 15,000.00</span></span></td>
                                            <td><span class="status-pill status-active">Active</span></span></td>
                                            <td>Spouse</span></span></td>
                                            <td><span class="badge" style="background: #e0e7ff; color: #3730a3;">Active</span></span></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Garcia, Roberto</strong><br><small class="text-muted">M007</small></span></span></td>
                                            <td>LN-2024-005</span></span></span></td>
                                            <td>₱ 25,000.00</span></span></td>
                                            <td>₱ 25,000.00</span></span></td>
                                            <td><span class="status-pill status-active">Active</span></span></span></td>
                                            <td>Relative</span></span></span></td>
                                            <td><span class="badge" style="background: #e0e7ff; color: #3730a3;">Active</span></span></span></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 text-end"><button type="button" class="btn btn-capital btn-capital-primary" data-bs-toggle="modal" data-bs-target="#addCoMakerModal"><i class="ti ti-plus"></i> Add Co-Maker / Guarantor</button></div>
                        </div>
                        
                        <div class="info-alert mt-3"><i class="ti ti-info-circle"></i><span><strong>Liability Monitoring Notes:</strong><br>• As a co-maker/guarantor, you are jointly and severally liable for the loan.<br>• Default by the borrower may result in collection from guarantors.<br>• Total contingent liability shown above represents outstanding loan balances you have guaranteed.</span></div>
                        
                        <input type="hidden" id="comaker_member_id" value="<?= $member_id ?>">
                    </div>
                    
                    <!-- ACCOUNT HISTORY TAB -->
                    <div class="tab-pane fade" id="account-history">
                        <div class="capital-stats-grid">
                            <div class="capital-stat-card"><div class="capital-stat-label"><i class="ti ti-wallet"></i> Total Share Capital</div><div class="capital-stat-value">₱ 12,500.00</div><div class="capital-stat-sub">As of <?= date('F d, Y') ?></div></div>
                            <div class="capital-stat-card"><div class="capital-stat-label"><i class="ti ti-credit-card"></i> Active Loans</div><div class="capital-stat-value">₱ 40,000.00</div><div class="capital-stat-sub">2 active loans</div></div>
                            <div class="capital-stat-card"><div class="capital-stat-label"><i class="ti ti-chart-line"></i> Total Payments Made</div><div class="capital-stat-value">₱ 18,500.00</div><div class="capital-stat-sub">Year-to-date payments</div></div>
                            <div class="capital-stat-card"><div class="capital-stat-label"><i class="ti ti-calendar"></i> Member Since</div><div class="capital-stat-value">2024</div><div class="capital-stat-sub">1 year 6 months</div></div>
                        </div>
                        
                        <div class="filter-buttons mb-4 d-flex flex-wrap gap-2">
                            <button type="button" class="btn-filter active" data-filter="all">All Transactions</button>
                            <button type="button" class="btn-filter" data-filter="contribution">Capital Contributions</button>
                            <button type="button" class="btn-filter" data-filter="deposit">Savings Deposits</button>
                            <button type="button" class="btn-filter" data-filter="withdrawal">Withdrawals</button>
                            <button type="button" class="btn-filter" data-filter="loan">Loan Disbursements</button>
                            <button type="button" class="btn-filter" data-filter="payment">Loan Payments</button>
                        </div>
                        
                        <h6 class="fw-semibold mb-3"><i class="ti ti-history me-2"></i> Account Activity Timeline</h6>
                        <div class="transaction-table-wrapper" id="activityTimeline">
                            <div class="timeline-item" data-type="loan"><div class="timeline-icon loan"><i class="ti ti-credit-card"></i></div><div class="timeline-content"><div class="d-flex justify-content-between align-items-start flex-wrap"><div><span class="timeline-title">Loan Disbursement - LN-2024-002</span><div class="timeline-date">June 15, 2025</div></div><div class="timeline-amount text-warning">+₱ 25,000.00</div></div><div class="timeline-reference">Reference: LN-2024-002 | Purpose: Emergency Loan</div></div></div>
                            <div class="timeline-item" data-type="payment"><div class="timeline-icon payment"><i class="ti ti-receipt"></i></div><div class="timeline-content"><div class="d-flex justify-content-between align-items-start flex-wrap"><div><span class="timeline-title">Loan Payment - LN-2024-001</span><div class="timeline-date">June 10, 2025</div></div><div class="timeline-amount text-success">-₱ 3,500.00</div></div><div class="timeline-reference">Reference: OR-2025-0456 | Monthly Amortization</div></div></div>
                            <div class="timeline-item" data-type="deposit"><div class="timeline-icon deposit"><i class="ti ti-pig-money"></i></div><div class="timeline-content"><div class="d-flex justify-content-between align-items-start flex-wrap"><div><span class="timeline-title">Savings Deposit</span><div class="timeline-date">June 5, 2025</div></div><div class="timeline-amount text-success">+₱ 2,000.00</div></div><div class="timeline-reference">Reference: DEP-2025-008 | Monthly savings contribution</div></div></div>
                            <div class="timeline-item" data-type="payment"><div class="timeline-icon payment"><i class="ti ti-receipt"></i></div><div class="timeline-content"><div class="d-flex justify-content-between align-items-start flex-wrap"><div><span class="timeline-title">Loan Payment - LN-2024-002</span><div class="timeline-date">May 10, 2025</div></div><div class="timeline-amount text-success">-₱ 2,800.00</div></div><div class="timeline-reference">Reference: OR-2025-0389 | Monthly Amortization</div></div></div>
                            <div class="timeline-item" data-type="withdrawal"><div class="timeline-icon withdrawal"><i class="ti ti-arrow-back-up"></i></div><div class="timeline-content"><div class="d-flex justify-content-between align-items-start flex-wrap"><div><span class="timeline-title">Savings Withdrawal</span><div class="timeline-date">May 12, 2025</div></div><div class="timeline-amount text-danger">-₱ 1,000.00</div></div><div class="timeline-reference">Reference: WDL-001 | Emergency withdrawal</div></div></div>
                            <div class="timeline-item" data-type="contribution"><div class="timeline-icon contribution"><i class="ti ti-coins"></i></div><div class="timeline-content"><div class="d-flex justify-content-between align-items-start flex-wrap"><div><span class="timeline-title">Capital Contribution</span><div class="timeline-date">April 20, 2025</div></div><div class="timeline-amount text-success">+₱ 1,500.00</div></div><div class="timeline-reference">Reference: CAP-002 | Additional share capital</div></div></div>
                            <div class="timeline-item" data-type="loan"><div class="timeline-icon loan"><i class="ti ti-credit-card"></i></div><div class="timeline-content"><div class="d-flex justify-content-between align-items-start flex-wrap"><div><span class="timeline-title">Loan Disbursement - LN-2024-001</span><div class="timeline-date">March 10, 2025</div></div><div class="timeline-amount text-warning">+₱ 40,000.00</div></div><div class="timeline-reference">Reference: LN-2024-001 | Purpose: Housing Loan</div></div></div>
                            <div class="timeline-item" data-type="contribution"><div class="timeline-icon contribution"><i class="ti ti-coins"></i></div><div class="timeline-content"><div class="d-flex justify-content-between align-items-start flex-wrap"><div><span class="timeline-title">Capital Contribution</span><div class="timeline-date">March 5, 2025</div></div><div class="timeline-amount text-success">+₱ 3,000.00</div></div><div class="timeline-reference">Reference: CAP-001 | Additional capital contribution</div></div></div>
                            <div class="timeline-item" data-type="deposit"><div class="timeline-icon deposit"><i class="ti ti-pig-money"></i></div><div class="timeline-content"><div class="d-flex justify-content-between align-items-start flex-wrap"><div><span class="timeline-title">Savings Deposit</span><div class="timeline-date">February 10, 2025</div></div><div class="timeline-amount text-success">+₱ 2,000.00</div></div><div class="timeline-reference">Reference: DEP-001 | Monthly savings deposit</div></div></div>
                            <div class="timeline-item" data-type="contribution"><div class="timeline-icon contribution"><i class="ti ti-coins"></i></div><div class="timeline-content"><div class="d-flex justify-content-between align-items-start flex-wrap"><div><span class="timeline-title">Initial Capital Contribution</span><div class="timeline-date">January 15, 2025</div></div><div class="timeline-amount text-success">+₱ 5,000.00</div></div><div class="timeline-reference">Reference: CAP-001 | Initial share capital</div></div></div>
                        </div>
                        
                        <div class="row mt-4"><div class="col-sm-12 text-end"><button type="button" class="btn btn-capital" onclick="alert('Mockup: Export account history as PDF/Excel')"><i class="ti ti-download me-1"></i> Export Statement</button></div></div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="row mt-4 p-3 border-top">
                    <div class="col-sm-12 text-end">
                        <button type="submit" class="<?= empty($member_id) ? 'btn-save' : 'btn-update' ?>"><i class="ti ti-device-floppy me-1"></i><?= empty($member_id) ? 'Save Member' : 'Update Member Information' ?></button>
                        <a href="<?=site_url();?>mymembers?meaction=MAIN" class="btn btn-light border ms-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Required Documents Card -->
    <?php if(!empty($member_id)): ?>
    <div class="card mt-4">
        <div class="card-header"><h6 class="fw-semibold mb-0"><i class="ti ti-files me-2" style="color: var(--gold-primary);"></i> Required Documents <span class="badge bg-success ms-2">Member: <?= $member_no ?> - <?= $first_name ?> <?= $last_name ?></span></h6></div>
        <div class="card-body">
            <div class="info-alert"><i class="ti ti-info-circle"></i><span>Upload required documents. Supported: PDF, JPG, JPEG, PNG (Max 5MB)</span></div>
            <form id="documentsUploadForm" enctype="multipart/form-data">
                <input type="hidden" name="member_id" value="<?= $member_id ?>">
                <div class="form-section"><h6><i class="ti ti-file-description me-2"></i> Membership Application Documents</h6>
                    <div class="documents-grid">
                        <?php
                        $doc_fields = [
                            'gov_id' => ['Government-issued ID', 'Required', 'ti-id', 'required'],
                            'proof_of_group' => ['Proof of Group Belonging', 'Required', 'ti-building', 'required'],
                            'id_photo' => ['ID Photo', 'Required', 'ti-camera', 'required'],
                            'signed_membership' => ['Signed Membership Form', 'Required', 'ti-file-signature', 'required']
                        ];
                        ?>
                        <?php foreach($doc_fields as $field_name => $doc_info): ?>
                        <div class="document-card">
                            <div class="doc-icon"><i class="<?= $doc_info[2] ?>"></i></div>
                            <div class="doc-title"><?= $doc_info[0] ?></div>
                            <span class="doc-badge <?= $doc_info[3] ?>"><?= $doc_info[1] ?></span>
                            <input type="file" name="<?= $field_name ?>" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png,.gif">
                            <?php if(isset($doc_by_type[$field_name])): 
                                $doc = $doc_by_type[$field_name];
                                $file_url = base_url($doc['document_path']);
                                $file_icon = get_file_icon($doc['document_path']);
                            ?>
                            <div class="existing-file">
                                <i class="ti ti-check-circle"></i>
                                <div style="flex:1;">
                                    <a href="javascript:void(0)" class="view-document-link" data-file-url="<?= $file_url ?>" data-file-name="<?= htmlspecialchars($doc['document_name']) ?>">
                                        <i class="<?= $file_icon ?> me-1"></i><?= htmlspecialchars($doc['document_name']) ?>
                                    </a>
                                    <div class="file-info">
                                        <?= format_file_size($doc['file_size']) ?> | Uploaded: <?= date('M d, Y', strtotime($doc['upload_date'])) ?>
                                    </div>
                                </div>
                                <a href="javascript:void(0)" class="view-link view-document-link" data-file-url="<?= $file_url ?>" data-file-name="<?= htmlspecialchars($doc['document_name']) ?>">
                                    <i class="ti ti-eye"></i> View
                                </a>
                                <a href="<?= $file_url ?>" class="view-link" download style="background: var(--success);">
                                    <i class="ti ti-download"></i> Download
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="form-section mt-4"><h6><i class="ti ti-credit-card me-2"></i> Loan Application Documents</h6>
                    <div class="documents-grid">
                        <?php
                        $loan_doc_fields = [
                            'proof_of_income' => ['Proof of Income', 'Required for Loans', 'ti-wallet', 'required'],
                            'bank_statement' => ['Bank Statement', 'Required for Loans', 'ti-chart-bar', 'required'],
                            'salary_deduction_auth' => ['Salary Deduction Authorization', 'Required by Law', 'ti-receipt-tax', 'required']
                        ];
                        ?>
                        <?php foreach($loan_doc_fields as $field_name => $doc_info): ?>
                        <div class="document-card">
                            <div class="doc-icon"><i class="<?= $doc_info[2] ?>"></i></div>
                            <div class="doc-title"><?= $doc_info[0] ?></div>
                            <span class="doc-badge <?= $doc_info[3] ?>"><?= $doc_info[1] ?></span>
                            <input type="file" name="<?= $field_name ?>" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                            <?php if(isset($doc_by_type[$field_name])): 
                                $doc = $doc_by_type[$field_name];
                                $file_url = base_url($doc['document_path']);
                                $file_icon = get_file_icon($doc['document_path']);
                            ?>
                            <div class="existing-file">
                                <i class="ti ti-check-circle"></i>
                                <div style="flex:1;">
                                    <a href="javascript:void(0)" class="view-document-link" data-file-url="<?= $file_url ?>" data-file-name="<?= htmlspecialchars($doc['document_name']) ?>">
                                        <i class="<?= $file_icon ?> me-1"></i><?= htmlspecialchars($doc['document_name']) ?>
                                    </a>
                                    <div class="file-info">
                                        <?= format_file_size($doc['file_size']) ?> | Uploaded: <?= date('M d, Y', strtotime($doc['upload_date'])) ?>
                                    </div>
                                </div>
                                <a href="javascript:void(0)" class="view-link view-document-link" data-file-url="<?= $file_url ?>" data-file-name="<?= htmlspecialchars($doc['document_name']) ?>">
                                    <i class="ti ti-eye"></i> View
                                </a>
                                <a href="<?= $file_url ?>" class="view-link" download style="background: var(--success);">
                                    <i class="ti ti-download"></i> Download
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-12 text-end">
                        <button type="submit" class="btn-document-upload"><i class="ti ti-cloud-upload me-1"></i> Upload Selected Documents</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Member List Card -->
    <div class="card mt-4">
        <div class="card-header"><h6 class="fw-semibold mb-0"><i class="ti ti-list me-2" style="color: var(--gold-primary);"></i> Member List</h6></div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                    <thead><tr><th width="80">Action</th><th width="50">Photo</th><th>Member No.</th><th>Last Name</th><th>First Name</th><th>Contact No.</th><th>Email</th><th>Loan Count</th><th>Loan Amount</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php if(!empty($membersdata)): foreach ($membersdata as $data): $mid = $data['member_id']; $has_photo = !empty($data['id_photo_path']) && file_exists(FCPATH . $data['id_photo_path']); ?>
                        <tr><td class="text-center"><div class="d-flex justify-content-center gap-2"><a class="text-primary nav-icon-hover" href="mymembers?meaction=MAIN&member_id=<?= $mid ?>"><i class="ti ti-pencil"></i></a><button class="btn btn-sm text-warning p-0 border-0 bg-transparent" onclick="__mysys_members_ent.__showPdfInModal('<?= base_url('mymembers?meaction=MEMBERS-PRINT&member_id='.$mid) ?>')"><i class="ti ti-printer"></i></button></div></span>
                        <td class="text-center"><?php if($has_photo): ?><div class="member-avatar has-photo"><img src="<?= base_url($data['id_photo_path']) ?>"></div><?php else: ?><div class="member-avatar default"><i class="ti ti-user"></i></div><?php endif; ?></span>
                        <td class="text-center"><?= htmlspecialchars($data['member_no']); ?></span>
                        <td class="text-center"><?= htmlspecialchars($data['last_name']); ?></span>
                        <td class="text-center"><?= htmlspecialchars($data['first_name']); ?></span>
                        <td class="text-center"><?= htmlspecialchars($data['contact_number']); ?></span>
                        <td class="text-center"><?= htmlspecialchars($data['email']); ?></span>
                        <td class="text-center"><?= $data['loan_count']; ?></span>
                        <td class="text-center">₱<?= number_format($data['loan_amount'],2); ?></span>
                        <td class="text-center"><span class="status-pill status-active">Active</span></span>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Co-Maker Modal -->
<div class="modal fade" id="addCoMakerModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title"><i class="ti ti-handshake me-2" style="color: var(--gold-primary);"></i>Add Co-Maker / Guarantor</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <input type="hidden" id="modal_member_id" value="<?= $member_id ?>">
        <div class="mb-3"><label class="form-label">Search Guarantor</label><input type="text" id="search_guarantor" class="form-control" placeholder="Search by name or member number..."><div id="search_results" class="member-search-dropdown" style="display:none;"></div></div>
        <div id="selected_guarantor_info" class="mt-2" style="display:none;"><div class="co-maker-card"><i class="ti ti-user-check me-2" style="color: var(--success);"></i> Selected: <strong id="selected_name"></strong> (<span id="selected_no"></span>)<input type="hidden" id="selected_guarantor_id"></div></div>
        <div class="mb-3"><label class="form-label">Loan Reference</label><input type="text" id="loan_reference" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Loan Amount (₱)</label><input type="number" id="loan_amount" class="form-control" step="0.01"></div>
        <div class="mb-3"><label class="form-label">Relationship Type</label><select id="relationship_type" class="form-select"><option>Co-Maker</option><option>Guarantor</option><option>Spouse</option><option>Relative</option></select></div>
        <div class="mb-3"><label class="form-label">Notes</label><textarea id="comaker_notes" class="form-control" rows="2"></textarea></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-light-custom" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-capital btn-capital-primary" id="saveCoMakerBtn">Add Guarantor</button></div></div></div>
</div>

<!-- Document Viewer Modal -->
<div class="modal fade document-viewer-modal" id="documentViewerModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentViewerTitle"><i class="ti ti-file me-2"></i> Document Viewer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="documentViewerContent" class="text-center p-4" style="min-height: 80vh;">
                    <div class="spinner-border text-gold" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="downloadDocumentBtn" class="btn btn-capital btn-capital-primary" download>
                    <i class="ti ti-download me-1"></i> Download
                </a>
                <button type="button" class="btn btn-light-custom" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- PDF Modal for Member Profile -->
<div class="modal fade" id="pdfModal" tabindex="-1">
    <div class="modal-dialog modal-xl"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Membership Profile Preview</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><iframe id="pdfFrame" src="" style="width:100%;height:80vh;" frameborder="0"></iframe></div></div></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url('assets/js/members-management/mymembers.js?v=5');?>"></script>

<script>
// Preserve the original __mysys_members_ent object for PDF viewing
window.__mysys_members_ent = window.__mysys_members_ent || {};
window.__mysys_members_ent.__showPdfInModal = function(url) {
    $('#pdfFrame').attr('src', url);
    $('#pdfModal').modal('show');
};

$(document).ready(function() {
    $('#datatablesSimple').DataTable({ pageLength: 10, lengthChange: true, order: [[1, 'asc']], language: { search: "Search Member:", info: "Showing _START_ to _END_ of _TOTAL_ entries" }, autoWidth: false, responsive: true });
    
    $('#date_of_birth').on('change', function() { let dob = new Date(this.value); if(dob) { let today = new Date(); let age = today.getFullYear() - dob.getFullYear(); let m = today.getMonth() - dob.getMonth(); if(m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--; if(age>0 && age<120) $('#age').val(age); } });
    
    $('#togglePassword').click(function() { const input = $('#password'); const icon = $('#toggleIcon'); if(input.attr('type') === 'password') { input.attr('type', 'text'); icon.removeClass('ti-eye').addClass('ti-eye-off'); } else { input.attr('type', 'password'); icon.removeClass('ti-eye-off').addClass('ti-eye'); } });
    
    $('.btn-filter').on('click', function(e) { e.preventDefault(); e.stopPropagation(); $('.btn-filter').removeClass('active'); $(this).addClass('active'); var filter = $(this).data('filter'); if(filter === 'all') { $('.timeline-item').show(); } else { $('.timeline-item').hide(); $('.timeline-item[data-type="' + filter + '"]').show(); } return false; });
    
    $('#search_guarantor').on('input', function() {
        let val = $(this).val().toLowerCase();
        if(val.length < 2) { $('#search_results').hide(); return; }
        let mockMembers = [{id:2,no:'M002',name:'Cruz, Juan'},{id:3,no:'M003',name:'Dela Cruz, Ana'},{id:4,no:'M004',name:'Reyes, Maria'},{id:5,no:'M005',name:'Garcia, Roberto'}];
        let results = mockMembers.filter(m => m.name.toLowerCase().includes(val) || m.no.toLowerCase().includes(val));
        if(results.length) {
            let html = results.map(m => `<div class="search-result-item" data-id="${m.id}" data-no="${m.no}" data-name="${m.name}"><strong>${m.name}</strong><br><small>${m.no}</small></div>`).join('');
            $('#search_results').html(html).show();
            $('.search-result-item').click(function() { $('#selected_guarantor_id').val($(this).data('id')); $('#selected_name').text($(this).data('name')); $('#selected_no').text($(this).data('no')); $('#selected_guarantor_info').show(); $('#search_guarantor').val(''); $('#search_results').hide(); });
        } else { $('#search_results').hide(); }
    });
    $(document).click(function(e){ if(!$(e.target).closest('#search_guarantor, #search_results').length) $('#search_results').hide(); });
    
    $('#saveCoMakerBtn').click(function() { let guarantorId = $('#selected_guarantor_id').val(); let loanRef = $('#loan_reference').val().trim(); let loanAmount = parseFloat($('#loan_amount').val()); if(!guarantorId) { alert('Please select a guarantor.'); return; } if(!loanRef) { alert('Please enter a loan reference.'); return; } if(isNaN(loanAmount) || loanAmount <= 0) { alert('Please enter a valid loan amount.'); return; } alert('Mockup: Guarantor added successfully!'); $('#addCoMakerModal').modal('hide'); $('#selected_guarantor_info').hide(); $('#selected_guarantor_id, #loan_reference, #loan_amount, #comaker_notes').val(''); });
    
    // Document viewer functionality for uploaded documents
    function viewDocument(fileUrl, fileName) {
        $('#documentViewerTitle').html('<i class="ti ti-file me-2"></i> ' + (fileName || 'Document Viewer'));
        $('#documentViewerContent').html('<div class="text-center p-5"><div class="spinner-border text-gold" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-3 text-muted">Loading document...</p></div>');
        $('#downloadDocumentBtn').attr('href', fileUrl);
        
        // Get file extension
        var fileExtension = fileUrl.split('.').pop().toLowerCase();
        
        // Handle different file types
        if (fileExtension === 'pdf') {
            $('#documentViewerContent').html('<iframe src="' + fileUrl + '" class="document-frame" style="width:100%; min-height:80vh; border:none;"></iframe>');
        } else if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(fileExtension)) {
            $('#documentViewerContent').html('<img src="' + fileUrl + '" class="img-fluid" style="max-height: 80vh; object-fit: contain;" alt="Document Image">');
        } else {
            // For other file types, show download option
            $('#documentViewerContent').html(`
                <div class="text-center p-5">
                    <i class="ti ti-file" style="font-size: 64px; color: var(--gray-400);"></i>
                    <h5 class="mt-3">Cannot preview this file type</h5>
                    <p class="text-muted">File type: .${fileExtension.toUpperCase()}</p>
                    <a href="${fileUrl}" class="btn btn-capital btn-capital-primary mt-3" download>
                        <i class="ti ti-download me-1"></i> Download to view
                    </a>
                </div>
            `);
        }
        
        $('#documentViewerModal').modal('show');
    }
    
    // Bind click events to document view links
    $(document).on('click', '.view-document-link', function(e) {
        e.preventDefault();
        var fileUrl = $(this).data('file-url');
        var fileName = $(this).data('file-name');
        viewDocument(fileUrl, fileName);
    });
});

function deleteTransaction(id) {
    if(confirm('Delete this transaction?')) {
        alert('Transaction ' + id + ' deleted (mockup)');
        location.reload();
    }
}
</script>

<?php echo view('templates/myfooter.php'); ?>