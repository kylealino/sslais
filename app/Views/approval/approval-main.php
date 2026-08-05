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

    .stat-cards-wrapper {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
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
    .stat-risk .stat-value { color: #7c3aed; }
    .stat-credit .stat-value { color: #1e40af; }
    .stat-decision .stat-value { color: #4338ca; }
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

    .btn-secondary {
        background: var(--gray-400);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 8px 20px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .btn-secondary:hover {
        background: var(--gray-400);
        color: white;
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
    .status-risk { background: #ede9fe; color: #7c3aed; }
    .status-credit { background: #dbeafe; color: #1e40af; }
    .status-decision { background: #e0e7ff; color: #4338ca; }
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

    /* Status Flow */
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

    .form-control-sm {
        font-size: 12px;
        padding: 4px 8px;
    }

    .badge {
        padding: 3px 8px;
        font-size: 9px;
        font-weight: 600;
        border-radius: 20px;
    }

    .badge-paid { background: #d1fae5; color: #065f46; }
    .badge-unpaid { background: #fef3c7; color: #d97706; }
    
    .badge-risk-high { background: #fee2e2; color: #dc2626; }
    .badge-risk-cautionary { background: #fef3c7; color: #d97706; }
    .badge-risk-moderate { background: #fde68a; color: #92400e; }
    .badge-risk-low { background: #d1fae5; color: #065f46; }

    .approval-actions {
        background: var(--gray-50);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid var(--gray-200);
    }

    .timeline-item {
        position: relative;
        padding-left: 20px;
        margin-bottom: 20px;
        border-left: 2px solid var(--gray-200);
    }

    .timeline-item:last-child {
        border-left: none;
    }

    .timeline-item.completed {
        border-left-color: var(--success);
    }

    .timeline-item.declined {
        border-left-color: var(--danger);
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

    /* Risk Assessment Styles */
    .quant-input {
        background: #fff;
        border: 1.5px solid var(--gray-200);
        border-radius: 8px;
        padding: 4px 8px;
        width: 100%;
        font-size: 13px;
        transition: all 0.2s;
    }

    .quant-input:focus {
        border-color: var(--gold-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .num-rating-display {
        font-weight: 700;
        font-size: 16px;
        text-align: center;
        padding: 4px 8px;
        border-radius: 8px;
        background: var(--gray-50);
        min-width: 40px;
        display: inline-block;
        width: 100%;
    }

    .num-rating-display.high {
        color: #dc2626;
        background: #fee2e2;
    }

    .num-rating-display.medium {
        color: #d97706;
        background: #fef3c7;
    }

    .num-rating-display.low {
        color: #065f46;
        background: #d1fae5;
    }

    .num-rating-display.zero {
        color: var(--gray-500);
        background: var(--gray-100);
    }

    .result-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        padding: 15px;
    }

    .result-value {
        font-size: 28px;
        font-weight: 700;
    }

    .result-value.high-risk { color: #dc2626; }
    .result-value.cautionary { color: #d97706; }
    .result-value.moderate-risk { color: #92400e; }
    .result-value.low-risk { color: #065f46; }

    .risk-badge {
        display: inline-block;
        padding: 4px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
    }

    .risk-badge.high-risk { background: #fee2e2; color: #dc2626; }
    .risk-badge.cautionary { background: #fef3c7; color: #d97706; }
    .risk-badge.moderate-risk { background: #fde68a; color: #92400e; }
    .risk-badge.low-risk { background: #d1fae5; color: #065f46; }

    /* Committee Approval Styles */
    .committee-check {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .committee-check:checked {
        accent-color: var(--success);
    }

    #committeeStatus {
        background: var(--gray-50);
        padding: 10px 15px;
        border-radius: 8px;
        border: 1px solid var(--gray-200);
    }

    /* Credit Committee Styles */
    .credit-committee-check {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .credit-committee-check:checked {
        accent-color: var(--info);
    }

    #creditCommitteeStatus {
        background: var(--gray-50);
        padding: 10px 15px;
        border-radius: 8px;
        border: 1px solid var(--gray-200);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .stat-cards-wrapper {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stat-cards-wrapper {
            grid-template-columns: repeat(2, 1fr);
        }
        .card-body {
            padding: 16px;
        }
        .status-flow {
            gap: 10px;
            padding: 10px;
        }
        .status-connector {
            min-width: 15px;
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
        <!-- ============================================ -->
        <!-- DASHBOARD VIEW -->
        <!-- ============================================ -->
        <div class="stat-cards-wrapper">
            <div class="stat-card stat-pending" data-status="Pending">
                <div class="stat-info">
                    <div class="stat-label">Pending</div>
                    <div class="stat-value"><?= number_format($stats['pending'] ?? 0); ?></div>
                    <div class="stat-sub">Awaiting submission</div>
                </div>
                <div class="stat-icon"><i class="ti ti-clock"></i></div>
            </div>

            <div class="stat-card stat-risk" data-status="Risk Assessment">
                <div class="stat-info">
                    <div class="stat-label">Risk Assessment</div>
                    <div class="stat-value"><?= number_format($stats['risk_assessment'] ?? 0); ?></div>
                    <div class="stat-sub">Under risk review</div>
                </div>
                <div class="stat-icon"><i class="ti ti-shield"></i></div>
            </div>

            <div class="stat-card stat-credit" data-status="Credit Assessment">
                <div class="stat-info">
                    <div class="stat-label">Credit Assessment</div>
                    <div class="stat-value"><?= number_format($stats['credit_assessment'] ?? 0); ?></div>
                    <div class="stat-sub">Under credit review</div>
                </div>
                <div class="stat-icon"><i class="ti ti-credit-card"></i></div>
            </div>

            <div class="stat-card stat-decision" data-status="Decision">
                <div class="stat-info">
                    <div class="stat-label">Decision</div>
                    <div class="stat-value"><?= number_format($stats['decision'] ?? 0); ?></div>
                    <div class="stat-sub">Ready for decision</div>
                </div>
                <div class="stat-icon"><i class="ti ti-flag"></i></div>
            </div>

            <div class="stat-card stat-approved" data-status="Approved">
                <div class="stat-info">
                    <div class="stat-label">Approved</div>
                    <div class="stat-value"><?= number_format($stats['approved'] ?? 0); ?></div>
                    <div class="stat-sub">Approved loans</div>
                </div>
                <div class="stat-icon"><i class="ti ti-check"></i></div>
            </div>

            <div class="stat-card stat-declined" data-status="Declined">
                <div class="stat-info">
                    <div class="stat-label">Declined</div>
                    <div class="stat-value"><?= number_format($stats['declined'] ?? 0); ?></div>
                    <div class="stat-sub">Declined applications</div>
                </div>
                <div class="stat-icon"><i class="ti ti-x"></i></div>
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
                                <th>Stage</th>
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
                                        <?= $loan['workflow_stage'] == 'Pending' ? 'status-pending' : 
                                          ($loan['workflow_stage'] == 'Risk Assessment' ? 'status-risk' : 
                                          ($loan['workflow_stage'] == 'Credit Assessment' ? 'status-credit' : 
                                          ($loan['workflow_stage'] == 'Decision' ? 'status-decision' : 
                                          ($loan['workflow_stage'] == 'Completed' ? 'status-approved' : 'status-declined')))) ?>">
                                        <?= $loan['workflow_stage'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-pill 
                                        <?= $loan['approval_status'] == 'Pending' ? 'status-pending' : 
                                          ($loan['approval_status'] == 'Submitted' ? 'status-risk' : 
                                          ($loan['approval_status'] == 'Approved' ? 'status-approved' : 'status-declined')) ?>">
                                        <?= $loan['approval_status'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('myapprovals?loan_id='.$loan['loan_id']); ?>" class="btn btn-primary btn-sm">
                                        <i class="ti ti-eye"></i> 
                                        <?= ($loan['workflow_stage'] != 'Completed' && $loan['workflow_stage'] != 'Declined') ? 'Review' : 'View' ?>
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
        <!-- ============================================ -->
        <!-- DETAIL VIEW -->
        <!-- ============================================ -->
        <?php if($loan_data): ?>
        
        <!-- Loan Summary Cards -->
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
                        <div class="text-muted small text-uppercase mb-1">Current Stage</div>
                        <h5 class="mb-0">
                            <span class="status-pill 
                                <?= $loan_data['workflow_stage'] == 'Pending' ? 'status-pending' : 
                                  ($loan_data['workflow_stage'] == 'Risk Assessment' ? 'status-risk' : 
                                  ($loan_data['workflow_stage'] == 'Credit Assessment' ? 'status-credit' : 
                                  ($loan_data['workflow_stage'] == 'Decision' ? 'status-decision' : 
                                  ($loan_data['workflow_stage'] == 'Completed' ? 'status-approved' : 'status-declined')))) ?>">
                                <?= $loan_data['workflow_stage'] ?>
                            </span>
                        </h5>
                        <small class="text-muted">Term: <?= (int)$loan_data['term_months']; ?> months</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Flow -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="ti ti-timeline me-2" style="color: var(--gold-primary);"></i>
                Approval Process
            </div>
            <div class="card-body">
                <div class="status-flow">
                    <div class="status-step <?= in_array($loan_data['workflow_stage'], ['Risk Assessment', 'Credit Assessment', 'Decision', 'Completed']) ? 'completed' : ($loan_data['workflow_stage'] == 'Pending' ? 'active' : '') ?>">
                        <span class="step-icon"><i class="ti ti-file"></i></span>
                        <span>Pending</span>
                    </div>
                    <div class="status-connector <?= in_array($loan_data['workflow_stage'], ['Risk Assessment', 'Credit Assessment', 'Decision', 'Completed']) ? 'completed' : '' ?>"></div>
                    
                    <div class="status-step <?= in_array($loan_data['workflow_stage'], ['Risk Assessment', 'Credit Assessment', 'Decision', 'Completed']) ? 'active' : '' ?>">
                        <span class="step-icon"><i class="ti ti-shield"></i></span>
                        <span>Risk Assessment</span>
                    </div>
                    <div class="status-connector <?= in_array($loan_data['workflow_stage'], ['Credit Assessment', 'Decision', 'Completed']) ? 'completed' : ($loan_data['workflow_stage'] == 'Risk Assessment' ? 'active' : '') ?>"></div>
                    
                    <div class="status-step <?= in_array($loan_data['workflow_stage'], ['Credit Assessment', 'Decision', 'Completed']) ? 'active' : '' ?>">
                        <span class="step-icon"><i class="ti ti-credit-card"></i></span>
                        <span>Credit Assessment</span>
                    </div>
                    <div class="status-connector <?= in_array($loan_data['workflow_stage'], ['Decision', 'Completed']) ? 'completed' : ($loan_data['workflow_stage'] == 'Credit Assessment' ? 'active' : '') ?>"></div>
                    
                    <div class="status-step <?= $loan_data['workflow_stage'] == 'Decision' ? 'active' : ($loan_data['workflow_stage'] == 'Completed' ? 'completed' : '') ?>">
                        <span class="step-icon">
                            <?php if($loan_data['approval_status'] == 'Approved'): ?>
                                <i class="ti ti-check"></i>
                            <?php elseif($loan_data['approval_status'] == 'Declined'): ?>
                                <i class="ti ti-x"></i>
                            <?php else: ?>
                                <i class="ti ti-flag"></i>
                            <?php endif; ?>
                        </span>
                        <span>Decision</span>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- STAGE 1: PENDING - Submit for Approval -->
                <!-- ============================================ -->
                <?php if($loan_data['workflow_stage'] == 'Pending'): ?>
                <div class="approval-actions">
                    <h6 class="fw-semibold mb-3" style="color: var(--navy-dark);">
                        <i class="ti ti-send me-2" style="color: var(--gold-primary);"></i>
                        Submit for Approval
                    </h6>
                    <form id="submitApprovalForm">
                        <input type="hidden" name="loan_id" value="<?= $loan_id ?>">
                        <div class="mb-2">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control form-control-sm" placeholder="Optional remarks for submission..." rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn-submit w-100">
                            <i class="ti ti-send me-1"></i> Submit for Risk Assessment
                        </button>
                    </form>
                </div>

                <!-- ============================================ -->
                <!-- STAGE 2: RISK ASSESSMENT -->
                <!-- ============================================ -->
                <?php elseif($loan_data['workflow_stage'] == 'Risk Assessment'): ?>
                <div class="card mb-3">
                    <div class="card-header" style="background: #fef7e0;">
                        <i class="ti ti-shield me-2" style="color: var(--gold-primary);"></i>
                        INDIVIDUAL CREDIT RISK RATING DETERMINATION
                        <small class="text-muted d-block">To be filled-out by SSLAI Risk Management Committee</small>
                    </div>
                    <div class="card-body">
                        <form id="riskAssessmentForm">
                            <input type="hidden" name="loan_id" value="<?= $loan_id ?>">
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="riskTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 35%;">Risk Components</th>
                                            <th style="width: 35%;">Quantitative Rating<br><small class="text-muted">(User Input)</small></th>
                                            <th style="width: 30%;">Numerical Rating<br><small class="text-muted">(Auto-Calculated)</small></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>1. Leave Credits</strong>
                                                <small class="d-block text-muted">(Vacation + Sick Leave)</small>
                                            </td>
                                            <td>
                                                <input type="number" name="leave_credits_quant" class="form-control form-control-sm quant-input" 
                                                       step="0.5" placeholder="Enter days" data-field="leave_credits" required>
                                            </td>
                                            <td>
                                                <span class="num-rating-display" id="leave_credits_num">-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>2. Capital Contribution</strong>
                                                <small class="d-block text-muted">Current member's contribution/investment</small>
                                            </td>
                                            <td>
                                                <input type="number" name="capital_contribution_quant" class="form-control form-control-sm quant-input" 
                                                       step="0.01" placeholder="Enter amount" data-field="capital_contribution" required>
                                            </td>
                                            <td>
                                                <span class="num-rating-display" id="capital_contribution_num">-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>3. Take-home pay (Basic Salary)</strong>
                                            </td>
                                            <td>
                                                <input type="number" name="take_home_pay_quant" class="form-control form-control-sm quant-input" 
                                                       step="0.01" placeholder="Enter amount" data-field="take_home_pay" required>
                                            </td>
                                            <td>
                                                <span class="num-rating-display" id="take_home_pay_num">-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>4. Total amount of SSLAI existing loans</strong>
                                            </td>
                                            <td>
                                                <input type="number" name="existing_sslai_loans_quant" class="form-control form-control-sm quant-input" 
                                                       step="0.01" placeholder="Enter amount" data-field="existing_sslai_loans" required>
                                            </td>
                                            <td>
                                                <span class="num-rating-display" id="existing_sslai_loans_num">-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>5. Number of years in service</strong>
                                            </td>
                                            <td>
                                                <input type="number" name="years_in_service_quant" class="form-control form-control-sm quant-input" 
                                                       step="0.5" placeholder="Enter years" data-field="years_in_service" required>
                                            </td>
                                            <td>
                                                <span class="num-rating-display" id="years_in_service_num">-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>6. Existing loans from other providers</strong>
                                                <small class="d-block text-muted">(LandBank, GSIS, PAG-IBIG, etc.)</small>
                                            </td>
                                            <td>
                                                <input type="number" name="other_loans_quant" class="form-control form-control-sm quant-input" 
                                                       step="0.01" placeholder="Enter amount" data-field="other_loans" required>
                                            </td>
                                            <td>
                                                <span class="num-rating-display" id="other_loans_num">-</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Other Factors -->
                            <h6 class="fw-semibold mt-4">7. Other Factors</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Health Condition</label>
                                        <select name="health_condition" class="form-select form-select-sm other-factor" required>
                                            <option value="">Select</option>
                                            <option value="good">Good health condition</option>
                                            <option value="mild">Mild health conditions (diabetes, etc.)</option>
                                            <option value="severe">Severe health conditions (cancer, heart, etc.)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Civil Status</label>
                                        <select name="civil_status" class="form-select form-select-sm" required>
                                            <option value="">Select</option>
                                            <option value="single">Single</option>
                                            <option value="married">Married</option>
                                            <option value="separated">Separated</option>
                                            <option value="widowed">Widowed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Age</label>
                                        <input type="number" name="age" class="form-control form-control-sm other-factor" 
                                               min="18" max="100" placeholder="Enter age" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Number of Dependents</label>
                                        <input type="number" name="dependents" class="form-control form-control-sm other-factor" 
                                               min="0" placeholder="0" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Delinquency Months</label>
                                        <input type="number" name="delinquency_months" class="form-control form-control-sm other-factor" 
                                               min="0" placeholder="0" required>
                                        <small class="text-muted">0 = No delinquency</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Risk Assessment Results -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card result-card">
                                        <div class="card-body">
                                            <h6 class="fw-semibold">Risk Assessment Results</h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Total Numerical Score</label>
                                                        <div class="result-value" id="total_score">0</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Qualitative Risk Rating</label>
                                                        <div class="risk-badge" id="qualitative_rating">-</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Descriptive Risk Rating</label>
                                                        <div class="risk-badge" id="descriptive_rating">-</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Assessed By</label>
                                                        <input type="text" name="assessed_by" class="form-control" value="<?= $cuser ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ============================================ -->
                            <!-- RISK COMMITTEE APPROVAL SECTION -->
                            <!-- ============================================ -->
                            <div class="card mt-4">
                                <div class="card-header" style="background: #ede9fe;">
                                    <i class="ti ti-users me-2" style="color: #7c3aed;"></i>
                                    Risk Management Committee Approval
                                    <span class="ms-2 text-muted small fw-normal">(Check all members who approve)</span>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle"></i> 
                                        Please review the risk assessment above. Check all committee members who approve this assessment.
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input committee-check" type="checkbox" name="committee_michael" id="committee_michael" value="1">
                                                <label class="form-check-label fw-semibold" for="committee_michael">
                                                    Michael Serafico <span class="badge bg-primary">Chair</span>
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input committee-check" type="checkbox" name="committee_rosela" id="committee_rosela" value="1">
                                                <label class="form-check-label fw-semibold" for="committee_rosela">
                                                    Rosela M. Gomez <span class="badge bg-secondary">Member</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input committee-check" type="checkbox" name="committee_gerry" id="committee_gerry" value="1">
                                                <label class="form-check-label fw-semibold" for="committee_gerry">
                                                    Gerry Boy Garinggan <span class="badge bg-secondary">Member</span>
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input committee-check" type="checkbox" name="committee_sharra" id="committee_sharra" value="1">
                                                <label class="form-check-label fw-semibold" for="committee_sharra">
                                                    Sharra A. Taywan <span class="badge bg-secondary">Member</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3" id="committeeStatus">
                                        <span class="text-muted">Selected: <span id="selectedCount">0</span> of 4 members</span>
                                        <div class="progress mt-1" style="height: 6px;">
                                            <div class="progress-bar bg-success" id="committeeProgress" role="progressbar" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label class="form-label">Remarks / Recommendations</label>
                                <textarea name="remarks" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
                            </div>

                            <button type="submit" class="btn-review mt-3" id="submitRiskBtn" disabled>
                                <i class="ti ti-save me-1"></i> Select Committee Members First
                            </button>
                        </form>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- STAGE 3: CREDIT ASSESSMENT -->
                <!-- ============================================ -->
                <?php elseif($loan_data['workflow_stage'] == 'Credit Assessment'): ?>
                <div class="card mb-3">
                    <div class="card-header" style="background: #dbeafe;">
                        <i class="ti ti-credit-card me-2" style="color: #1e40af;"></i>
                        Credit Assessment - Computation of Loanable Amount
                        <small class="text-muted d-block">To be filled-out by Credit Committee</small>
                    </div>
                    <div class="card-body">
                        <form id="creditAssessmentForm">
                            <input type="hidden" name="loan_id" value="<?= $loan_id ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="member_name" class="form-control" 
                                            value="<?= $member['first_name'] . ' ' . $member['last_name'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Annual Salary</label>
                                        <input type="number" name="annual_salary" class="form-control" step="0.01" placeholder="0.00" required>
                                    </div>
                                </div>
                            </div>
                            
                            <h6 class="fw-semibold mt-3">Loan Balances</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label class="form-label">RL</label>
                                        <input type="number" name="rl_balance" class="form-control form-control-sm credit-balance" step="0.01" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label class="form-label">EL</label>
                                        <input type="number" name="el_balance" class="form-control form-control-sm credit-balance" step="0.01" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label class="form-label">MPL</label>
                                        <input type="number" name="mpl_balance" class="form-control form-control-sm credit-balance" step="0.01" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label class="form-label">SAL 1</label>
                                        <input type="number" name="sal1_balance" class="form-control form-control-sm credit-balance" step="0.01" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label class="form-label">SAL 2</label>
                                        <input type="number" name="sal2_balance" class="form-control form-control-sm credit-balance" step="0.01" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label class="form-label">SAL 3</label>
                                        <input type="number" name="sal3_balance" class="form-control form-control-sm credit-balance" step="0.01" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label class="form-label">EXL</label>
                                        <input type="number" name="exl_balance" class="form-control form-control-sm credit-balance" step="0.01" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label class="form-label">B2B</label>
                                        <input type="number" name="b2b_balance" class="form-control form-control-sm credit-balance" step="0.01" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label class="form-label">COMPL</label>
                                        <input type="number" name="compl_balance" class="form-control form-control-sm credit-balance" step="0.01" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Total Loan Balance</label>
                                        <input type="number" name="total_loan_balance" class="form-control" id="total_loan_balance" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Loanable Amount</label>
                                        <input type="number" name="loanable_amount" class="form-control" id="loanable_amount" step="0.01" placeholder="0.00" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Withdrawable CAPCON</label>
                                        <input type="number" name="withdrawable_capcon" class="form-control" step="0.01" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Total Loanable Amount</label>
                                        <input type="number" name="total_loanable_amount" class="form-control" id="total_loanable_amount" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Amortization Applied</label>
                                        <input type="text" name="amortization_applied" class="form-control" placeholder="e.g., 12 months" value="<?= (int)$loan_data['term_months']; ?> months">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Amortization Per Month</label>
                                        <input type="text" name="amortization_per_month" class="form-control" placeholder="e.g., ₱5,000.00">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mt-3">
                                <label class="form-label">Loanable Amount Computed By</label>
                                <input type="text" name="computed_by" class="form-control" value="<?= $cuser ?>" readonly>
                            </div>
                            
                            <div class="form-group mt-3">
                                <label class="form-label">Remarks</label>
                                <textarea name="remarks" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
                            </div>

                            <!-- ============================================ -->
                            <!-- CREDIT COMMITTEE APPROVAL SECTION -->
                            <!-- ============================================ -->
                            <div class="card mt-4">
                                <div class="card-header" style="background: #dbeafe;">
                                    <i class="ti ti-users me-2" style="color: #1e40af;"></i>
                                    Credit Committee Approval
                                    <span class="ms-2 text-muted small fw-normal">(Check all members who approve)</span>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle"></i> 
                                        Please review the credit assessment above. Check all committee members who approve this assessment.
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input credit-committee-check" type="checkbox" name="committee_sharra_credit" id="committee_sharra_credit" value="1">
                                                <label class="form-check-label fw-semibold" for="committee_sharra_credit">
                                                    Sharra A. Taywan <span class="badge bg-primary">Chair</span>
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input credit-committee-check" type="checkbox" name="committee_roseann" id="committee_roseann" value="1">
                                                <label class="form-check-label fw-semibold" for="committee_roseann">
                                                    Rose Ann H Bonto <span class="badge bg-info">Co-Chair</span>
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input credit-committee-check" type="checkbox" name="committee_gerry_credit" id="committee_gerry_credit" value="1">
                                                <label class="form-check-label fw-semibold" for="committee_gerry_credit">
                                                    Gerry Boy Garinggan <span class="badge bg-secondary">Member</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input credit-committee-check" type="checkbox" name="committee_michael_credit" id="committee_michael_credit" value="1">
                                                <label class="form-check-label fw-semibold" for="committee_michael_credit">
                                                    Michael Serafico <span class="badge bg-secondary">Member</span>
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input credit-committee-check" type="checkbox" name="committee_jovelyn" id="committee_jovelyn" value="1">
                                                <label class="form-check-label fw-semibold" for="committee_jovelyn">
                                                    Jovelyn E. Pareja <span class="badge bg-secondary">Member</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3" id="creditCommitteeStatus">
                                        <span class="text-muted">Selected: <span id="creditSelectedCount">0</span> of 5 members</span>
                                        <div class="progress mt-1" style="height: 6px;">
                                            <div class="progress-bar bg-success" id="creditCommitteeProgress" role="progressbar" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn-review mt-3" id="submitCreditBtn" disabled>
                                <i class="ti ti-save me-1"></i> Select Committee Members First
                            </button>
                        </form>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- STAGE 4: DECISION -->
                <!-- ============================================ -->
                <?php elseif($loan_data['workflow_stage'] == 'Decision'): ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="approval-actions">
                            <h6 class="fw-semibold mb-3" style="color: var(--navy-dark);">
                                <i class="ti ti-flag me-2" style="color: var(--gold-primary);"></i>
                                Final Decision
                            </h6>
                            
                            <!-- Show Risk Assessment Summary -->
                            <?php if(!empty($risk_assessment)): ?>
                            <div class="card mb-3" style="background: #fef7e0;">
                                <div class="card-body p-2">
                                    <small class="text-muted">Risk Assessment</small>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <span><strong>Score:</strong> <?= $risk_assessment['total_numerical_score'] ?></span>
                                        <span><strong>Rating:</strong> <?= $risk_assessment['descriptive_rating'] ?></span>
                                        <span class="badge badge-risk-<?= strtolower(str_replace(' ', '-', $risk_assessment['descriptive_rating'])) ?>">
                                            <?= $risk_assessment['descriptive_rating'] ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Show Credit Assessment Summary -->
                            <?php if(!empty($credit_assessment)): ?>
                            <div class="card mb-3" style="background: #dbeafe;">
                                <div class="card-body p-2">
                                    <small class="text-muted">Credit Assessment</small>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <span><strong>Total Balance:</strong> ₱<?= number_format($credit_assessment['total_loan_balance'], 2) ?></span>
                                        <span><strong>Loanable:</strong> ₱<?= number_format($credit_assessment['loanable_amount'], 2) ?></span>
                                        <span><strong>Total Loanable:</strong> ₱<?= number_format($credit_assessment['total_loanable_amount'], 2) ?></span>
                                        <span><strong>Computed By:</strong> <?= $credit_assessment['computed_by'] ?></span>
                                    </div>
                                    <?php if(!empty($credit_assessment['amortization_applied'])): ?>
                                    <div class="mt-1">
                                        <small><strong>Amortization:</strong> <?= $credit_assessment['amortization_applied'] ?> per month (<?= $credit_assessment['amortization_per_month'] ?>)</small>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(!empty($credit_assessment['approved_by'])): ?>
                                    <div class="mt-1">
                                        <small class="text-muted">Approved by Credit Committee on: <?= date('F d, Y h:i A', strtotime($credit_assessment['approved_at'])) ?></small>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <form id="approveLoanForm">
                                        <input type="hidden" name="loan_id" value="<?= $loan_id ?>">
                                        <div class="mb-2">
                                            <label class="form-label">Approval Remarks</label>
                                            <textarea name="remarks" class="form-control form-control-sm" placeholder="Approval remarks..." rows="2"></textarea>
                                        </div>
                                        <button type="submit" class="btn-approve w-100">
                                            <i class="ti ti-check me-1"></i> Approve Loan
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <form id="declineLoanForm">
                                        <input type="hidden" name="loan_id" value="<?= $loan_id ?>">
                                        <div class="mb-2">
                                            <label class="form-label">Decline Reason</label>
                                            <textarea name="remarks" class="form-control form-control-sm" placeholder="Decline reason..." rows="2"></textarea>
                                        </div>
                                        <button type="submit" class="btn-decline w-100">
                                            <i class="ti ti-x me-1"></i> Decline Loan
                                        </button>
                                    </form>
                                </div>
                                <div class="col-12 mt-2">
                                    <form id="reviseLoanForm">
                                        <input type="hidden" name="loan_id" value="<?= $loan_id ?>">
                                        <div class="mb-2">
                                            <label class="form-label">Revision Request</label>
                                            <textarea name="remarks" class="form-control form-control-sm" placeholder="Revision request details..." rows="2" required></textarea>
                                        </div>
                                        <button type="submit" class="btn-review w-100">
                                            <i class="ti ti-edit me-1"></i> Request Revision (Back to Pending)
                                        </button>
                                    </form>
                                </div>
                            </div>
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

                <!-- ============================================ -->
                <!-- COMPLETED: Approved or Declined -->
                <!-- ============================================ -->
                <?php elseif($loan_data['workflow_stage'] == 'Completed'): ?>
                <div class="alert alert-success">
                    <i class="ti ti-check-circle"></i> 
                    This loan has been <strong>APPROVED</strong>.
                    <?php if(!empty($loan_data['approval_by'])): ?>
                        <br><small class="text-muted">Approved by: <?= htmlspecialchars($loan_data['approval_by']) ?></small>
                    <?php endif; ?>
                    <?php if(!empty($loan_data['approval_at'])): ?>
                        <br><small class="text-muted">Approved on: <?= date('F d, Y h:i A', strtotime($loan_data['approval_at'])) ?></small>
                    <?php endif; ?>
                    <?php if(!empty($loan_data['approval_remarks'])): ?>
                        <br><small class="text-muted">Remarks: <?= htmlspecialchars($loan_data['approval_remarks']) ?></small>
                    <?php endif; ?>
                </div>

                <?php elseif($loan_data['workflow_stage'] == 'Declined'): ?>
                <div class="alert alert-danger">
                    <i class="ti ti-x-circle"></i> 
                    This loan has been <strong>DECLINED</strong>.
                    <?php if(!empty($loan_data['approval_by'])): ?>
                        <br><small class="text-muted">Declined by: <?= htmlspecialchars($loan_data['approval_by']) ?></small>
                    <?php endif; ?>
                    <?php if(!empty($loan_data['approval_at'])): ?>
                        <br><small class="text-muted">Declined on: <?= date('F d, Y h:i A', strtotime($loan_data['approval_at'])) ?></small>
                    <?php endif; ?>
                    <?php if(!empty($loan_data['approval_remarks'])): ?>
                        <br><small class="text-muted">Reason: <?= htmlspecialchars($loan_data['approval_remarks']) ?></small>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- AMORTIZATION SCHEDULE -->
        <!-- ============================================ -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="ti ti-chart-bar me-2" style="color: var(--gold-primary);"></i>
                Amortization Schedule (Quick Review)
            </div>
            <div class="card-body">
                <?php if(!empty($amortizationSched)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
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
                                    if($isPaid) $totalPaid++;
                                    else $totalUnpaid++;
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
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex gap-3 mt-2 small text-muted flex-wrap">
                        <span><i class="ti ti-check-circle text-success"></i> Paid: <?= $totalPaid ?></span>
                        <span><i class="ti ti-clock text-warning"></i> Unpaid: <?= $totalUnpaid ?></span>
                        <span><i class="ti ti-file"></i> Total: <?= $totalRows ?> periods</span>
                    </div>
                <?php else: ?>
                    <div class="text-center py-3 text-muted">
                        <i class="ti ti-info-circle fs-4 mb-2 d-block"></i>
                        <p class="mb-0">No amortization schedule generated yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- MEMBER DOCUMENTS -->
        <!-- ============================================ -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="ti ti-files me-2" style="color: var(--gold-primary);"></i>
                Member Documents
                <span class="text-muted small fw-normal">(<?= !empty($member_documents) ? count($member_documents) : 0 ?> documents)</span>
            </div>
            <div class="card-body">
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
                                ?>
                                <tr>
                                    <td>
                                        <i class="<?= $file_icon ?> me-2" style="color: var(--gold-primary);"></i>
                                        <?= htmlspecialchars($doc['document_name']) ?>
                                    </td>
                                    <td><?= strtoupper(pathinfo($doc['document_path'], PATHINFO_EXTENSION)) ?></td>
                                    <td><?= $file_size ?></td>
                                    <td><?= date('M d, Y', strtotime($doc['upload_date'])) ?></td>
                                    <td class="text-center">
                                        <a href="<?= $file_url ?>" target="_blank" class="btn btn-primary btn-sm">
                                            <i class="ti ti-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-3 text-muted">
                        <i class="ti ti-files fs-4 mb-2 d-block"></i>
                        <p class="mb-0">No documents uploaded for this member yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- APPROVAL HISTORY -->
        <!-- ============================================ -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="ti ti-history me-2" style="color: var(--gold-primary);"></i>
                Approval History
            </div>
            <div class="card-body">
                <?php if(!empty($approval_logs)): ?>
                    <?php foreach($approval_logs as $log): ?>
                        <div class="timeline-item <?= $log['action'] == 'APPROVE' ? 'completed' : ($log['action'] == 'DECLINE' ? 'declined' : '') ?>">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <strong><?= ucwords(strtolower($log['action'])) ?></strong>
                                    <span class="status-pill status-<?= strtolower($log['action']) ?>">
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
                <?php else: ?>
                    <div class="text-center py-3 text-muted">
                        <i class="ti ti-timeline fs-2 mb-2 d-block"></i>
                        No approval history yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php else: ?>
        <div class="alert alert-danger">
            <i class="ti ti-alert-triangle"></i> Loan not found.
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- ============================================ -->
<!-- LOADING OVERLAY -->
<!-- ============================================ -->
<div id="uploadOverlay">
    <div class="upload-spinner">
        <i class="ti ti-loader"></i>
        <p class="mt-2 mb-0">Processing...</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url('assets/js/approval/approval.js?v=3');?>"></script>

<?php echo view('templates/myfooter.php'); ?>