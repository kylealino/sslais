<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');

// Helper functions for document handling
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
        case 'zip':
        case 'rar': return 'ti-file-zip';
        case 'txt': return 'ti-file-text';
        default: return 'ti-file';
    }
}

function format_file_size($bytes) {
    if (empty($bytes) || $bytes == 0) {
        return 'N/A';
    }
    if ($bytes >= 1048576) {
        return round($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return round($bytes / 1024, 2) . ' KB';
    }
    return $bytes . ' bytes';
}

function is_image_file($file_path) {
    $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
}

function is_pdf_file($file_path) {
    $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    return $extension === 'pdf';
}

function get_file_type_label($file_path) {
    $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    return strtoupper($extension);
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

    /* Compact Stat Cards - Single Row */
    .stat-cards-wrapper {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: var(--white-bg);
        border-radius: 14px;
        border: 1px solid var(--gray-200);
        padding: 12px 16px;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-height: 70px;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border-color: var(--gold-primary);
    }

    .stat-card.active-filter {
        border: 2px solid var(--gold-primary);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
    }

    .stat-card .stat-info {
        flex: 1;
    }

    .stat-card .stat-label {
        font-size: 9px;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .stat-card .stat-value {
        font-size: 22px;
        font-weight: 700;
        line-height: 1.2;
        color: var(--gray-800);
    }

    .stat-card .stat-sub {
        font-size: 9px;
        color: var(--gray-400);
        margin-top: 1px;
    }

    .stat-card .stat-icon {
        font-size: 28px;
        opacity: 0.12;
        color: var(--gold-primary);
        flex-shrink: 0;
        margin-left: 8px;
    }

    .stat-pending .stat-value { color: #d97706; }
    .stat-submitted .stat-value { color: #1e40af; }
    .stat-review .stat-value { color: #4338ca; }
    .stat-approved .stat-value { color: #065f46; }
    .stat-declined .stat-value { color: #dc2626; }

    .card {
        border: 1px solid var(--gray-200);
        border-radius: 20px;
        background: var(--white-bg);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .card-header {
        background: var(--white-bg);
        border-bottom: 1px solid var(--gray-200);
        padding: 14px 20px;
        font-weight: 600;
        color: var(--navy-dark);
    }

    .card-body {
        padding: 20px;
    }

    .btn-primary {
        background: var(--navy-dark);
        border: none;
        border-radius: 10px;
        padding: 6px 16px;
        font-size: 12px;
        transition: all 0.2s;
        color: white;
    }

    .btn-primary:hover {
        background: var(--navy-medium);
        transform: translateY(-1px);
        color: white;
    }

    .btn-success {
        background: var(--gold-primary);
        border: none;
        border-radius: 10px;
        padding: 8px 20px;
        font-size: 12px;
        font-weight: 600;
        color: var(--navy-dark);
        transition: all 0.2s;
    }

    .btn-success:hover {
        background: var(--gold-dark);
        transform: translateY(-1px);
        color: white;
    }
    
    .btn-approve {
        background: var(--success);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 8px 20px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-approve:hover {
        background: #059669;
        transform: translateY(-1px);
        color: white;
    }

    .btn-decline {
        background: var(--danger);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 8px 20px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-decline:hover {
        background: #dc2626;
        transform: translateY(-1px);
        color: white;
    }

    .btn-review {
        background: var(--warning);
        color: var(--navy-dark);
        border: none;
        border-radius: 10px;
        padding: 8px 20px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-review:hover {
        background: #d97706;
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-submit {
        background: var(--info);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 8px 20px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        background: #2563eb;
        transform: translateY(-1px);
        color: white;
    }
    
    .btn-back {
        background: var(--gray-100);
        border: 1px solid var(--gray-200);
        border-radius: 10px;
        padding: 8px 20px;
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-600);
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: var(--gold-soft);
        border-color: var(--gold-primary);
        color: var(--gold-dark);
    }

    .btn-outline-secondary {
        background: transparent;
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 500;
        color: var(--gray-600);
        transition: all 0.2s;
    }

    .btn-outline-secondary:hover {
        background: var(--gray-100);
        border-color: var(--gray-400);
    }

    .btn-view-doc {
        background: var(--info);
        border: none;
        border-radius: 6px;
        padding: 2px 10px;
        font-size: 10px;
        font-weight: 600;
        color: white;
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-view-doc:hover {
        background: #2563eb;
        color: white;
        text-decoration: none;
    }

    .btn-download-doc {
        background: var(--success);
        border: none;
        border-radius: 6px;
        padding: 2px 10px;
        font-size: 10px;
        font-weight: 600;
        color: white;
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-download-doc:hover {
        background: #059669;
        color: white;
        text-decoration: none;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 30px;
    }

    .status-pending { background: #fef3c7; color: #d97706; }
    .status-submitted { background: #dbeafe; color: #1e40af; }
    .status-review { background: #e0e7ff; color: #4338ca; }
    .status-approved { background: #d1fae5; color: #065f46; }
    .status-declined { background: #fee2e2; color: #dc2626; }

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

    .breadcrumb-item a:hover {
        color: var(--gold-primary);
    }

    .breadcrumb-item.active {
        color: var(--gold-dark);
        font-weight: 600;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background: transparent;
        color: var(--gray-500);
        font-weight: 600;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 8px 6px;
        border-bottom: 1px solid var(--gray-200);
    }

    .table tbody td {
        padding: 6px 6px;
        color: var(--gray-700);
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
        font-size: 11px;
    }

    .table tbody tr:hover td {
        background: var(--gold-soft);
    }
    
    .table tbody tr.table-success td {
        background: #d1fae5;
    }

    /* Approval Detail Styles */
    .approval-timeline {
        position: relative;
        padding-left: 30px;
    }

    .approval-timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--gray-200);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 25px;
        padding-left: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 5px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: var(--gray-300);
        border: 2px solid var(--white-bg);
    }

    .timeline-item.completed::before {
        background: var(--success);
    }

    .timeline-item.active::before {
        background: var(--gold-primary);
        box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.2);
        animation: pulse 2s infinite;
    }

    .timeline-item.declined::before {
        background: var(--danger);
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(212, 175, 55, 0); }
        100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
    }

    .timeline-badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
    }

    .badge-submit { background: #dbeafe; color: #1e40af; }
    .badge-review { background: #fef3c7; color: #d97706; }
    .badge-approve { background: #d1fae5; color: #065f46; }
    .badge-decline { background: #fee2e2; color: #dc2626; }
    .badge-revise { background: #fce4ec; color: #c62828; }

    .approval-actions {
        background: var(--gray-50);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid var(--gray-200);
    }

    .status-flow {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 20px;
        background: var(--gray-50);
        border-radius: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .status-step {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--gray-400);
    }

    .status-step.active {
        color: var(--gold-primary);
        font-weight: 600;
    }

    .status-step.completed {
        color: var(--success);
    }

    .status-step .step-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .status-step.active .step-icon {
        background: var(--gold-primary);
        color: white;
    }

    .status-step.completed .step-icon {
        background: var(--success);
        color: white;
    }

    .status-connector {
        flex: 1;
        height: 2px;
        background: var(--gray-200);
        min-width: 30px;
    }

    .status-connector.completed {
        background: var(--success);
    }

    .status-connector.active {
        background: var(--gold-primary);
    }

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
        padding: 8px 12px;
        font-size: 13px;
        transition: all 0.2s;
        background: var(--white-bg);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--gold-primary);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        outline: none;
    }

    /* DataTables */
    .dataTables_wrapper {
        font-family: 'Inter', sans-serif;
    }

    .dataTables_filter {
        float: right;
        margin-bottom: 20px;
    }

    .dataTables_filter input {
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 13px;
        width: 250px;
    }

    .dataTables_filter input:focus {
        border-color: var(--gold-primary);
        outline: none;
    }

    .dataTables_paginate {
        float: right;
        margin-top: 20px;
    }

    .dataTables_paginate .paginate_button {
        padding: 6px 12px !important;
        margin: 0 3px !important;
        border-radius: 8px !important;
        border: 1px solid var(--gray-200) !important;
        background: var(--white-bg) !important;
        color: var(--gray-600) !important;
        font-size: 12px !important;
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

    .dataTables_info {
        float: left;
        font-size: 12px;
        color: var(--gray-500);
        margin-top: 20px;
    }

    #uploadOverlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    #uploadOverlay.active {
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

    .badge {
        padding: 3px 8px;
        font-size: 9px;
        font-weight: 600;
        border-radius: 20px;
    }
    
    .badge-paid {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge-unpaid {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-pdf { background: #fee2e2; color: #dc2626; }
    .badge-image { background: #dbeafe; color: #1e40af; }
    .badge-doc { background: #fef3c7; color: #d97706; }
    .badge-txt { background: #e0e7ff; color: #4338ca; }
    .badge-other { background: #e2e8f0; color: #475569; }

    /* Responsive */
    @media (max-width: 1200px) {
        .stat-cards-wrapper {
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }
        .stat-card .stat-value {
            font-size: 18px;
        }
        .stat-card .stat-icon {
            font-size: 22px;
        }
    }

    @media (max-width: 992px) {
        .stat-cards-wrapper {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 16px;
        }
        .stat-cards-wrapper {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }
        .stat-card {
            padding: 10px 12px;
            min-height: 60px;
        }
        .stat-card .stat-value {
            font-size: 16px;
        }
        .stat-card .stat-icon {
            font-size: 20px;
        }
        .dataTables_filter {
            float: none;
            text-align: center;
        }
        .dataTables_filter input {
            width: 100%;
        }
        .dataTables_paginate {
            float: none;
            text-align: center;
        }
        .dataTables_info {
            float: none;
            text-align: center;
            margin-bottom: 15px;
        }
        .status-flow {
            gap: 10px;
            padding: 10px;
        }
        .status-connector {
            min-width: 15px;
        }
        .btn-approve, .btn-decline, .btn-review, .btn-submit {
            width: 100%;
            margin-bottom: 5px;
        }
        .table thead th {
            font-size: 8px;
            padding: 4px;
        }
        .table tbody td {
            font-size: 9px;
            padding: 4px;
        }
        .btn-view-doc, .btn-download-doc {
            font-size: 8px;
            padding: 1px 6px;
        }
    }

    @media (max-width: 480px) {
        .stat-cards-wrapper {
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }
        .stat-card {
            padding: 8px 10px;
            min-height: 50px;
        }
        .stat-card .stat-value {
            font-size: 14px;
        }
        .stat-card .stat-label {
            font-size: 7px;
        }
        .stat-card .stat-sub {
            font-size: 7px;
        }
        .stat-card .stat-icon {
            font-size: 16px;
        }
    }
</style>

<div class="pe-3 ps-3">
    <div class="row me-approval-outp-msg mx-0"></div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

    <div class="row mb-2">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="fw-semibold">Approval Management</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="<?=site_url();?>mydashboard">
                                    <i class="ti ti-home fs-5"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Approval Management</li>
                        </ol>
                    </nav>
                </div>
                <?php if(!empty($loan_id)): ?>
                <div>
                    <a href="<?=site_url('myapprovals'); ?>" class="btn-back">
                        <i class="ti ti-arrow-left me-1"></i> Back to Approval List
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if(empty($loan_id)): ?>
        <!-- DASHBOARD VIEW - Single Row Cards -->
        <div class="stat-cards-wrapper">
            <!-- Pending -->
            <div class="stat-card stat-pending" data-status="Pending">
                <div class="stat-info">
                    <div class="stat-label">Pending</div>
                    <div class="stat-value"><?= number_format($pendingCount); ?></div>
                    <div class="stat-sub">Awaiting submission</div>
                </div>
                <div class="stat-icon">
                    <i class="ti ti-clock"></i>
                </div>
            </div>

            <!-- Submitted -->
            <div class="stat-card stat-submitted" data-status="Submitted">
                <div class="stat-info">
                    <div class="stat-label">Submitted</div>
                    <div class="stat-value"><?= number_format($submittedCount); ?></div>
                    <div class="stat-sub">Awaiting review</div>
                </div>
                <div class="stat-icon">
                    <i class="ti ti-send"></i>
                </div>
            </div>

            <!-- Under Review -->
            <div class="stat-card stat-review" data-status="Under Review">
                <div class="stat-info">
                    <div class="stat-label">Under Review</div>
                    <div class="stat-value"><?= number_format($underReviewCount); ?></div>
                    <div class="stat-sub">Being reviewed</div>
                </div>
                <div class="stat-icon">
                    <i class="ti ti-clipboard"></i>
                </div>
            </div>

            <!-- Approved -->
            <div class="stat-card stat-approved" data-status="Approved">
                <div class="stat-info">
                    <div class="stat-label">Approved</div>
                    <div class="stat-value"><?= number_format($approvedCount); ?></div>
                    <div class="stat-sub">Approved loans</div>
                </div>
                <div class="stat-icon">
                    <i class="ti ti-check"></i>
                </div>
            </div>

            <!-- Declined -->
            <div class="stat-card stat-declined" data-status="Declined">
                <div class="stat-info">
                    <div class="stat-label">Declined</div>
                    <div class="stat-value"><?= number_format($declinedCount); ?></div>
                    <div class="stat-sub">Declined applications</div>
                </div>
                <div class="stat-icon">
                    <i class="ti ti-x"></i>
                </div>
            </div>
        </div>

        <!-- Loan Applications Table -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <i class="ti ti-list me-2" style="color: var(--gold-primary);"></i>
                        Loan Applications
                        <span class="ms-2 text-muted small" id="filterStatus">(All)</span>
                    </div>
                    <div>
                        <button class="btn btn-outline-secondary btn-sm" onclick="filterTable('All')">
                            <i class="ti ti-list"></i> Show All
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="approvalTable" class="table mb-0">
                        <thead>
                            <tr>
                                <th>Loan ID</th>
                                <th>Member</th>
                                <th>Member No.</th>
                                <th>Loan Type</th>
                                <th class="text-end">Loan Amount</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($allLoans as $loan): ?>
                            <tr>
                                <td><?= $loan['loan_id'] ?></td>
                                <td><?= htmlspecialchars($loan['first_name'] . ' ' . $loan['last_name']) ?></td>
                                <td><?= $loan['member_no'] ?></td>
                                <td><?= $loan['loan_type'] ?></td>
                                <td class="text-end">₱<?= number_format($loan['loan_amount'], 2) ?></td>
                                <td>
                                    <span class="status-pill 
                                        <?= $loan['approval_status'] == 'Pending' ? 'status-pending' : 
                                          ($loan['approval_status'] == 'Submitted' ? 'status-submitted' : 
                                          ($loan['approval_status'] == 'Under Review' ? 'status-review' : 
                                          ($loan['approval_status'] == 'Approved' ? 'status-approved' : 'status-declined'))) ?>">
                                        <?= $loan['approval_status'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('myapprovals?loan_id='.$loan['loan_id']); ?>" class="btn btn-primary btn-sm">
                                        <i class="ti ti-eye"></i> 
                                        <?= ($loan['approval_status'] == 'Pending' || $loan['approval_status'] == 'Submitted' || $loan['approval_status'] == 'Under Review') ? 'Review' : 'View' ?>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- DETAIL VIEW -->
        <?php if($loan_data): ?>
        <!-- Loan Summary -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted small text-uppercase mb-1">Member</div>
                        <h5 class="mb-0"><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></h5>
                        <small class="text-muted">Member #: <?= $member['member_no']; ?></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted small text-uppercase mb-1">Loan Amount</div>
                        <h5 class="mb-0 text-success">₱<?= number_format((float)$loan_data['loan_amount'], 2); ?></h5>
                        <small class="text-muted"><?= $loan_data['loan_type']; ?></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted small text-uppercase mb-1">Outstanding Balance</div>
                        <h5 class="mb-0 text-danger">₱<?= number_format($loan_data['outstanding_balance'], 2); ?></h5>
                        <small class="text-muted">Remaining balance</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted small text-uppercase mb-1">Approval Status</div>
                        <h5 class="mb-0">
                            <span class="status-pill 
                                <?= $loan_data['approval_status'] == 'Pending' ? 'status-pending' : 
                                  ($loan_data['approval_status'] == 'Submitted' ? 'status-submitted' : 
                                  ($loan_data['approval_status'] == 'Under Review' ? 'status-review' : 
                                  ($loan_data['approval_status'] == 'Approved' ? 'status-approved' : 'status-declined'))) ?>">
                                <?= $loan_data['approval_status'] ?>
                            </span>
                        </h5>
                        <small class="text-muted">Term: <?= (int)$loan_data['term_months']; ?> months</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Workflow -->
        <div class="card">
            <div class="card-header">
                <i class="ti ti-timeline me-2" style="color: var(--gold-primary);"></i>
                Approval Process
            </div>
            <div class="card-body">
                <!-- Status Flow -->
                <div class="status-flow">
                    <div class="status-step <?= in_array($loan_data['approval_status'], ['Submitted', 'Under Review', 'Approved']) ? 'completed' : ($loan_data['approval_status'] == 'Pending' ? 'active' : '') ?>">
                        <span class="step-icon"><i class="ti ti-file"></i></span>
                        <span>Pending</span>
                    </div>
                    <div class="status-connector <?= in_array($loan_data['approval_status'], ['Submitted', 'Under Review', 'Approved']) ? 'completed' : '' ?>"></div>
                    
                    <div class="status-step <?= in_array($loan_data['approval_status'], ['Submitted', 'Under Review', 'Approved']) ? 'active' : '' ?>">
                        <span class="step-icon"><i class="ti ti-send"></i></span>
                        <span>Submitted</span>
                    </div>
                    <div class="status-connector <?= in_array($loan_data['approval_status'], ['Under Review', 'Approved']) ? 'completed' : ($loan_data['approval_status'] == 'Submitted' ? 'active' : '') ?>"></div>
                    
                    <div class="status-step <?= $loan_data['approval_status'] == 'Under Review' ? 'active' : ($loan_data['approval_status'] == 'Approved' ? 'completed' : '') ?>">
                        <span class="step-icon"><i class="ti ti-clipboard"></i></span>
                        <span>Review</span>
                    </div>
                    <div class="status-connector <?= $loan_data['approval_status'] == 'Approved' ? 'completed' : ($loan_data['approval_status'] == 'Under Review' ? 'active' : '') ?>"></div>
                    
                    <div class="status-step <?= $loan_data['approval_status'] == 'Approved' ? 'completed' : ($loan_data['approval_status'] == 'Declined' ? 'declined' : '') ?>">
                        <span class="step-icon">
                            <?php if($loan_data['approval_status'] == 'Approved'): ?>
                                <i class="ti ti-check"></i>
                            <?php elseif($loan_data['approval_status'] == 'Declined'): ?>
                                <i class="ti ti-x"></i>
                            <?php else: ?>
                                <i class="ti ti-flag"></i>
                            <?php endif; ?>
                        </span>
                        <span><?= $loan_data['approval_status'] == 'Declined' ? 'Declined' : 'Decision' ?></span>
                    </div>
                </div>

                <!-- Approval Actions & Loan Info -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="approval-actions">
                            <h6 class="fw-semibold mb-3" style="color: var(--navy-dark);">
                                <i class="ti ti-settings me-2" style="color: var(--gold-primary);"></i>
                                Approval Actions
                            </h6>
                            
                            <?php if($loan_data['approval_status'] == 'Pending'): ?>
                                <form id="submitApprovalForm" class="mb-2">
                                    <input type="hidden" name="loan_id" value="<?= $loan_id ?>">
                                    <div class="mb-2">
                                        <label class="form-label">Remarks</label>
                                        <textarea name="remarks" class="form-control form-control-sm" placeholder="Optional remarks for submission..." rows="2"></textarea>
                                    </div>
                                    <button type="submit" class="btn-submit w-100">
                                        <i class="ti ti-send me-1"></i> Submit for Approval
                                    </button>
                                </form>
                                
                            <?php elseif($loan_data['approval_status'] == 'Submitted'): ?>
                                <form id="reviewApprovalForm" class="mb-2">
                                    <input type="hidden" name="loan_id" value="<?= $loan_id ?>">
                                    <div class="mb-2">
                                        <label class="form-label">Review Remarks</label>
                                        <textarea name="remarks" class="form-control form-control-sm" placeholder="Enter review remarks..." rows="2"></textarea>
                                    </div>
                                    <button type="submit" class="btn-review w-100">
                                        <i class="ti ti-clipboard me-1"></i> Start Review
                                    </button>
                                </form>
                                
                            <?php elseif($loan_data['approval_status'] == 'Under Review'): ?>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <form id="approveLoanForm">
                                            <input type="hidden" name="loan_id" value="<?= $loan_id ?>">
                                            <div class="mb-2">
                                                <textarea name="remarks" class="form-control form-control-sm" placeholder="Approval remarks..." rows="2"></textarea>
                                            </div>
                                            <button type="submit" class="btn-approve w-100">
                                                <i class="ti ti-check me-1"></i> Approve
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-6">
                                        <form id="declineLoanForm">
                                            <input type="hidden" name="loan_id" value="<?= $loan_id ?>">
                                            <div class="mb-2">
                                                <textarea name="remarks" class="form-control form-control-sm" placeholder="Decline reason..." rows="2"></textarea>
                                            </div>
                                            <button type="submit" class="btn-decline w-100">
                                                <i class="ti ti-x me-1"></i> Decline
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <form id="reviseLoanForm">
                                            <input type="hidden" name="loan_id" value="<?= $loan_id ?>">
                                            <div class="mb-2">
                                                <textarea name="remarks" class="form-control form-control-sm" placeholder="Revision request details..." rows="2"></textarea>
                                            </div>
                                            <button type="submit" class="btn-review w-100">
                                                <i class="ti ti-edit me-1"></i> Request Revision
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                            <?php elseif($loan_data['approval_status'] == 'Approved'): ?>
                                <div class="alert alert-success mb-0">
                                    <i class="ti ti-check-circle"></i> 
                                    This loan has been approved. No further actions required.
                                    <?php if(!empty($loan_data['approval_by'])): ?>
                                        <br><small class="text-muted">Approved by: <?= htmlspecialchars($loan_data['approval_by']) ?></small>
                                    <?php endif; ?>
                                    <?php if(!empty($loan_data['approval_at'])): ?>
                                        <br><small class="text-muted">Approved on: <?= date('F d, Y h:i A', strtotime($loan_data['approval_at'])) ?></small>
                                    <?php endif; ?>
                                </div>
                                
                            <?php elseif($loan_data['approval_status'] == 'Declined'): ?>
                                <div class="alert alert-danger mb-0">
                                    <i class="ti ti-x-circle"></i> 
                                    This loan has been declined. No further actions required.
                                    <?php if(!empty($loan_data['approval_remarks'])): ?>
                                        <br><small class="text-muted">Reason: <?= htmlspecialchars($loan_data['approval_remarks']) ?></small>
                                    <?php endif; ?>
                                    <?php if(!empty($loan_data['approval_by'])): ?>
                                        <br><small class="text-muted">Declined by: <?= htmlspecialchars($loan_data['approval_by']) ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- Loan Information -->
                        <div class="card">
                            <div class="card-header">Loan Information</div>
                            <div class="card-body">
                                <p><strong>Loan Type:</strong> <?= $loan_data['loan_type'] ?></p>
                                <p><strong>Loan Amount:</strong> ₱<?= number_format((float)$loan_data['loan_amount'], 2) ?></p>
                                <p><strong>Interest Rate:</strong> <?= number_format((float)$loan_data['interest_rate'], 2) ?>%</p>
                                <p><strong>Term:</strong> <?= (int)$loan_data['term_months'] ?> months</p>
                                <p><strong>Start Date:</strong> <?= date('F d, Y', strtotime($loan_data['start_date'])) ?></p>
                                <p><strong>Maturity Date:</strong> <?= date('F d, Y', strtotime($loan_data['maturity_date'])) ?></p>
                                <p><strong>Co-maker:</strong> <?= !empty($loan_data['loan_comakers']) ? 'Yes' : 'None' ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amortization Schedule -->
                <div class="mt-4">
                    <h6 class="fw-semibold mb-3" style="color: var(--navy-dark);">
                        <i class="ti ti-chart-bar me-2" style="color: var(--gold-primary);"></i>
                        Amortization Schedule 
                        <span class="text-muted small fw-normal">(Quick Review)</span>
                    </h6>
                    
                    <?php if(!empty($amortizationSched)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0" id="amortizationTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Payment Date</th>
                                        <th class="text-end">Beginning Bal.</th>
                                        <th class="text-end">Interest</th>
                                        <th class="text-end">Principal</th>
                                        <th class="text-end">Payment</th>
                                        <th class="text-end">Ending Bal.</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $displayCount = 0;
                                    $totalRows = count($amortizationSched);
                                    $showAll = $totalRows <= 10;
                                    $totalPaid = 0;
                                    $totalUnpaid = 0;
                                    ?>
                                    
                                    <?php foreach($amortizationSched as $index => $row): 
                                        $isPaid = isset($row['payment_status']) && strtolower($row['payment_status']) === 'paid';
                                        $displayCount++;
                                        
                                        if($isPaid) {
                                            $totalPaid++;
                                        } else {
                                            $totalUnpaid++;
                                        }
                                        
                                        if($displayCount > 10 && !$showAll) continue;
                                    ?>
                                        <tr class="<?= $isPaid ? 'table-success' : ''; ?>">
                                            <td class="text-center"><?= (int)$row['period']; ?></td>
                                            <td><?= date('m/d/Y', strtotime($row['payment_date'])); ?></td>
                                            <td class="text-end">₱<?= number_format((float)$row['beginning_balance'], 2); ?></td>
                                            <td class="text-end">₱<?= number_format((float)$row['interest'], 2); ?></td>
                                            <td class="text-end">₱<?= number_format((float)$row['principal'], 2); ?></td>
                                            <td class="text-end">₱<?= number_format((float)$row['payment'], 2); ?></td>
                                            <td class="text-end">₱<?= number_format((float)$row['ending_balance'], 2); ?></td>
                                            <td class="text-center">
                                                <?php if($isPaid): ?>
                                                    <span class="badge badge-paid">Paid</span>
                                                <?php else: ?>
                                                    <span class="badge badge-unpaid">Unpaid</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    
                                    <?php if($totalRows > 10): ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-2 small">
                                                <i class="ti ti-info-circle"></i> Showing first 10 of <?= $totalRows ?> periods. 
                                                <a href="<?= site_url('myloanprofile?meaction=MAIN&loan_id='.$loan_id); ?>" class="text-decoration-none">
                                                    View full schedule →
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Quick Summary -->
                        <div class="d-flex gap-3 mt-2 small text-muted flex-wrap">
                            <span><i class="ti ti-check-circle text-success"></i> Paid: <?= $totalPaid ?></span>
                            <span><i class="ti ti-clock text-warning"></i> Unpaid: <?= $totalUnpaid ?></span>
                            <span><i class="ti ti-file"></i> Total: <?= $totalRows ?> periods</span>
                            <?php if(isset($loan_data['loan_amount']) && isset($loan_data['outstanding_balance'])): ?>
                                <span class="text-success">Total Paid: ₱<?= number_format($loan_data['loan_amount'] - $loan_data['outstanding_balance'], 2) ?></span>
                                <span class="text-danger">Remaining: ₱<?= number_format($loan_data['outstanding_balance'], 2) ?></span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3 text-muted border rounded-3">
                            <i class="ti ti-info-circle fs-4 mb-2 d-block"></i>
                            <p class="mb-0">No amortization schedule generated yet.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Supporting Documents - Fetch from tbl_member_documents -->
                <div class="mt-4">
                    <h6 class="fw-semibold mb-3" style="color: var(--navy-dark);">
                        <i class="ti ti-files me-2" style="color: var(--gold-primary);"></i>
                        Member Documents
                        <span class="text-muted small fw-normal">(<?= !empty($member_documents) ? count($member_documents) : 0 ?> documents)</span>
                    </h6>
                    
                    <?php if(!empty($member_documents)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Document Name</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Upload Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($member_documents as $doc): 
                                        $file_url = base_url($doc['document_path']);
                                        $file_icon = get_file_icon($doc['document_path']);
                                        $file_size = format_file_size($doc['file_size']);
                                        $doc_type = !empty($doc['file_type']) ? strtoupper($doc['file_type']) : get_file_type_label($doc['document_path']);
                                        $is_image = is_image_file($doc['document_path']);
                                        $is_pdf = is_pdf_file($doc['document_path']);
                                        
                                        // Determine badge class
                                        $badge_class = 'badge-other';
                                        if($is_pdf) $badge_class = 'badge-pdf';
                                        elseif($is_image) $badge_class = 'badge-image';
                                        elseif(in_array($doc_type, ['DOC', 'DOCX'])) $badge_class = 'badge-doc';
                                        elseif($doc_type == 'TXT') $badge_class = 'badge-txt';
                                    ?>
                                    <tr>
                                        <td>
                                            <i class="<?= $file_icon ?> me-2" style="color: var(--gold-primary);"></i>
                                            <?= htmlspecialchars($doc['document_name']) ?>
                                        </td>
                                        <td><span class="badge <?= $badge_class ?>"><?= $doc_type ?></span></td>
                                        <td><?= $file_size ?></td>
                                        <td><?= date('M d, Y', strtotime($doc['upload_date'])) ?></td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="<?= $file_url ?>" target="_blank" class="btn-view-doc">
                                                    <i class="ti ti-eye"></i> View
                                                </a>
                                                <a href="<?= $file_url ?>" download class="btn-download-doc">
                                                    <i class="ti ti-download"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3 text-muted border rounded-3">
                            <i class="ti ti-files fs-4 mb-2 d-block"></i>
                            <p class="mb-0">No documents uploaded for this member yet.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Approval History -->
                <div class="mt-4">
                    <h6 class="fw-semibold mb-3" style="color: var(--navy-dark);">
                        <i class="ti ti-history me-2" style="color: var(--gold-primary);"></i>
                        Approval History
                    </h6>
                    
                    <?php if(!empty($approval_logs)): ?>
                        <div class="approval-timeline">
                            <?php foreach($approval_logs as $log): ?>
                                <div class="timeline-item <?= $log['action'] == 'APPROVE' ? 'completed' : ($log['action'] == 'DECLINE' ? 'declined' : '') ?>">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                        <div>
                                            <strong><?= ucwords(strtolower($log['action'])) ?></strong>
                                            <span class="timeline-badge badge-<?= strtolower($log['action']) ?>">
                                                <?= $log['status_from'] ?> → <?= $log['status_to'] ?>
                                            </span>
                                            <?php if(!empty($log['remarks'])): ?>
                                                <p class="mb-0 small text-muted mt-1"><?= htmlspecialchars($log['remarks']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted d-block"><?= date('M d, Y h:i A', strtotime($log['created_at'])) ?></small>
                                            <small class="text-muted"><i class="ti ti-user"></i> <?= htmlspecialchars($log['created_by']) ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3 text-muted">
                            <i class="ti ti-timeline fs-2 mb-2 d-block"></i>
                            No approval history yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-danger">
            <i class="ti ti-alert-triangle"></i> Loan not found.
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Loading Overlay -->
<div id="uploadOverlay">
    <div class="upload-spinner">
        <i class="ti ti-loader"></i>
        <p class="mt-2 mb-0">Processing...</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url('assets/js/approval/approval.js?v=1');?>"></script>

<script>
$(document).ready(function () {
    // Initialize DataTable
    if ($('#approvalTable').length && !$.fn.DataTable.isDataTable('#approvalTable')) {
        $('#approvalTable').DataTable({
            pageLength: 10,
            lengthChange: true,
            order: [[0, 'desc']],
            language: { 
                search: "Search:",
                info: "Showing _START_ to _END_ of _TOTAL_ applications",
                infoEmpty: "No applications found",
                lengthMenu: "Show _MENU_ applications"
            },
            dom: 'frtip'
        });
    }
    
    // Click handlers for stat cards
    $('.stat-card').on('click', function() {
        var status = $(this).data('status');
        filterTable(status);
        
        // Add active class
        $('.stat-card').removeClass('active-filter');
        $(this).addClass('active-filter');
    });
    
    // Double click to show all
    $('.stat-card').on('dblclick', function() {
        filterTable('All');
        $('.stat-card').removeClass('active-filter');
    });
});

function filterTable(status) {
    var table = $('#approvalTable').DataTable();
    
    // Clear existing filter
    table.column(5).search('').draw();
    
    // Apply filter if not 'All'
    if(status !== 'All') {
        table.column(5).search(status).draw();
        $('#filterStatus').text('(' + status + ')');
    } else {
        $('#filterStatus').text('(All)');
    }
}
</script>

<?php echo view('templates/myfooter.php'); ?>