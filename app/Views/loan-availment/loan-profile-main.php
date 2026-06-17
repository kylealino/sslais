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
        AND LOWER(payment_status) = 'paid'
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
            AND LOWER(payment_status) = 'paid'
            ORDER BY ammortization_id DESC LIMIT 1
        ", [$loan_id])->getRowArray();
        $outstanding = isset($balanceQuery['ending_balance']) ? (float)$balanceQuery['ending_balance'] : (float)$loan_amount;
        
        // Get amortization schedule - VIEW ONLY
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

    /* ===== MAIN CARD ===== */
    .main-card {
        border: 1px solid var(--gray-200);
        border-radius: 20px;
        background: var(--white-bg);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .main-card .card-header {
        background: var(--white-bg);
        border-bottom: 1px solid var(--gray-200);
        padding: 14px 24px;
    }

    .main-card .card-header h5 {
        font-weight: 700;
        color: var(--navy-dark);
        margin: 0;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .main-card .card-header h5 i {
        color: var(--gold-primary);
        font-size: 18px;
    }

    .main-card .card-body {
        padding: 24px;
    }

    /* ===== STATS CARDS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--white-bg);
        border-radius: 16px;
        border: 1px solid var(--gray-200);
        padding: 14px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
    }

    .stat-card:hover {
        border-color: var(--gold-primary);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .stat-card .stat-left .stat-label {
        font-size: 10px;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 2px;
    }

    .stat-card .stat-left .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--gray-800);
        line-height: 1.3;
    }

    .stat-card .stat-left .stat-sub {
        font-size: 10px;
        color: var(--gray-400);
        margin-top: 1px;
    }

    .stat-card .stat-icon {
        font-size: 28px;
        opacity: 0.1;
        color: var(--gold-primary);
        flex-shrink: 0;
        margin-left: 8px;
    }

    /* ===== SECTION TITLES ===== */
    .section-title {
        font-weight: 600;
        color: var(--navy-dark);
        font-size: 13px;
        letter-spacing: 0.5px;
        border-left: 3px solid var(--gold-primary);
        padding-left: 12px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        color: var(--gold-primary);
        font-size: 14px;
    }

    /* ===== INFO GRID ===== */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4px 30px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px solid var(--gray-100);
    }

    .info-row .label {
        color: var(--gray-500);
        font-size: 13px;
    }

    .info-row .value {
        color: var(--gray-800);
        font-weight: 500;
        font-size: 13px;
    }

    /* ===== DIVIDER ===== */
    .divider {
        height: 1px;
        background: var(--gray-200);
        margin: 20px 0;
    }

    /* ===== BREADCRUMB ===== */
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

    /* ===== TABS ===== */
    .nav-tabs-custom {
        display: flex;
        flex-wrap: wrap;
        border-bottom: 2px solid var(--gray-200);
        padding: 0 4px;
        background: var(--white-bg);
        margin-bottom: 24px;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        color: var(--gray-600);
        font-weight: 500;
        font-size: 13px;
        padding: 10px 20px;
        transition: all 0.2s;
        cursor: pointer;
        background: none;
        border-radius: 0;
        position: relative;
    }

    .nav-tabs-custom .nav-link i {
        margin-right: 6px;
        font-size: 14px;
    }

    .nav-tabs-custom .nav-link:hover {
        color: var(--gold-primary);
    }

    .nav-tabs-custom .nav-link.active {
        color: var(--gold-primary);
        font-weight: 600;
    }

    .nav-tabs-custom .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--gold-primary);
    }

    /* ===== BUTTONS ===== */
    .btn-primary {
        background: var(--navy-dark);
        border: none;
        border-radius: 8px;
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 600;
        color: white;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        background: var(--navy-medium);
        transform: translateY(-1px);
        color: white;
    }

    .btn-back {
        background: var(--gray-100);
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        padding: 6px 16px;
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

    .btn-view-schedule {
        background: var(--info);
        border: none;
        border-radius: 8px;
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 600;
        color: white;
        transition: all 0.2s;
    }

    .btn-view-schedule:hover {
        background: #2563eb;
        transform: translateY(-1px);
        color: white;
    }

    .btn-assess {
        background: var(--info);
        border: none;
        border-radius: 8px;
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 600;
        color: white;
        transition: all 0.2s;
    }

    .btn-assess:hover {
        background: #2563eb;
        transform: translateY(-1px);
        color: white;
    }

    /* ===== BADGES ===== */
    .status-pill {
        display: inline-block;
        padding: 4px 14px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 30px;
    }

    .status-pending { background: #fef3c7; color: #d97706; }
    .status-approved { background: #dbeafe; color: #1e40af; }
    .status-active { background: #d1fae5; color: #065f46; }
    .status-closed { background: #e2e8f0; color: #475569; }
    .status-declined { background: #fee2e2; color: #dc2626; }

    .badge-paid {
        background: #d1fae5;
        color: #065f46;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
    }

    .badge-unpaid {
        background: #fef3c7;
        color: #d97706;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
    }

    .risk-badge {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
    }

    .risk-low { background: #d1fae5; color: #065f46; }
    .risk-medium { background: #fef3c7; color: #d97706; }
    .risk-high { background: #fee2e2; color: #dc2626; }

    /* ===== TABLES ===== */
    .table-custom {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .table-custom thead th {
        background: var(--gray-50);
        color: var(--gray-500);
        font-weight: 600;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 14px;
        border-bottom: 2px solid var(--gray-200);
        text-align: left;
    }

    .table-custom tbody td {
        padding: 10px 14px;
        color: var(--gray-700);
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
    }

    .table-custom tbody tr:hover td {
        background: var(--gold-soft);
    }

    .table-custom tbody tr.success td {
        background: #d1fae5;
    }

    .table-custom .text-end { text-align: right; }
    .table-custom .text-center { text-align: center; }

    /* ===== EXPOSURE CARDS ===== */
    .exposure-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .exposure-card {
        background: var(--white-bg);
        border-radius: 12px;
        padding: 16px;
        border: 1px solid var(--gray-200);
        text-align: center;
        transition: all 0.2s;
    }

    .exposure-card:hover {
        border-color: var(--gold-primary);
    }

    .exposure-card .exposure-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray-500);
    }

    .exposure-card .exposure-value {
        font-size: 22px;
        font-weight: 700;
        color: var(--navy-dark);
        margin: 4px 0;
    }

    .exposure-card small {
        font-size: 11px;
        color: var(--gray-400);
    }

    /* ===== CO-MAKER HEADER ===== */
    .comaker-header {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
        border-radius: 12px;
        padding: 16px 24px;
        color: white;
        margin-bottom: 20px;
    }

    .comaker-header h5 {
        color: white;
        margin-bottom: 2px;
        font-size: 15px;
    }

    .comaker-header .text-muted {
        color: rgba(255,255,255,0.7) !important;
        font-size: 11px;
    }

    /* ===== ALERT BOX ===== */
    .alert-box {
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13px;
        border-left: 4px solid;
        margin-bottom: 16px;
    }

    .alert-box i { margin-right: 8px; }

    .alert-info {
        background: #dbeafe;
        border-color: #3b82f6;
        color: #1e40af;
    }

    .alert-warning {
        background: #fef3c7;
        border-color: #f59e0b;
        color: #92400e;
    }

    .alert-success {
        background: #d1fae5;
        border-color: #10b981;
        color: #065f46;
    }

    .alert-danger {
        background: #fee2e2;
        border-color: #ef4444;
        color: #991b1b;
    }

    /* ===== SUMMARY STATS ===== */
    .summary-stats {
        display: flex;
        gap: 24px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--gray-200);
        font-size: 12px;
        color: var(--gray-500);
        flex-wrap: wrap;
    }

    .summary-stats span i { margin-right: 4px; }

    /* ===== RISK ASSESSMENT - CARDLESS DESIGN ===== */

    /* Metrics Grid */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .metric-item {
        background: var(--white-bg);
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        padding: 16px 20px;
        text-align: center;
        transition: all 0.2s;
    }

    .metric-item:hover {
        border-color: var(--gold-primary);
    }

    .metric-item .metric-label {
        font-size: 10px;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .metric-item .metric-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--gray-800);
        line-height: 1.2;
    }

    .metric-item .metric-sub {
        font-size: 11px;
        color: var(--gray-400);
        margin-top: 2px;
    }

    /* Assessment Row */
    .assessment-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    .assessment-col {
        display: flex;
    }

    .recommendation-box {
        flex: 1;
        background: #d1fae5;
        border-left: 4px solid var(--success);
        border-radius: 12px;
        padding: 16px 20px;
        transition: all 0.2s;
    }

    .recommendation-box .recommendation-label {
        font-size: 10px;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .recommendation-box .recommendation-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--gray-800);
        margin: 2px 0;
    }

    .recommendation-box .recommendation-sub {
        font-size: 11px;
        color: var(--gray-500);
    }

    .assessor-box {
        flex: 1;
        background: var(--white-bg);
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        padding: 16px 20px;
        transition: all 0.2s;
    }

    .assessor-box:hover {
        border-color: var(--gold-primary);
    }

    .assessor-box .assessor-label {
        font-size: 10px;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .assessor-box .assessor-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--gray-800);
        margin: 2px 0;
    }

    .assessor-box .assessor-date {
        font-size: 12px;
        color: var(--gray-400);
    }

    /* Notes Section */
    .notes-section {
        background: var(--white-bg);
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .notes-section .notes-header {
        padding: 12px 18px;
        background: var(--gray-50);
        border-bottom: 1px solid var(--gray-200);
        font-weight: 600;
        color: var(--navy-dark);
        font-size: 13px;
    }

    .notes-section .notes-header i {
        margin-right: 8px;
    }

    .notes-section .notes-body {
        padding: 14px 18px;
        font-size: 13px;
        color: var(--gray-700);
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .notes-section .notes-body i {
        margin-top: 2px;
        flex-shrink: 0;
    }

    /* Criteria Box */
    .criteria-box {
        background: #dbeafe;
        border-left: 4px solid #3b82f6;
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 13px;
        color: #1e40af;
        margin-bottom: 16px;
    }

    .criteria-box i {
        margin-right: 8px;
    }

    /* ===== DATATABLES ===== */
    .dataTables_wrapper {
        font-family: 'Inter', sans-serif;
    }

    .dataTables_filter {
        float: right;
        margin-bottom: 16px;
    }

    .dataTables_filter input {
        border: 1.5px solid var(--gray-200);
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 13px;
        width: 220px;
        transition: all 0.2s;
    }

    .dataTables_filter input:focus {
        border-color: var(--gold-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .dataTables_paginate {
        float: right;
        margin-top: 16px;
    }

    .dataTables_paginate .paginate_button {
        padding: 4px 10px !important;
        margin: 0 2px !important;
        border-radius: 6px !important;
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
        margin-top: 16px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .exposure-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .info-grid {
            grid-template-columns: 1fr;
        }
        .metrics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .assessment-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .main-card .card-header {
            padding: 12px 16px;
        }
        .main-card .card-header h5 {
            font-size: 14px;
        }
        .main-card .card-body {
            padding: 16px;
        }

        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .stat-card {
            padding: 10px 12px;
        }
        .stat-card .stat-left .stat-value {
            font-size: 16px;
        }
        .stat-card .stat-icon {
            font-size: 22px;
        }

        .nav-tabs-custom .nav-link {
            font-size: 11px;
            padding: 8px 12px;
        }
        .nav-tabs-custom .nav-link i {
            font-size: 12px;
        }

        .exposure-grid {
            grid-template-columns: 1fr;
        }

        .metrics-grid {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .metric-item {
            padding: 12px 14px;
        }
        .metric-item .metric-value {
            font-size: 20px;
        }
        .recommendation-box {
            padding: 14px 16px;
        }
        .recommendation-box .recommendation-value {
            font-size: 18px;
        }
        .assessor-box {
            padding: 14px 16px;
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
            margin-bottom: 10px;
        }

        .info-row {
            font-size: 12px;
        }
        .info-row .label,
        .info-row .value {
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .stat-card {
            padding: 8px 10px;
        }
        .stat-card .stat-left .stat-value {
            font-size: 14px;
        }
        .stat-card .stat-left .stat-label {
            font-size: 9px;
        }
        .stat-card .stat-left .stat-sub {
            font-size: 9px;
        }
        .stat-card .stat-icon {
            font-size: 18px;
        }

        .main-card .card-header {
            padding: 10px 12px;
        }
        .main-card .card-header h5 {
            font-size: 13px;
        }
        .main-card .card-body {
            padding: 12px;
        }

        .nav-tabs-custom .nav-link {
            font-size: 10px;
            padding: 6px 8px;
        }
        .nav-tabs-custom .nav-link i {
            margin-right: 3px;
            font-size: 11px;
        }

        .metrics-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .metric-item {
            padding: 10px 12px;
        }
        .metric-item .metric-value {
            font-size: 17px;
        }
        .metric-item .metric-label {
            font-size: 9px;
        }
        .metric-item .metric-sub {
            font-size: 9px;
        }
        .notes-section .notes-body {
            font-size: 12px;
            padding: 10px 14px;
            flex-wrap: wrap;
        }

        .table-custom {
            font-size: 11px;
        }
        .table-custom thead th,
        .table-custom tbody td {
            padding: 6px 8px;
        }
    }
</style>

<div class="ps-3 pe-3">
    <div class="row me-myloanprofile-outp-msg mx-0"></div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

    <!-- ===== HEADER ===== -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="fw-semibold">Loan Profile</h4>
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
        <!-- ===== DASHBOARD VIEW ===== -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-left">
                    <div class="stat-label">Active Loans</div>
                    <div class="stat-value"><?= number_format($activeLoans); ?></div>
                    <div class="stat-sub"><?= number_format($totalMembers); ?> Members</div>
                </div>
                <div class="stat-icon"><i class="ti ti-file-invoice"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-left">
                    <div class="stat-label">Outstanding Balance</div>
                    <div class="stat-value">₱<?= number_format($totalOutstanding, 2); ?></div>
                    <div class="stat-sub">Total remaining</div>
                </div>
                <div class="stat-icon"><i class="ti ti-currency-peso"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-left">
                    <div class="stat-label">Daily Collections</div>
                    <div class="stat-value">₱<?= number_format($dailyCollections, 2); ?></div>
                    <div class="stat-sub"><?= date('M d, Y'); ?></div>
                </div>
                <div class="stat-icon"><i class="ti ti-calendar"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-left">
                    <div class="stat-label">Active Rate</div>
                    <div class="stat-value"><?= number_format(($totalLoans > 0 ? ($activeLoans / $totalLoans * 100) : 0), 1); ?>%</div>
                    <div class="stat-sub"><?= number_format($activeLoans); ?> of <?= number_format($totalLoans); ?></div>
                </div>
                <div class="stat-icon"><i class="ti ti-chart-bar"></i></div>
            </div>
        </div>

        <!-- ===== LOAN LIST ===== -->
        <div class="main-card">
            <div class="card-header">
                <h5><i class="ti ti-list me-2"></i> Loan Applications</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="loansTable" class="table-custom">
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
                                        <i class="ti ti-eye"></i> View
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
        <!-- ===== DETAIL VIEW ===== -->

        <!-- ===== STATS CARDS ===== -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-left">
                    <div class="stat-label">Member</div>
                    <div class="stat-value" style="font-size:16px;"><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></div>
                    <div class="stat-sub">#<?= $member['member_no']; ?></div>
                </div>
                <div class="stat-icon"><i class="ti ti-user"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-left">
                    <div class="stat-label">Loan Amount</div>
                    <div class="stat-value" style="color: var(--success);">₱<?= number_format((float)$loan_amount, 2); ?></div>
                    <div class="stat-sub"><?= $loan_type; ?></div>
                </div>
                <div class="stat-icon"><i class="ti ti-cash"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-left">
                    <div class="stat-label">Outstanding Balance</div>
                    <div class="stat-value" style="color: var(--danger);">₱<?= number_format($outstanding, 2); ?></div>
                    <div class="stat-sub">Remaining to pay</div>
                </div>
                <div class="stat-icon"><i class="ti ti-currency-peso"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-left">
                    <div class="stat-label">Loan Status</div>
                    <div>
                        <span class="status-pill 
                            <?= $status == 'Pending' ? 'status-pending' : ($status == 'Approved' ? 'status-approved' : ($status == 'Active' ? 'status-active' : ($status == 'Closed' ? 'status-closed' : 'status-declined'))) ?>">
                            <?= esc($status); ?>
                        </span>
                    </div>
                    <div class="stat-sub"><?= (int)$term_months; ?> months term</div>
                </div>
                <div class="stat-icon"><i class="ti ti-flag"></i></div>
            </div>
        </div>

        <!-- ===== MAIN CARD ===== -->
        <div class="main-card">
            <div class="card-header">
                <h5><i class="ti ti-file-description me-2"></i> Loan Details</h5>
            </div>
            <div class="card-body">
                <!-- ===== TABS ===== -->
                <ul class="nav-tabs-custom" id="loanProfileTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="loan-details-tab" data-bs-toggle="tab" data-bs-target="#loan-details" type="button" role="tab">
                            <i class="ti ti-file-description"></i> Loan Details & Amortization
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="comaker-exposure-tab" data-bs-toggle="tab" data-bs-target="#comaker-exposure" type="button" role="tab">
                            <i class="ti ti-handshake"></i> Co-Maker
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="credit-assessment-tab" data-bs-toggle="tab" data-bs-target="#credit-assessment" type="button" role="tab">
                            <i class="ti ti-shield"></i> Risk Assessment
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="payment-history-tab" data-bs-toggle="tab" data-bs-target="#payment-history" type="button" role="tab">
                            <i class="ti ti-history"></i> Payments
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    
                    <!-- ===== TAB 1: LOAN DETAILS & AMORTIZATION ===== -->
                    <div class="tab-pane fade show active" id="loan-details" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="section-title"><i class="ti ti-info-circle"></i> Loan Information</h6>
                                <div class="info-grid">
                                    <div class="info-row"><span class="label">Loan Type</span><span class="value"><?= esc($loan_type); ?></span></div>
                                    <div class="info-row"><span class="label">Loan Amount</span><span class="value">₱<?= number_format((float)$loan_amount, 2); ?></span></div>
                                    <div class="info-row"><span class="label">Interest Rate</span><span class="value"><?= number_format((float)$interest_rate, 2); ?>%</span></div>
                                    <div class="info-row"><span class="label">Term</span><span class="value"><?= (int)$term_months; ?> months</span></div>
                                    <div class="info-row"><span class="label">Start Date</span><span class="value"><?= date('F d, Y', strtotime($start_date)); ?></span></div>
                                    <div class="info-row"><span class="label">Maturity Date</span><span class="value"><?= date('F d, Y', strtotime($maturity_date)); ?></span></div>
                                    <div class="info-row"><span class="label">Co-maker</span><span class="value"><?= !empty($comaker_name) ? htmlspecialchars($comaker_name) : 'None' ?></span></div>
                                    <div class="info-row"><span class="label">Outstanding</span><span class="value" style="color: var(--danger);">₱<?= number_format($outstanding, 2); ?></span></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="section-title"><i class="ti ti-user"></i> Member Information</h6>
                                <div class="info-grid">
                                    <div class="info-row"><span class="label">Name</span><span class="value"><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></span></div>
                                    <div class="info-row"><span class="label">Member No.</span><span class="value"><?= $member['member_no']; ?></span></div>
                                    <div class="info-row"><span class="label">Contact</span><span class="value"><?= $member['contact_number'] ?? 'N/A'; ?></span></div>
                                    <div class="info-row"><span class="label">Email</span><span class="value"><?= $member['email'] ?? 'N/A'; ?></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- ===== AMORTIZATION SCHEDULE ===== -->
                        <div>
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <h6 class="section-title mb-0"><i class="ti ti-chart-bar"></i> Amortization Schedule</h6>
                                <a href="<?=site_url('mypaymentschedule?loan_id='.$loan_id);?>" class="btn-view-schedule">
                                    <i class="ti ti-calculator me-1"></i> Manage Schedule
                                </a>
                            </div>

                            <?php if(!empty($amortizationSched)): ?>
                            <div class="table-responsive">
                                <table class="table-custom">
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
                                        $totalPaid = 0;
                                        $totalUnpaid = 0;
                                        foreach($amortizationSched as $row): 
                                            $isPaid = isset($row['payment_status']) && strtolower($row['payment_status']) === 'paid';
                                            if($isPaid) $totalPaid++; else $totalUnpaid++;
                                        ?>
                                        <tr class="<?= $isPaid ? 'success' : ''; ?>">
                                            <td class="text-center"><?= (int)$row['period']; ?></td>
                                            <td><?= date('m/d/Y', strtotime($row['payment_date'])); ?></td>
                                            <td class="text-end">₱<?= number_format((float)$row['beginning_balance'], 2); ?></td>
                                            <td class="text-end">₱<?= number_format((float)$row['interest'], 2); ?></td>
                                            <td class="text-end">₱<?= number_format((float)$row['principal'], 2); ?></td>
                                            <td class="text-end">₱<?= number_format((float)$row['payment'], 2); ?></td>
                                            <td class="text-end">₱<?= number_format((float)$row['ending_balance'], 2); ?></td>
                                            <td class="text-center">
                                                <span class="<?= $isPaid ? 'badge-paid' : 'badge-unpaid' ?>">
                                                    <?= $isPaid ? 'Paid' : 'Unpaid' ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="summary-stats">
                                <span><i class="ti ti-check-circle text-success"></i> Paid: <?= $totalPaid ?></span>
                                <span><i class="ti ti-clock text-warning"></i> Unpaid: <?= $totalUnpaid ?></span>
                                <span><i class="ti ti-file"></i> Total: <?= count($amortizationSched) ?> periods</span>
                                <span class="text-success">Total Paid: ₱<?= number_format($loan_amount - $outstanding, 2) ?></span>
                                <span class="text-danger">Remaining: ₱<?= number_format($outstanding, 2) ?></span>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-4 text-muted border rounded-3">
                                <i class="ti ti-calculator fs-1 mb-3 d-block"></i>
                                <p class="mb-2">No amortization schedule generated yet.</p>
                                <a href="<?=site_url('mypaymentschedule?loan_id='.$loan_id);?>" class="btn-view-schedule">
                                    <i class="ti ti-calculator me-1"></i> Generate Schedule
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ===== TAB 2: CO-MAKER ===== -->
                    <div class="tab-pane fade" id="comaker-exposure" role="tabpanel">
                        <div class="comaker-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <small class="text-muted">CO-MAKER / GUARANTOR</small>
                                    <h5><?= !empty($comaker_name) ? htmlspecialchars($comaker_name) : 'No Co-maker Assigned' ?></h5>
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

                        <div class="exposure-grid">
                            <div class="exposure-card">
                                <div class="exposure-label">Total Exposure</div>
                                <div class="exposure-value">₱ <?= $mock_comaker_exposure['total_exposure'] ?></div>
                                <small>Amount liable for</small>
                            </div>
                            <div class="exposure-card">
                                <div class="exposure-label">Active Co-maker Loans</div>
                                <div class="exposure-value"><?= $mock_comaker_exposure['active_loans_as_comaker'] ?></div>
                                <small>Other loans as co-maker</small>
                            </div>
                            <div class="exposure-card">
                                <div class="exposure-label">Total Obligations</div>
                                <div class="exposure-value">₱ <?= $mock_comaker_exposure['total_comaker_obligations'] ?></div>
                                <small>Total guaranteed amount</small>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="main-card">
                                    <div class="card-header">
                                        <h5><i class="ti ti-currency-peso me-2" style="color: var(--gold-primary);"></i> Current Loan Exposure</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-grid">
                                            <div class="info-row"><span class="label">Loan Amount</span><span class="value">₱ <?= number_format($loan_amount, 2) ?></span></div>
                                            <div class="info-row"><span class="label">Outstanding</span><span class="value" style="color: var(--danger);">₱ <?= number_format($outstanding, 2) ?></span></div>
                                            <div class="info-row"><span class="label">Exposure %</span><span class="value"><?= $mock_comaker_exposure['exposure_percentage'] ?>%</span></div>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $mock_comaker_exposure['exposure_percentage'] ?>%;"></div>
                                        </div>
                                        <div class="alert-box alert-warning mt-3 mb-0">
                                            <i class="ti ti-alert-triangle"></i> Jointly liable for ₱ <?= number_format($outstanding, 2) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="main-card">
                                    <div class="card-header">
                                        <h5><i class="ti ti-chart-bar me-2" style="color: var(--gold-primary);"></i> Performance Metrics</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-grid">
                                            <div class="info-row">
                                                <span class="label">Payment Performance</span>
                                                <span class="value <?= $mock_comaker_exposure['payment_performance'] >= 80 ? 'text-success' : ($mock_comaker_exposure['payment_performance'] >= 50 ? 'text-warning' : 'text-danger') ?>">
                                                    <?= $mock_comaker_exposure['payment_performance'] ?>%
                                                </span>
                                            </div>
                                            <div class="info-row">
                                                <span class="label">Risk Contribution</span>
                                                <span class="value"><?= $mock_comaker_exposure['risk_contribution'] ?></span>
                                            </div>
                                            <div class="info-row">
                                                <span class="label">Co-maker Since</span>
                                                <span class="value"><?= date('M d, Y', strtotime($start_date)) ?></span>
                                            </div>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $mock_comaker_exposure['payment_performance'] ?>%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if(!empty($otherLoansAsComaker)): ?>
                        <div class="main-card mt-3">
                            <div class="card-header">
                                <h5><i class="ti ti-handshake me-2" style="color: var(--gold-primary);"></i> Other Loans as Co-maker</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table-custom">
                                        <thead>
                                            <tr>
                                                <th>Loan ID</th>
                                                <th>Borrower</th>
                                                <th class="text-end">Amount</th>
                                                <th class="text-end">Outstanding</th>
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
                                                <td><span class="status-pill <?= $other['status'] == 'Active' ? 'status-active' : 'status-pending' ?>"><?= $other['status'] ?></span></td>
                                                <td class="text-center">
                                                    <a href="<?= site_url('myloanprofile?meaction=MAIN&loan_id='.$other['loan_id']); ?>" class="btn btn-primary btn-sm"><i class="ti ti-eye"></i></a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="alert-box alert-danger mt-3">
                            <i class="ti ti-alert-triangle"></i>
                            <strong>Accountability Notice:</strong> As co-maker, you are equally responsible for loan repayment.
                        </div>
                    </div>

                    <!-- ===== TAB 3: RISK ASSESSMENT - CARDLESS ===== -->
                    <div class="tab-pane fade" id="credit-assessment" role="tabpanel">
                        <!-- Status Alert -->
                        <div class="alert-box alert-info">
                            <i class="ti ti-info-circle"></i>
                            <?php
                            if($status == 'Pending') echo '🔍 <strong>Step 1:</strong> Credit assessment in progress.';
                            elseif($status == 'Approved') echo '✅ <strong>Step 2:</strong> Assessment complete. Loan approved.';
                            elseif($status == 'Active') echo '💰 <strong>Step 3:</strong> Loan active and being paid.';
                            elseif($status == 'Closed') echo '🏁 <strong>Step 4:</strong> Loan fully paid and closed.';
                            elseif($status == 'Declined') echo '❌ <strong>Loan Declined.</strong>';
                            ?>
                        </div>

                        <!-- Metrics Grid -->
                        <div class="metrics-grid">
                            <div class="metric-item">
                                <div class="metric-label">Credit Score</div>
                                <div class="metric-value" id="mock_credit_score">85</div>
                                <div class="metric-sub">/ 100</div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-label">Risk Rating</div>
                                <div class="metric-value"><span class="risk-badge risk-low" id="mock_risk_badge">Low</span></div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-label">Debt-to-Income</div>
                                <div class="metric-value" id="mock_dti">28.0%</div>
                                <div class="metric-sub">Ideal ≤ 40%</div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-label">Payment Capacity</div>
                                <div class="metric-value" id="mock_capacity">₱25,000</div>
                                <div class="metric-sub">Monthly disposable</div>
                            </div>
                        </div>

                        <!-- Recommendation & Assessor -->
                        <div class="assessment-row">
                            <div class="assessment-col">
                                <div class="recommendation-box" id="mock_recommendation_card">
                                    <div class="recommendation-label">Recommendation</div>
                                    <div class="recommendation-value" id="mock_recommendation">Approve</div>
                                    <div class="recommendation-sub">Credit Committee Decision</div>
                                </div>
                            </div>
                            <div class="assessment-col">
                                <div class="assessor-box">
                                    <div class="assessor-label">Assessed By / Date</div>
                                    <div class="assessor-value" id="mock_assessed_by">Juan Dela Cruz</div>
                                    <div class="assessor-date" id="mock_assessment_date"><?= date('F d, Y') ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Assessment Notes -->
                        <div class="notes-section">
                            <div class="notes-header">
                                <i class="ti ti-clipboard" style="color: var(--gold-primary);"></i>
                                Assessment Notes
                            </div>
                            <div class="notes-body" id="mock_notes">
                                <i class="ti ti-file-text" style="color: var(--gold-primary);"></i>
                                <span>Excellent credit standing. Member has consistent savings and no delinquent records.</span>
                            </div>
                        </div>

                        <!-- Scoring Criteria -->
                        <div class="criteria-box">
                            <i class="ti ti-info-circle"></i>
                            <strong>Credit Scoring:</strong> 80-100 = Low risk | 60-79 = Moderate | 40-59 = High risk | Below 40 = Poor
                        </div>

                        <!-- Refresh Button -->
                        <div class="text-end mt-3">
                            <button type="button" class="btn-assess" id="refreshAssessmentBtn">
                                <i class="ti ti-refresh me-1"></i> Refresh (Mockup)
                            </button>
                        </div>
                    </div>

                    <!-- ===== TAB 4: PAYMENTS ===== -->
                    <div class="tab-pane fade" id="payment-history" role="tabpanel">
                        <?php if(!empty($payments)): ?>
                        <div class="table-responsive">
                            <table class="table-custom">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th class="text-end">Amount</th>
                                        <th>Processed By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($payments as $pay): ?>
                                    <tr>
                                        <td><?= date('m/d/Y', strtotime($pay['payment_date'])); ?></td>
                                        <td class="text-end">₱<?= number_format((float)$pay['total_payment'], 2); ?></td>
                                        <td><?= esc($pay['created_by']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="ti ti-credit-card fs-2 mb-2 d-block"></i>
                            No payments recorded yet.
                        </div>
                        <?php endif; ?>
                    </div>

                </div><!-- /tab-content -->
            </div><!-- /card-body -->
        </div><!-- /main-card -->

    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    if ($('#loansTable').length && !$.fn.DataTable.isDataTable('#loansTable')) {
        $('#loansTable').DataTable({
            pageLength: 10,
            lengthChange: true,
            order: [[0, 'desc']],
            language: {
                search: "Search:",
                info: "Showing _START_ to _END_ of _TOTAL_ loans",
                infoEmpty: "No loans found",
                lengthMenu: "Show _MENU_ loans"
            },
            dom: 'frtip'
        });
    }

    <?php if(!empty($loan_id)): ?>
    $('#refreshAssessmentBtn').click(function() {
        let newCreditScore = Math.floor(Math.random() * (95 - 55 + 1) + 55);
        let newDti = (Math.random() * (45 - 20) + 20).toFixed(1);
        let newPaymentCapacity = Math.floor(Math.random() * (35000 - 15000 + 1) + 15000);

        let newRiskRating = '', newRiskClass = '', newRecommendation = '', newRecommendationClass = '', newNotes = '', newAssessedBy = '';

        if (newCreditScore >= 80) {
            newRiskRating = 'Low';
            newRiskClass = 'risk-low';
            newRecommendation = 'Approve';
            newRecommendationClass = 'recommend-approve';
            newNotes = 'Excellent credit standing. Member has consistent savings and no delinquent records.';
            newAssessedBy = 'Maria Santos';
        } else if (newCreditScore >= 65) {
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
        $('#mock_capacity').text('₱' + newPaymentCapacity.toLocaleString('en-US', {minimumFractionDigits: 0}));

        $('#mock_risk_badge').removeClass('risk-low risk-medium risk-high').addClass(newRiskClass).text(newRiskRating);
        $('#mock_recommendation_card').removeClass('recommend-approve recommend-review').addClass(newRecommendationClass);
        $('#mock_recommendation').text(newRecommendation);
        $('#mock_notes span').text(newNotes);
        $('#mock_assessed_by').text(newAssessedBy);
        $('#mock_assessment_date').text(new Date().toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: 'numeric'}));
    });
    <?php endif; ?>
});
</script>

<?php echo view('templates/myfooter.php'); ?>