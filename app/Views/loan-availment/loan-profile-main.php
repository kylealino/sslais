<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

$meaction = $this->request->getGet('meaction');
$loan_id = $this->request->getGet('loan_id');

$member_id = "";
$loan_type = "";
$loan_amount = "";
$interest_rate = "";
$term_months = "";
$start_date = "";
$maturity_date = "";
$loan_comakers = "";
$status = "Pending";

// Get dashboard statistics
$activeLoans = $this->db->query("
    SELECT COUNT(*) as total 
    FROM tbl_loans l
    WHERE l.status != 'Paid' AND l.status != 'Completed' AND l.status != 'Closed'
")->getRowArray()['total'];

$loans = $this->db->query("
    SELECT loan_id, loan_amount
    FROM tbl_loans
    WHERE status != 'Paid'
    AND status != 'Completed'
    AND status != 'Closed'
")->getResultArray();

$totalOutstanding = 0;
foreach ($loans as $loan) {
    $balanceQuery = $this->db->query("
        SELECT ending_balance
        FROM tbl_loans_ammortization
        WHERE loan_id = ?
        AND payment_status = 'Paid'
        ORDER BY ammortization_id DESC
        LIMIT 1
    ", [$loan['loan_id']])->getRowArray();
    $outstanding = isset($balanceQuery['ending_balance']) ? (float)$balanceQuery['ending_balance'] : (float)$loan['loan_amount'];
    $totalOutstanding += $outstanding;
}

$dailyCollections = $this->db->query("
    SELECT COALESCE(SUM(total_payment), 0) as total 
    FROM tbl_loans_payment 
    WHERE DATE(created_at) = CURDATE()
")->getRowArray()['total'];

$totalMembers = $this->db->query("SELECT COUNT(*) as total FROM tbl_members")->getRowArray()['total'];
$totalLoans = $this->db->query("SELECT COUNT(*) as total FROM tbl_loans")->getRowArray()['total'];

// Get list of loans for display
$loanList = $this->db->query("
    SELECT l.loan_id, l.loan_type, l.loan_amount, l.status, 
           m.first_name, m.last_name, m.member_no
    FROM tbl_loans l
    LEFT JOIN tbl_members m ON l.member_id = m.member_id
    ORDER BY l.loan_id DESC
")->getResultArray();

// If loan_id is provided, get specific loan details
if(!empty($loan_id)) {
    $query = $this->db->query("SELECT * FROM tbl_loans WHERE loan_id = '$loan_id'");
    $data = $query->getRowArray();

    if($data) {
        $member_id = $data['member_id'];
        $loan_type = $data['loan_type'];
        $loan_amount = $data['loan_amount'];
        $interest_rate = $data['interest_rate'];
        $term_months = $data['term_months'];
        $start_date = $data['start_date'];
        $maturity_date = $data['maturity_date'];
        $loan_comakers = $data['loan_comakers'];
        $status = $data['status'];
        
        // Get member details
        $memberQuery = $this->db->query("SELECT first_name, last_name, member_no, contact_number, email FROM tbl_members WHERE member_id = '$member_id'");
        $member = $memberQuery->getRowArray();
        
        // Get co-maker details
        $comaker_name = "";
        $comaker_id = $loan_comakers;
        $comaker_no = "";
        if(!empty($loan_comakers)) {
            $comakerQuery = $this->db->query("SELECT first_name, last_name, member_no FROM tbl_members WHERE member_id = '$loan_comakers'");
            $comaker = $comakerQuery->getRowArray();
            if($comaker) {
                $comaker_name = $comaker['first_name'] . ' ' . $comaker['last_name'];
                $comaker_no = $comaker['member_no'];
            }
        }
        
        // Get outstanding balance
        $balanceQuery = $this->db->query("
            SELECT ending_balance 
            FROM tbl_loans_ammortization 
            WHERE loan_id = ? 
            AND payment_status = 'Paid'
            ORDER BY ammortization_id DESC LIMIT 1
        ", [$loan_id])->getRowArray();
        $outstanding = isset($balanceQuery['ending_balance']) ? (float)$balanceQuery['ending_balance'] : (float)$loan_amount;
        
        // Get amortization schedule
        $amortizationSched = $this->db->query("
            SELECT * FROM tbl_loans_ammortization 
            WHERE loan_id = '$loan_id'
            ORDER BY period ASC
        ")->getResultArray();
        
        // Get payment history
        $payments = $this->db->query("
            SELECT payment_id, total_payment, payment_date, created_by
            FROM tbl_loans_payment
            WHERE loan_id = ?
            ORDER BY payment_date DESC
        ", [$loan_id])->getResultArray();
        
        // MOCKUP DATA FOR CO-MAKER EXPOSURE
        $mock_comaker_exposure = [
            'total_exposure' => number_format($loan_amount * 0.3, 2),
            'active_loans_as_comaker' => rand(1, 3),
            'total_comaker_obligations' => number_format($loan_amount * 0.5, 2),
            'risk_contribution' => $outstanding > ($loan_amount / 2) ? 'High' : ($outstanding > ($loan_amount / 4) ? 'Medium' : 'Low'),
            'payment_performance' => $loan_amount > 0 ? round((($loan_amount - $outstanding) / $loan_amount) * 100, 1) : 100,
            'exposure_percentage' => $loan_amount > 0 ? round(($outstanding / $loan_amount) * 100, 1) : 0
        ];
        
        // Mockup other loans where this member is a co-maker
        $otherLoansAsComaker = [];
        if($loan_id % 2 == 0) {
            $otherLoansAsComaker = [
                ['loan_id' => $loan_id - 1, 'borrower' => 'Cruz, Juan', 'loan_amount' => 50000, 'outstanding' => 25000, 'status' => 'Active'],
                ['loan_id' => $loan_id - 3, 'borrower' => 'Reyes, Maria', 'loan_amount' => 30000, 'outstanding' => 30000, 'status' => 'Pending']
            ];
        } else {
            $otherLoansAsComaker = [
                ['loan_id' => $loan_id - 2, 'borrower' => 'Dela Cruz, Ana', 'loan_amount' => 40000, 'outstanding' => 15000, 'status' => 'Active']
            ];
        }
    }
}

echo view('templates/myheader.php');
?>

<style>
    :root {
        --navy-dark: #0a1a3a;
        --navy-medium: #1a2e5a;
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

    /* Attendance Card Style */
    .attendance-card {
        background: var(--white-bg);
        border-radius: 20px;
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .attendance-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -12px rgba(0,0,0,0.1);
        border-color: var(--gray-300);
    }

    .attendance-card .card-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
    }

    .attendance-value {
        font-size: 32px;
        font-weight: 700;
        line-height: 1.2;
        color: var(--gray-800);
    }

    .attendance-icon {
        font-size: 42px;
        opacity: 0.12;
        color: var(--gold-primary);
    }

    .attendance-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .attendance-sub {
        font-size: 11px;
        color: var(--gray-400);
        margin-top: 6px;
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
        padding: 14px 20px;
        font-weight: 600;
        color: var(--navy-dark);
    }

    .card-body {
        padding: 20px;
    }

    /* Buttons */
    .btn-primary {
        background: var(--navy-dark);
        border: none;
        border-radius: 10px;
        padding: 6px 16px;
        font-size: 12px;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        background: var(--navy-medium);
        transform: translateY(-1px);
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
    
    .btn-assess {
        background: var(--info);
        border: none;
        border-radius: 10px;
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 600;
        color: white;
        transition: all 0.2s;
    }

    .btn-assess:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }
    
    .btn-generate {
        background: var(--white-bg);
        border: 1.5px solid var(--gold-primary);
        color: var(--gold-dark);
        padding: 8px 20px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.2s;
    }

    .btn-generate:hover {
        background: var(--gold-primary);
        color: var(--navy-dark);
        transform: translateY(-1px);
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

    /* Risk Assessment Styles */
    .risk-card {
        background: var(--white-bg);
        border-radius: 16px;
        padding: 16px;
        border: 1px solid var(--gray-200);
        transition: all 0.2s;
        text-align: center;
    }

    .risk-card:hover {
        border-color: var(--gold-primary);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .risk-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray-500);
        margin-bottom: 6px;
    }

    .risk-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--navy-dark);
        line-height: 1.2;
    }

    .risk-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
    }

    .risk-low {
        background: #d1fae5;
        color: #065f46;
    }

    .risk-medium {
        background: #fef3c7;
        color: #d97706;
    }

    .risk-high {
        background: #fee2e2;
        color: #dc2626;
    }

    .exposure-card {
        background: var(--white-bg);
        border-radius: 16px;
        padding: 16px;
        border: 1px solid var(--gray-200);
        transition: all 0.2s;
        text-align: center;
    }

    .exposure-card:hover {
        border-color: var(--gold-primary);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .exposure-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray-500);
        margin-bottom: 6px;
    }

    .exposure-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--navy-dark);
    }

    .comaker-info-card {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
        border-radius: 16px;
        padding: 20px;
        color: white;
        margin-bottom: 20px;
    }

    .comaker-info-card h5 {
        color: white;
        margin-bottom: 5px;
    }

    .comaker-info-card .text-muted {
        color: rgba(255,255,255,0.7) !important;
    }

    .recommend-approve {
        background: #d1fae5;
        color: #065f46;
        border-left: 3px solid var(--success);
    }

    .recommend-review {
        background: #fef3c7;
        color: #d97706;
        border-left: 3px solid var(--warning);
    }

    .info-alert {
        background: var(--gold-soft);
        border-left: 4px solid var(--gold-primary);
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .info-alert i {
        color: var(--gold-primary);
        margin-right: 10px;
    }

    /* Tables */
    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background: transparent;
        color: var(--gray-500);
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 8px;
        border-bottom: 1px solid var(--gray-200);
    }

    .table tbody td {
        padding: 10px 8px;
        color: var(--gray-700);
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
        font-size: 13px;
    }

    .table tbody tr:hover td {
        background: var(--gold-soft);
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

    .dataTables_info {
        float: left;
        font-size: 12px;
        color: var(--gray-500);
        margin-top: 20px;
    }

    /* Badges */
    .badge {
        padding: 4px 10px;
        font-size: 10px;
        font-weight: 600;
        border-radius: 30px;
    }

    .bg-warning {
        background: var(--warning) !important;
        color: white;
    }

    .bg-success {
        background: var(--success) !important;
    }

    .bg-secondary {
        background: var(--gray-400) !important;
    }
    
    .status-pending { background: #fef3c7; color: #d97706; }
    .status-approved { background: #dbeafe; color: #1e40af; }
    .status-active { background: #d1fae5; color: #065f46; }
    .status-closed { background: #e2e8f0; color: #475569; }
    .status-declined { background: #fee2e2; color: #dc2626; }
    
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 30px;
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

    .dashboard-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--navy-dark);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--gold-primary);
        display: inline-block;
    }

    /* Nav Tabs */
    .nav-tabs {
        border-bottom: 2px solid var(--gray-200);
        background: var(--white-bg);
        border-radius: 20px 20px 0 0;
        flex-wrap: wrap;
    }

    .nav-tabs .nav-link {
        border: none;
        color: var(--gray-600);
        font-weight: 500;
        font-size: 13px;
        padding: 10px 20px;
        transition: all 0.2s;
        position: relative;
    }

    .nav-tabs .nav-link i {
        margin-right: 8px;
        font-size: 16px;
    }

    .nav-tabs .nav-link:hover {
        color: var(--gold-primary);
        background: transparent;
    }

    .nav-tabs .nav-link.active {
        color: var(--gold-primary);
        background: transparent;
        font-weight: 600;
    }

    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--gold-primary);
    }

    .tab-content {
        padding: 20px 0;
        background: transparent;
    }

    /* Form Controls for Loan Details */
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
        background: var(--gray-50);
    }
    
    .form-control[readonly] {
        background: var(--gray-50);
    }

    /* Payment Amount Input */
    #total_payment {
        background: var(--gray-50);
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 16px;
        }
        .attendance-value {
            font-size: 24px;
        }
        .attendance-icon {
            font-size: 34px;
        }
        .table {
            font-size: 11px;
        }
        .btn-success, .btn-assess, .btn-generate, .btn-back {
            width: 100%;
            margin-top: 10px;
        }
        .nav-tabs .nav-link {
            padding: 8px 12px;
            font-size: 11px;
        }
        .risk-value, .exposure-value {
            font-size: 22px;
        }
        .dataTables_filter {
            float: none;
            text-align: center;
            margin-bottom: 15px;
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
    }
</style>

<div class="container-fluid">
    <div class="row me-myloanprofile-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    
    <div class="row mb-2 mt-2">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="fw-semibold my-3">Loan Profile</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="<?=site_url();?>mydashboard">
                                    <i class="ti ti-home fs-5"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="<?=site_url();?>myloans">Loan Management</a>
                            </li>
                            <li class="breadcrumb-item active">Loan Profile</li>
                        </ol>
                    </nav>
                </div>
                <?php if(!empty($loan_id)): ?>
                <div>
                    <a href="<?=site_url('myloanprofile?meaction=MAIN'); ?>" class="btn-back">
                        <i class="ti ti-arrow-left me-1"></i> Back to Loan List
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if(empty($loan_id)): ?>
        <!-- DASHBOARD VIEW -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="attendance-card">
                    <div class="card-body">
                        <div>
                            <div class="attendance-label">Active Loans</div>
                            <div class="attendance-value"><?= number_format($activeLoans); ?></div>
                            <div class="attendance-sub"><?= number_format($totalMembers); ?> Members | <?= number_format($totalLoans); ?> Total</div>
                        </div>
                        <i class="ti ti-file-invoice attendance-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="attendance-card">
                    <div class="card-body">
                        <div>
                            <div class="attendance-label">Outstanding Balance</div>
                            <div class="attendance-value">₱<?= number_format($totalOutstanding, 2); ?></div>
                            <div class="attendance-sub">Total remaining balance</div>
                        </div>
                        <i class="ti ti-currency-peso attendance-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="attendance-card">
                    <div class="card-body">
                        <div>
                            <div class="attendance-label">Daily Collections</div>
                            <div class="attendance-value">₱<?= number_format($dailyCollections, 2); ?></div>
                            <div class="attendance-sub"><?= date('M d, Y'); ?></div>
                        </div>
                        <i class="ti ti-calendar attendance-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="attendance-card">
                    <div class="card-body">
                        <div>
                            <div class="attendance-label">Active Rate</div>
                            <div class="attendance-value"><?= number_format(($totalLoans > 0 ? ($activeLoans / $totalLoans * 100) : 0), 1); ?>%</div>
                            <div class="attendance-sub"><?= number_format($activeLoans); ?> of <?= number_format($totalLoans); ?> active</div>
                        </div>
                        <i class="ti ti-chart-bar attendance-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loan List Table -->
        <div class="card">
            <div class="card-header">
                <i class="ti ti-list me-2" style="color: var(--gold-primary);"></i>
                Loan Applications
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="loansTable" class="table mb-0">
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
                            <?php foreach($loanList as $loan): ?>
                            <tr>
                                <td><?= $loan['loan_id'] ?></td>
                                <td><?= htmlspecialchars($loan['first_name'] . ' ' . $loan['last_name']) ?></td>
                                <td><?= $loan['member_no'] ?></td>
                                <td><?= $loan['loan_type'] ?></td>
                                <td class="text-end">₱<?= number_format($loan['loan_amount'], 2) ?></td>
                                <td>
                                    <span class="status-pill 
                                        <?= $loan['status'] == 'Pending' ? 'status-pending' : ($loan['status'] == 'Approved' ? 'status-approved' : ($loan['status'] == 'Active' ? 'status-active' : ($loan['status'] == 'Closed' ? 'status-closed' : 'status-declined'))) ?>">
                                        <?= $loan['status'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('myloanprofile?meaction=MAIN&loan_id='.$loan['loan_id']); ?>" class="btn btn-primary btn-sm">
                                        <i class="ti ti-eye"></i> View Profile
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
        <!-- LOAN PROFILE VIEW -->
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
                        <h5 class="mb-0 text-success">₱<?= number_format((float)$loan_amount, 2); ?></h5>
                        <small class="text-muted"><?= $loan_type; ?></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted small text-uppercase mb-1">Outstanding Balance</div>
                        <h5 class="mb-0 text-danger">₱<?= number_format($outstanding, 2); ?></h5>
                        <small class="text-muted">Remaining to pay</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted small text-uppercase mb-1">Loan Status</div>
                        <h5 class="mb-0">
                            <span class="status-pill 
                                <?= $status == 'Pending' ? 'status-pending' : ($status == 'Approved' ? 'status-approved' : ($status == 'Active' ? 'status-active' : ($status == 'Closed' ? 'status-closed' : 'status-declined'))) ?>">
                                <?= esc($status); ?>
                            </span>
                        </h5>
                        <small class="text-muted">Term: <?= (int)$term_months; ?> months</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Tabs -->
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs" id="loanProfileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="loan-details-tab" data-bs-toggle="tab" data-bs-target="#loan-details" type="button" role="tab">
                            <i class="ti ti-file-description"></i> Loan Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="comaker-exposure-tab" data-bs-toggle="tab" data-bs-target="#comaker-exposure" type="button" role="tab">
                            <i class="ti ti-handshake"></i> Co-Maker Exposure
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="credit-assessment-tab" data-bs-toggle="tab" data-bs-target="#credit-assessment" type="button" role="tab">
                            <i class="ti ti-shield"></i> Credit & Risk Assessment
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payment-history-tab" data-bs-toggle="tab" data-bs-target="#payment-history" type="button" role="tab">
                            <i class="ti ti-history"></i> Payment History
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="loanProfileTabsContent">
                    
                    <!-- TAB 1: LOAN DETAILS with Payment -->
                    <div class="tab-pane fade show active" id="loan-details" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header">Loan Information</div>
                                    <div class="card-body">
                                        <p><strong>Loan Type:</strong> <?= esc($loan_type); ?></p>
                                        <p><strong>Loan Amount:</strong> ₱<?= number_format((float)$loan_amount, 2); ?></p>
                                        <p><strong>Interest Rate:</strong> <?= number_format((float)$interest_rate, 2); ?>%</p>
                                        <p><strong>Term:</strong> <?= (int)$term_months; ?> months</p>
                                        <p><strong>Start Date:</strong> <?= date('F d, Y', strtotime($start_date)); ?></p>
                                        <p><strong>Maturity Date:</strong> <?= date('F d, Y', strtotime($maturity_date)); ?></p>
                                        <p><strong>Co-maker/Guarantor:</strong> <?= !empty($comaker_name) ? htmlspecialchars($comaker_name) : 'None' ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <!-- Make Payment Section -->
                                <div class="card mb-3">
                                    <div class="card-header">Make Payment</div>
                                    <div class="card-body">
                                        <form class="myloanprofile-validation" id="paymentForm">
                                            <input type="hidden" name="loan_id" id="loan_id" value="<?= $loan_id; ?>">
                                            <input type="hidden" name="member_id" id="member_id" value="<?= $member_id; ?>">
                                            <input type="hidden" name="interest" id="interest">
                                            <input type="hidden" name="principal" id="principal">
                                            <input type="hidden" name="ammortization_id" id="ammortization_id">
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Payment Date</label>
                                                    <input type="date" name="payment_date" id="payment_date" class="form-control form-control-sm" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="number" step="0.01" name="amount" id="total_payment" class="form-control form-control-sm" readonly style="background: var(--gray-50);" required>
                                                </div>
                                            </div>
                                            
                                            <div id="selectedAmortizationInfo" style="display: none;" class="mb-3">
                                                <div class="alert alert-info p-2 mb-0">
                                                    <i class="ti ti-info-circle"></i> 
                                                    <strong>Selected Payment:</strong> Period <span id="info_period">-</span> | 
                                                    Principal: ₱<span id="info_principal">0.00</span> | 
                                                    Interest: ₱<span id="info_interest">0.00</span>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <button type="submit" class="btn btn-success" id="payButton" disabled>
                                                    <i class="ti ti-credit-card"></i> Pay Amortization
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Amortization Schedule -->
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div>
                                                <i class="ti ti-chart-bar me-2" style="color: var(--gold-primary);"></i>
                                                Amortization Schedule
                                            </div>
                                            <button type="button" id="generateAmortization" class="btn-generate">
                                                <i class="ti ti-calculator me-1"></i> Generate Schedule
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table class="table mb-0 ammortization-list" id="amortizationTable">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>Payment Date</th>
                                                    <th class="text-end">Beginning Balance</th>
                                                    <th class="text-end">Interest</th>
                                                    <th class="text-end">Principal</th>
                                                    <th class="text-end">Payment</th>
                                                    <th class="text-end">Ending Balance</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="amortizationTableBody">
                                                <?php if(!empty($amortizationSched)): ?>
                                                    <?php foreach($amortizationSched as $row): 
                                                        $isPaid = isset($row['payment_status']) && $row['payment_status'] === 'Paid';
                                                    ?>
                                                        <tr class="<?= $isPaid ? 'table-success' : ''; ?>">
                                                            <td class="text-center"><?= (int)$row['period']; ?></td>
                                                            <td class="text-center"><?= date('m/d/Y', strtotime($row['payment_date'])); ?></td>
                                                            <td class="text-end">₱<?= number_format((float)$row['beginning_balance'], 2); ?></td>
                                                            <td class="text-end">₱<?= number_format((float)$row['interest'], 2); ?></td>
                                                            <td class="text-end">₱<?= number_format((float)$row['principal'], 2); ?></td>
                                                            <td class="text-end">₱<?= number_format((float)$row['payment'], 2); ?></td>
                                                            <td class="text-end">₱<?= number_format((float)$row['ending_balance'], 2); ?></td>
                                                            <td class="text-center">
                                                                <?php if($isPaid): ?>
                                                                    <span class="badge bg-success">Paid</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary">Unpaid</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php if(!$isPaid): ?>
                                                                    <button type="button" 
                                                                            class="btn btn-primary btn-sm select-payment" 
                                                                            data-period="<?= (int)$row['period']; ?>"
                                                                            data-ammortization-id="<?= (int)$row['ammortization_id']; ?>"
                                                                            data-payment-date="<?= date('Y-m-d', strtotime($row['payment_date'])); ?>"
                                                                            data-amount="<?= (float)$row['payment']; ?>"
                                                                            data-interest="<?= (float)$row['interest']; ?>"
                                                                            data-principal="<?= (float)$row['principal']; ?>">
                                                                        <i class="ti ti-credit-card"></i> Pay
                                                                    </button>
                                                                <?php else: ?>
                                                                    <span class="text-muted">Paid</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="9" class="text-center text-muted py-3">
                                                            No amortization schedule generated yet. Click "Generate Schedule" button.
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- TAB 2: CO-MAKER EXPOSURE -->
                    <div class="tab-pane fade" id="comaker-exposure" role="tabpanel">
                        <!-- Co-maker Information Card -->
                        <div class="comaker-info-card">
                            <div class="row">
                                <div class="col-md-8">
                                    <small class="text-muted">CO-MAKER / GUARANTOR</small>
                                    <h5 class="mb-1"><?= !empty($comaker_name) ? htmlspecialchars($comaker_name) : 'No Co-maker Assigned' ?></h5>
                                    <p class="mb-0">Member #: <?= !empty($comaker_no) ? $comaker_no : '—' ?></p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <small class="text-muted">EXPOSURE STATUS</small>
                                    <div>
                                        <span class="risk-badge <?= $mock_comaker_exposure['risk_contribution'] == 'Low' ? 'risk-low' : ($mock_comaker_exposure['risk_contribution'] == 'Medium' ? 'risk-medium' : 'risk-high') ?>">
                                            <?= $mock_comaker_exposure['risk_contribution'] ?> Risk
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Exposure Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <div class="exposure-card text-center">
                                    <div class="exposure-label">Total Exposure</div>
                                    <div class="exposure-value">₱ <?= $mock_comaker_exposure['total_exposure'] ?></div>
                                    <small class="text-muted">Amount co-maker is liable for</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="exposure-card text-center">
                                    <div class="exposure-label">Active Co-maker Loans</div>
                                    <div class="exposure-value"><?= $mock_comaker_exposure['active_loans_as_comaker'] ?></div>
                                    <small class="text-muted">Other loans where this member is co-maker</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="exposure-card text-center">
                                    <div class="exposure-label">Total Co-maker Obligations</div>
                                    <div class="exposure-value">₱ <?= $mock_comaker_exposure['total_comaker_obligations'] ?></div>
                                    <small class="text-muted">Total amount guaranteed</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Exposure Breakdown -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header">Current Loan Exposure</div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Loan Amount:</span>
                                                <strong>₱ <?= number_format($loan_amount, 2) ?></strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Outstanding Balance:</span>
                                                <strong class="text-danger">₱ <?= number_format($outstanding, 2) ?></strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Co-maker Exposure Percentage:</span>
                                                <strong><?= $mock_comaker_exposure['exposure_percentage'] ?>%</strong>
                                            </div>
                                        </div>
                                        <div class="progress mb-3" style="height: 8px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $mock_comaker_exposure['exposure_percentage'] ?>%;"></div>
                                        </div>
                                        <div class="info-alert small mb-0" style="background: #fef3c7;">
                                            <i class="ti ti-alert-triangle"></i>
                                            <span>As co-maker, you are jointly and severally liable for the full outstanding balance of ₱ <?= number_format($outstanding, 2) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header">Co-maker Performance Metrics</div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span>Payment Performance:</span>
                                            <strong class="<?= $mock_comaker_exposure['payment_performance'] >= 80 ? 'text-success' : ($mock_comaker_exposure['payment_performance'] >= 50 ? 'text-warning' : 'text-danger') ?>">
                                                <?= $mock_comaker_exposure['payment_performance'] ?>% Paid
                                            </strong>
                                        </div>
                                        <div class="progress mb-3" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $mock_comaker_exposure['payment_performance'] ?>%;"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Risk Contribution:</span>
                                            <strong class="<?= $mock_comaker_exposure['risk_contribution'] == 'Low' ? 'text-success' : ($mock_comaker_exposure['risk_contribution'] == 'Medium' ? 'text-warning' : 'text-danger') ?>">
                                                <?= $mock_comaker_exposure['risk_contribution'] ?>
                                            </strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Co-maker Since:</span>
                                            <strong><?= date('M d, Y', strtotime($start_date)) ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Other Loans Where This Member is Co-maker -->
                        <div class="card">
                            <div class="card-header">
                                <i class="ti ti-handshake me-2"></i>
                                Other Loans Where This Member is Co-maker
                            </div>
                            <div class="card-body table-responsive">
                                <?php if(!empty($otherLoansAsComaker)): ?>
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Loan ID</th>
                                            <th>Borrower</th>
                                            <th>Loan Amount</th>
                                            <th>Outstanding Balance</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($otherLoansAsComaker as $other): ?>
                                        <tr>
                                            <td><?= $other['loan_id'] ?></td>
                                            <td><?= $other['borrower'] ?></td>
                                            <td class="text-end">₱ <?= number_format($other['loan_amount'], 2) ?></td>
                                            <td class="text-end text-danger">₱ <?= number_format($other['outstanding'], 2) ?></td>
                                            <td>
                                                <span class="status-pill <?= $other['status'] == 'Active' ? 'status-active' : 'status-pending' ?>">
                                                    <?= $other['status'] ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= site_url('myloanprofile?meaction=MAIN&loan_id='.$other['loan_id']); ?>" class="btn btn-primary btn-sm">
                                                    <i class="ti ti-eye"></i> View
                                                </a>
                                            </td>
                                        </table>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <div class="text-center py-3 text-muted">
                                    <i class="ti ti-handshake fs-2 mb-2 d-block"></i>
                                    This member is not a co-maker for any other loans.
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Accountability Note -->
                        <div class="info-alert mt-3" style="background: #fee2e2; border-left-color: #dc2626;">
                            <i class="ti ti-alert-triangle" style="color: #dc2626;"></i>
                            <span>
                                <strong>⚠️ Co-maker Accountability Notice:</strong><br>
                                • As a co-maker/guarantor, you are equally responsible for loan repayment.<br>
                                • If the borrower defaults, the cooperative may demand payment from you.<br>
                                • Your credit standing and future loan applications may be affected.<br>
                                • Total exposure across all loans should not exceed your payment capacity.
                            </span>
                        </div>
                    </div>
                    
                    <!-- TAB 3: CREDIT & RISK ASSESSMENT -->
                    <div class="tab-pane fade" id="credit-assessment" role="tabpanel">
                        <div class="info-alert mb-4" style="background: #dbeafe; border-left-color: #3b82f6;">
                            <i class="ti ti-info-circle" style="color: #3b82f6;"></i>
                            <span>
                                <strong>📋 Assessment Workflow Status:</strong><br>
                                <?php
                                if($status == 'Pending') {
                                    echo '🔍 <strong>Step 1: Credit Assessment Required</strong> - Credit assessment is in progress.';
                                } elseif($status == 'Approved') {
                                    echo '✅ <strong>Step 2: Assessment Complete</strong> - Credit assessment done. Loan approved.';
                                } elseif($status == 'Active') {
                                    echo '💰 <strong>Step 3: Loan Disbursed</strong> - Loan is active and being paid.';
                                } elseif($status == 'Closed') {
                                    echo '🏁 <strong>Step 4: Loan Closed</strong> - Loan has been fully paid.';
                                } elseif($status == 'Declined') {
                                    echo '❌ <strong>Loan Declined</strong> - Application was declined.';
                                }
                                ?>
                            </span>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <div class="risk-card">
                                    <div class="risk-label">Credit Score</div>
                                    <div class="risk-value" id="mock_credit_score">85</div>
                                    <small class="text-muted">Out of 100 points</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="risk-card">
                                    <div class="risk-label">Risk Rating</div>
                                    <div class="risk-value">
                                        <span class="risk-badge risk-low" id="mock_risk_badge">Low</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="risk-card">
                                    <div class="risk-label">Debt-to-Income Ratio</div>
                                    <div class="risk-value" id="mock_dti">28.0%</div>
                                    <small class="text-muted">Ideal: ≤ 40%</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="risk-card">
                                    <div class="risk-label">Payment Capacity</div>
                                    <div class="risk-value" id="mock_capacity">₱ 25,000.00</div>
                                    <small class="text-muted">Monthly disposable income</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="risk-card recommend-approve" style="padding: 15px; text-align: left;" id="mock_recommendation_card">
                                    <div class="risk-label">Recommendation</div>
                                    <div class="fw-semibold fs-5" style="color: var(--navy-dark);" id="mock_recommendation">
                                        Approve
                                    </div>
                                    <small class="text-muted">Credit Committee Decision</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="risk-card" style="padding: 15px; text-align: left;">
                                    <div class="risk-label">Assessed By / Date</div>
                                    <div class="fw-semibold fs-6" style="color: var(--navy-dark);" id="mock_assessed_by">
                                        Juan Dela Cruz
                                    </div>
                                    <small class="text-muted" id="mock_assessment_date"><?= date('F d, Y') ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h6 class="fw-semibold mb-3" style="color: var(--navy-dark);">
                                <i class="ti ti-clipboard me-2" style="color: var(--gold-primary);"></i>
                                Risk Assessment Notes
                            </h6>
                            <div class="info-alert" id="mock_notes">
                                <i class="ti ti-file-text"></i>
                                <span>Excellent credit standing. Member has consistent savings and no delinquent records. Strong payment capacity.</span>
                            </div>
                        </div>

                        <div class="info-alert mt-3">
                            <i class="ti ti-info-circle"></i>
                            <span>
                                <strong>📊 Credit Scoring Criteria:</strong><br>
                                • <strong>80-100 points</strong>: Excellent - Low risk, recommend approval<br>
                                • <strong>60-79 points</strong>: Good - Moderate risk, may need co-maker<br>
                                • <strong>40-59 points</strong>: Fair - High risk, requires review<br>
                                • <strong>Below 40 points</strong>: Poor - High risk, recommend decline
                            </span>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-sm-12 text-end">
                                <button type="button" class="btn-assess" id="refreshAssessmentBtn">
                                    <i class="ti ti-refresh me-1"></i> Refresh Assessment Data (Mockup)
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- TAB 4: PAYMENT HISTORY -->
                    <div class="tab-pane fade" id="payment-history" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th class="text-end">Amount</th>
                                        <th>Processed By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($payments)): ?>
                                        <?php foreach($payments as $pay): ?>
                                            <tr>
                                                <td><?= date('m/d/Y', strtotime($pay['payment_date'])); ?></td>
                                                <td class="text-end">₱<?= number_format((float)$pay['total_payment'], 2); ?></td>
                                                <td><?= esc($pay['created_by']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">No payments yet</span></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url('assets/js/loan-availment/myloanprofile.js?v=1');?>"></script>

<script>
$(document).ready(function () {
    if ($('#loansTable').length && !$.fn.DataTable.isDataTable('#loansTable')) {
        $('#loansTable').DataTable({
            pageLength: 10,
            lengthChange: true,
            order: [[0, 'desc']],
            language: { 
                search: "Search Loan:",
                info: "Showing _START_ to _END_ of _TOTAL_ loans",
                infoEmpty: "No loans found",
                lengthMenu: "Show _MENU_ loans"
            },
            dom: 'frtip'
        });
    }
    
    <?php if(!empty($loan_id)): ?>
    // Generate Amortization Schedule
    $('#generateAmortization').click(function() {
        let loanAmount = parseFloat(<?= $loan_amount ?>);
        let interestRate = parseFloat(<?= $interest_rate ?>);
        let termMonths = parseInt(<?= $term_months ?>);
        let startDate = '<?= $start_date ?>';
        let loanId = <?= $loan_id ?>;
        
        if(!loanAmount || !interestRate || !termMonths) {
            alert('Missing loan details. Cannot generate schedule.');
            return;
        }
        
        let monthlyRate = (interestRate / 100) / 12;
        let payment = (loanAmount * monthlyRate * Math.pow(1 + monthlyRate, termMonths)) / (Math.pow(1 + monthlyRate, termMonths) - 1);
        
        let balance = loanAmount;
        let html = '';
        let currentDate = startDate ? new Date(startDate) : new Date();
        
        for(let i = 1; i <= termMonths; i++) {
            let interest = balance * monthlyRate;
            let principal = payment - interest;
            let endingBalance = balance - principal;
            
            let paymentDate = new Date(currentDate);
            paymentDate.setMonth(currentDate.getMonth() + i);
            let dateStr = paymentDate.toISOString().slice(0,10);
            
            html += `
                <tr>
                    <td class="text-center">${i}</td>
                    <td class="text-center">${dateStr}</td>
                    <td class="text-end">₱ ${balance.toLocaleString('en-US', {minimumFractionDigits:2})}</td>
                    <td class="text-end">₱ ${interest.toLocaleString('en-US', {minimumFractionDigits:2})}</td>
                    <td class="text-end">₱ ${principal.toLocaleString('en-US', {minimumFractionDigits:2})}</td>
                    <td class="text-end">₱ ${payment.toLocaleString('en-US', {minimumFractionDigits:2})}</td>
                    <td class="text-end">₱ ${endingBalance.toLocaleString('en-US', {minimumFractionDigits:2})}</td>
                    <td class="text-center"><span class="badge bg-secondary">Unpaid</span></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-primary btn-sm select-payment" 
                                data-period="${i}"
                                data-payment-date="${dateStr}"
                                data-amount="${payment.toFixed(2)}"
                                data-interest="${interest.toFixed(2)}"
                                data-principal="${principal.toFixed(2)}">
                            <i class="ti ti-credit-card"></i> Pay
                        </button>
                    </td>
                </tr>
            `;
            balance = endingBalance;
            if(balance < 0) balance = 0;
        }
        
        $('#amortizationTableBody').html(html);
        alert('Amortization schedule generated successfully!');
    });
    
    // Select payment handler
    $(document).on('click', '.select-payment', function() {
        let period = $(this).data('period');
        let paymentDate = $(this).data('payment-date');
        let amount = $(this).data('amount');
        let interest = $(this).data('interest');
        let principal = $(this).data('principal');
        let ammortizationId = $(this).data('ammortization-id');
        
        $('#payment_date').val(paymentDate);
        $('#total_payment').val(amount.toFixed(2));
        $('#interest').val(interest);
        $('#principal').val(principal);
        if(ammortizationId) $('#ammortization_id').val(ammortizationId);
        
        $('#info_period').text(period);
        $('#info_principal').text(principal.toFixed(2));
        $('#info_interest').text(interest.toFixed(2));
        $('#selectedAmortizationInfo').show();
        
        $('#payButton').prop('disabled', false);
        
        $('.select-payment').closest('tr').removeClass('table-primary');
        $(this).closest('tr').addClass('table-primary');
    });
    
    // Refresh Assessment Data (Mockup)
    $('#refreshAssessmentBtn').click(function() {
        let newCreditScore = Math.floor(Math.random() * (95 - 55 + 1) + 55);
        let newDti = (Math.random() * (45 - 20) + 20).toFixed(1);
        let newPaymentCapacity = Math.floor(Math.random() * (35000 - 15000 + 1) + 15000);
        
        let newRiskRating = '';
        let newRiskClass = '';
        let newRecommendation = '';
        let newRecommendationClass = '';
        let newNotes = '';
        let newAssessedBy = '';
        
        if(newCreditScore >= 80) {
            newRiskRating = 'Low';
            newRiskClass = 'risk-low';
            newRecommendation = 'Approve';
            newRecommendationClass = 'recommend-approve';
            newNotes = 'Excellent credit standing. Member has consistent savings and no delinquent records.';
            newAssessedBy = 'Maria Santos';
        } else if(newCreditScore >= 65) {
            newRiskRating = 'Medium';
            newRiskClass = 'risk-medium';
            newRecommendation = 'Approve with Conditions';
            newRecommendationClass = 'recommend-review';
            newNotes = 'Member has good payment history but existing loan obligations.';
            newAssessedBy = 'Juan Dela Cruz';
        } else {
            newRiskRating = 'High';
            newRiskClass = 'risk-high';
            newRecommendation = 'Review Further';
            newRecommendationClass = '';
            newNotes = 'Member has high debt-to-income ratio. Recommend further review.';
            newAssessedBy = 'Robert Reyes';
        }
        
        $('#mock_credit_score').text(newCreditScore);
        $('#mock_dti').text(newDti + '%');
        $('#mock_capacity').text('₱ ' + newPaymentCapacity.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        
        $('#mock_risk_badge').removeClass('risk-low risk-medium risk-high').addClass(newRiskClass).text(newRiskRating);
        
        $('#mock_recommendation_card').removeClass('recommend-approve recommend-review').addClass(newRecommendationClass);
        $('#mock_recommendation').text(newRecommendation);
        
        $('#mock_notes span').text(newNotes);
        $('#mock_assessed_by').text(newAssessedBy);
        $('#mock_assessment_date').text(new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }));
        
        alert('Credit assessment refreshed!\n\nNew Credit Score: ' + newCreditScore + '/100\nNew Risk Rating: ' + newRiskRating + '\nNew Recommendation: ' + newRecommendation);
    });
    <?php endif; ?>
});
</script>

<?php echo view('templates/myfooter.php'); ?>