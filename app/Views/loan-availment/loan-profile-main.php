<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

$loan_id = $this->request->getPostGet('loan_id');

$member_id = "";
$loan_type = "";
$loan_amount = "";
$interest_rate = "";
$term_months = "";
$start_date = "";
$maturity_date = "";
$loan_comakers = "";
$status = "Pending";

if(!empty($loan_id)) {
    $query = $this->db->query("SELECT * FROM tbl_loans WHERE loan_id = '$loan_id'");
    $data = $query->getRowArray();

    $member_id = $data['member_id'];
    $loan_type = $data['loan_type'];
    $loan_amount = $data['loan_amount'];
    $interest_rate = $data['interest_rate'];
    $term_months = $data['term_months'];
    $start_date = $data['start_date'];
    $maturity_date = $data['maturity_date'];
    $loan_comakers = $data['loan_comakers'];
    $status = $data['status'];
}

$members = $this->db->query("SELECT member_id, first_name, last_name FROM tbl_members")->getResultArray();

// Get dashboard statistics for when no loan_id is selected
$activeLoans = $this->db->query("
    SELECT COUNT(*) as total 
    FROM tbl_loans l
    WHERE l.status != 'Paid' AND l.status != 'Completed'
")->getRowArray()['total'];

$totalOutstanding = $this->db->query("
    SELECT COALESCE(SUM(loan_amount), 0) as total 
    FROM tbl_loans l
    WHERE l.status != 'Paid' AND l.status != 'Completed'
")->getRowArray()['total'];

$dailyCollections = $this->db->query("
    SELECT COALESCE(SUM(total_payment), 0) as total 
    FROM tbl_loans_payment 
    WHERE DATE(payment_date) = CURDATE()
")->getRowArray()['total'];

$totalMembers = $this->db->query("SELECT COUNT(*) as total FROM tbl_members")->getRowArray()['total'];
$totalLoans = $this->db->query("SELECT COUNT(*) as total FROM tbl_loans")->getRowArray()['total'];
$pendingLoans = $this->db->query("SELECT COUNT(*) as total FROM tbl_loans WHERE status = 'Pending'")->getRowArray()['total'];

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

    /* Attendance Card Style - Matching Your Other Modules */
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

    /* Regular Cards */
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

    /* Tables - Clean, No Borders */
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

    .dataTables_filter label {
        font-size: 12px;
        color: var(--gray-500);
        display: flex;
        align-items: center;
        gap: 8px;
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

    /* Alerts */
    .alert-info {
        background: var(--gold-soft);
        border-color: var(--gold-light);
        color: var(--gold-dark);
        border-radius: 10px;
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
        .btn-success {
            width: 100%;
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
            <h4 class="fw-semibold my-3">Loan Profile</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="text-muted text-decoration-none" href="<?=site_url();?>mydashboard">
                            <i class="ti ti-home fs-5"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">Loan Management</li>
                    <li class="breadcrumb-item active">Loan Profile</li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if(empty($loan_id)): ?>
        <!-- DASHBOARD CARDS - Attendance Card Style -->
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

        <!-- Loans Table -->
        <div class="row mt-2">
            <div class="col-12">
                <h6 class="dashboard-title">Loan Portfolio</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table mb-0">
                        <thead>
                            <tr>
                                <th style="display:none;">Loan ID</th>
                                <th>Member</th>
                                <th class="text-center">Total Loans</th>
                                <th class="text-end">Outstanding Balance</th>
                                <th class="text-center">Co-Makers</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $membersWithLoans = $this->db->query("
                            SELECT 
                                m.member_id,
                                m.first_name,
                                m.last_name,
                                l.loan_id,
                                l.loan_amount,
                                COUNT(DISTINCT l.loan_id) AS total_loans,
                                COUNT(DISTINCT CASE WHEN l.loan_comakers IS NOT NULL THEN l.loan_id END) AS co_maker_count,
                                l.loan_amount AS total_outstanding
                            FROM tbl_members m
                            JOIN tbl_loans l ON l.member_id = m.member_id
                            GROUP BY m.member_id, m.first_name, m.last_name, l.loan_id, l.loan_amount
                            ORDER BY l.loan_id DESC
                        ")->getResultArray();
                        
                        $membersGrouped = [];
                        foreach($membersWithLoans as $loan) {
                            $memberId = $loan['member_id'];
                            if(!isset($membersGrouped[$memberId])) {
                                $membersGrouped[$memberId] = [
                                    'member_id' => $loan['member_id'],
                                    'member_name' => $loan['first_name'] . ' ' . $loan['last_name'],
                                    'total_loans' => 0,
                                    'total_outstanding' => 0,
                                    'co_maker_count' => 0,
                                    'loan_id' => $loan['loan_id']
                                ];
                            }
                            $membersGrouped[$memberId]['total_loans'] += (int)$loan['total_loans'];
                            $membersGrouped[$memberId]['total_outstanding'] += (float)$loan['total_outstanding'];
                            $membersGrouped[$memberId]['co_maker_count'] += (int)$loan['co_maker_count'];
                        }
                        ?>
                        <?php foreach($membersGrouped as $member): ?>
                            <tr>
                                <td style="display:none;"><?= (int)$member['loan_id']; ?></td>
                                <td><strong><?= esc($member['member_name']); ?></strong></td>
                                <td class="text-center"><?= (int)$member['total_loans']; ?></td>
                                <td class="text-end">₱<?= number_format((float)$member['total_outstanding'], 2); ?></td>
                                <td class="text-center"><?= (int)$member['co_maker_count']; ?></td>
                                <td class="text-center">
                                    <a href="<?= site_url('myloanprofile?meaction=MAIN&loan_id='.$member['loan_id']); ?>" class="btn btn-primary btn-sm">
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
    <?php endif; ?>

    <?php if(!empty($loan_id)): ?>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- LEFT: LOAN SUMMARY -->
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">Loan Summary</div>
                        <div class="card-body">
                            <p><strong>Loan Type:</strong> <?= esc($loan_type); ?></p>
                            <p><strong>Loan Amount:</strong> ₱<?= number_format((float)$loan_amount, 2); ?></p>
                            <p><strong>Interest Rate:</strong> <?= number_format((float)$interest_rate, 2); ?>%</p>
                            <p><strong>Term:</strong> <?= (int)$term_months; ?> months</p>
                            <p><strong>Status:</strong> <span class="badge bg-warning"><?= esc($status); ?></span></p>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">Outstanding Balance</div>
                        <div class="card-body">
                            <?php
                            $balanceQuery = $this->db->query("
                                SELECT ending_balance 
                                FROM tbl_loans_ammortization 
                                WHERE loan_id = ? 
                                AND payment_status = 'Paid'
                                ORDER BY ammortization_id DESC LIMIT 1
                            ", [$loan_id])->getRowArray();

                            $outstanding = isset($balanceQuery['ending_balance']) ? (float)$balanceQuery['ending_balance'] : (float)$loan_amount;
                            ?>
                            <h3 class="text-danger">₱<?= number_format($outstanding, 2); ?></h3>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">Co-Maker</div>
                        <div class="card-body">
                            <?php
                            foreach($members as $m){
                                if($m['member_id'] == $loan_comakers){
                                    echo '<p class="mb-0"><strong>' . esc($m['first_name']) . ' ' . esc($m['last_name']) . '</strong></p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div class="col-md-8">
                    <!-- MAKE PAYMENT -->
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

                    <!-- AMORTIZATION SCHEDULE -->
                    <div class="card mb-3">
                        <div class="card-header">Amortization Schedule</div>
                        <div class="card-body table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">Period</th>
                                        <th>Date</th>
                                        <th class="text-end">Beginning</th>
                                        <th class="text-end">Interest</th>
                                        <th class="text-end">Principal</th>
                                        <th class="text-end">Payment</th>
                                        <th class="text-end">Ending</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sched = $this->db->query("
                                    SELECT * FROM tbl_loans_ammortization 
                                    WHERE loan_id = '$loan_id'
                                    ORDER BY period ASC
                                ")->getResultArray();

                                foreach($sched as $row):
                                    $isPaid = isset($row['payment_status']) && $row['payment_status'] === 'Paid';
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAYMENT HISTORY -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">Payment History</div>
                        <div class="card-body table-responsive">
                            <?php
                            $payments = $this->db->query("
                                SELECT payment_id, total_payment, payment_date, created_by
                                FROM tbl_loans_payment
                                WHERE loan_id = ?
                                ORDER BY payment_date ASC
                            ", [$loan_id])->getResultArray();
                            ?>
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
                                            <td colspan="3" class="text-center text-muted py-3">No payments yet</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/loan-availment/myloanprofile.js?v=1');?>"></script>

<script>
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#datatablesSimple')) {
        $('#datatablesSimple').DataTable({
            pageLength: 5,
            lengthChange: false,
            order: [[0, 'desc']],
            columnDefs: [{ targets: 0, visible: false }],
            language: { 
                search: "Search:",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No entries found"
            },
            dom: 'frtip'
        });
    }
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
    $('#ammortization_id').val(ammortizationId);
    
    $('#info_period').text(period);
    $('#info_principal').text(principal.toFixed(2));
    $('#info_interest').text(interest.toFixed(2));
    $('#selectedAmortizationInfo').show();
    
    $('#payButton').prop('disabled', false);
    
    $('.select-payment').closest('tr').removeClass('table-primary');
    $(this).closest('tr').addClass('table-primary');
});
</script>

<?php echo view('templates/myfooter.php'); ?>