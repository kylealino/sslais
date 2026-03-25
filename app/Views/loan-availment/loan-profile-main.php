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
// 1. Active Loans (loans that are not fully paid)
$activeLoans = $this->db->query("
    SELECT COUNT(*) as total 
    FROM tbl_loans l
    WHERE l.status != 'Paid' AND l.status != 'Completed'
    AND (
        SELECT COALESCE(SUM(principal), 0) 
        FROM tbl_loans_ammortization a 
        WHERE a.loan_id = l.loan_id AND a.payment_status = 'Paid'
    ) < l.loan_amount
")->getRowArray()['total'];

// 2. Total Outstanding Balance (sum of all remaining balances)
$totalOutstanding = $this->db->query("
    SELECT SUM(
        COALESCE((
            SELECT ending_balance 
            FROM tbl_loans_ammortization a2 
            WHERE a2.loan_id = l.loan_id 
            AND a2.payment_status = 'Paid'
            ORDER BY a2.ammortization_id DESC 
            LIMIT 1
        ), l.loan_amount)
    ) as total
    FROM tbl_loans l
    WHERE l.status != 'Paid' AND l.status != 'Completed'
")->getRowArray()['total'];

// 3. Daily Collections (today's total payments)
$dailyCollections = $this->db->query("
    SELECT COALESCE(SUM(principal), 0) as total 
    FROM tbl_loans_ammortization 
    WHERE payment_status = 'Paid' 
    AND DATE(payment_date) = CURDATE()
")->getRowArray()['total'];

// Additional stats for better dashboard
$totalMembers = $this->db->query("SELECT COUNT(*) as total FROM tbl_members")->getRowArray()['total'];
$totalLoans = $this->db->query("SELECT COUNT(*) as total FROM tbl_loans")->getRowArray()['total'];
$pendingLoans = $this->db->query("SELECT COUNT(*) as total FROM tbl_loans WHERE status = 'Pending'")->getRowArray()['total'];

echo view('templates/myheader.php');
?>
<style>
    .amortization-disabled {
        opacity: 0.4;
        pointer-events: none;
        filter: grayscale(100%);
    }
    
    /* Professional Dashboard Card Styles */
    .stat-card {
        background: #fff;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.04);
        transition: all 0.2s ease;
        border: 1px solid #e9ecef;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }
    
    .stat-card.primary::before {
        background: #4361ee;
    }
    
    .stat-card.success::before {
        background: #10b981;
    }
    
    .stat-card.warning::before {
        background: #f59e0b;
    }
    
    .stat-card.info::before {
        background: #3b82f6;
    }
    
    .stat-card.danger::before {
        background: #ef4444;
    }
    
    .stat-card .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }
    
    .stat-card .stat-title {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin: 0;
    }
    
    .stat-card .stat-icon {
        font-size: 1.25rem;
        color: #adb5bd;
    }
    
    .stat-card .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
        line-height: 1.2;
    }
    
    .stat-card .stat-value small {
        font-size: 0.75rem;
        font-weight: 400;
        color: #6c757d;
    }
    
    .stat-card .stat-footer {
        font-size: 0.7rem;
        color: #6c757d;
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        border-top: 1px solid #e9ecef;
    }
    
    .stat-card .stat-footer i {
        font-size: 0.7rem;
        margin-right: 0.25rem;
    }
    
    .dashboard-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    /* Table styling */
    .table {
        font-size: 0.85rem;
    }
    
    .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6;
    }
    
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
    }

    .table-primary {
        background-color: #e6f7ff !important;
    }
    
    .select-payment i {
        margin-right: 4px;
    }
    
    .alert-sm {
        font-size: 0.75rem;
        padding: 0.5rem;
    }
    
    .btn-sm i {
        font-size: 0.875rem;
    }
</style>

<div class="container-fluid">
    <div class="row me-myloanprofile-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    <div class="row mb-2 mt-0">
        <h4 class="fw-semibold mb-8">Loan Profile</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Loan Management</li>
                <li class="breadcrumb-item" aria-current="page">
                    <span class="form-label fw-bold">
                        Loan profile
                    </span>
                </li>
            </ol>
        </nav>
    </div>

    <?php if(empty($loan_id)):?>
        <!-- DASHBOARD VIEW WHEN NO LOAN_ID SELECTED -->
        <div class="row">
            <div class="col-12">
                <div class="dashboard-title">Key Metrics</div>
            </div>
        </div>
        
        <!-- Stats Cards Row - Compact and Professional -->
        <div class="row">
            <div class="col-md-4">
                <div class="stat-card primary">
                    <div class="stat-header">
                        <h6 class="stat-title">Active Loans</h6>
                        <i class="ti ti-file-invoice stat-icon"></i>
                    </div>
                    <div class="stat-value"><?= number_format($activeLoans); ?></div>
                    <div class="stat-footer">
                        <i class="ti ti-users"></i> <?= number_format($totalMembers); ?> Members &nbsp;|&nbsp;
                        <i class="ti ti-file"></i> <?= number_format($totalLoans); ?> Total
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stat-card success">
                    <div class="stat-header">
                        <h6 class="stat-title">Outstanding Balance</h6>
                        <i class="ti ti-currency-peso stat-icon"></i>
                    </div>
                    <div class="stat-value">₱<?= number_format($totalOutstanding, 2); ?></div>
                    <div class="stat-footer">
                        Total remaining balance across all active loans
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stat-card warning">
                    <div class="stat-header">
                        <h6 class="stat-title">Daily Collections</h6>
                        <i class="ti ti-calendar stat-icon"></i>
                    </div>
                    <div class="stat-value">₱<?= number_format($dailyCollections, 2); ?></div>
                    <div class="stat-footer">
                        <i class="ti ti-calendar-check"></i> <?= date('M d, Y'); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Optional: Secondary Stats Row - More Compact -->
        <div class="row mt-2">
            <div class="col-md-6">
                <div class="stat-card info">
                    <div class="stat-header">
                        <h6 class="stat-title">Pending Approval</h6>
                        <i class="ti ti-clock stat-icon"></i>
                    </div>
                    <div class="stat-value"><?= number_format($pendingLoans); ?> <small>loans</small></div>
                    <div class="stat-footer">
                        Loans awaiting approval
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="stat-card danger">
                    <div class="stat-header">
                        <h6 class="stat-title">Active Rate</h6>
                        <i class="ti ti-chart-bar stat-icon"></i>
                    </div>
                    <div class="stat-value"><?= number_format(($totalLoans > 0 ? ($activeLoans / $totalLoans * 100) : 0), 1); ?>%</div>
                    <div class="stat-footer">
                        <?= number_format($activeLoans); ?> of <?= number_format($totalLoans); ?> loans active
                    </div>
                </div>
            </div>
        </div>

        <!-- Loans Table Section -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="dashboard-title">Loan Portfolio</div>
            </div>
        </div>
        
        <div class="card">				
            <div class="card-body p-0">
                <div class="row mx-2 my-2">
                    <div class="col-12 table-responsive">
                        <table id="datatablesSimple" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="display:none;">Loan ID</th>
                                    <th>Member</th>
                                    <th>Total Loans</th>
                                    <th>Outstanding Balance</th>
                                    <th>Co-Makers</th>
                                    <th>Action</th>
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
                                    COALESCE((
                                        SELECT ending_balance 
                                        FROM tbl_loans_ammortization a2 
                                        WHERE a2.loan_id = l.loan_id 
                                        AND a2.payment_status = 'Paid'
                                        ORDER BY a2.ammortization_id DESC 
                                        LIMIT 1
                                    ), l.loan_amount) AS total_outstanding
                                FROM tbl_members m
                                JOIN tbl_loans l ON l.member_id = m.member_id
                                GROUP BY m.member_id, m.first_name, m.last_name, l.loan_id, l.loan_amount
                                ORDER BY l.loan_id DESC
                            ")->getResultArray();
                            
                            // Group by member to show one row per member
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
                                    <td><?= esc($member['member_name']); ?></td>
                                    <td><?= (int)$member['total_loans']; ?></td>
                                    <td>₱<?= number_format((float)$member['total_outstanding'], 2); ?></td>
                                    <td><?= (int)$member['co_maker_count']; ?></td>
                                    <td>
                                        <a href="<?= site_url('myloanprofile?meaction=MAIN&loan_id='.$member['loan_id']); ?>" class="btn btn-primary btn-sm">View Profile</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif;?>
        
        <div class="card">				
            <div class="card-body p-0">
                <?php if(!empty($loan_id)):?>
                    <div class="row">
                        <!-- LEFT: LOAN SUMMARY -->
                        <div class="col-md-4">
                            <!-- Loan Summary -->
                            <div class="card mb-3">
                                <div class="card-header fw-bold">Loan Summary</div>
                                <div class="card-body">

                                    <p><strong>Loan Type:</strong> <?= esc($loan_type); ?></p>
                                    <p><strong>Loan Amount:</strong> ₱<?= number_format((float)$loan_amount, 2); ?></p>
                                    <p><strong>Interest Rate:</strong> <?= number_format((float)$interest_rate, 2); ?>%</p>
                                    <p><strong>Term:</strong> <?= (int)$term_months; ?> months</p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-warning"><?= esc($status); ?></span>
                                    </p>

                                </div>
                            </div>

                            <!-- Outstanding Balance -->
                            <div class="card mb-3">
                                <div class="card-header fw-bold">Outstanding Balance</div>
                                <div class="card-body">
                                    <?php
                                    // Get the LATEST PAID amortization record
                                    $balanceQuery = $this->db->query("
                                        SELECT ending_balance 
                                        FROM tbl_loans_ammortization 
                                        WHERE loan_id = ? 
                                        AND payment_status = 'Paid'
                                        ORDER BY ammortization_id DESC 
                                        LIMIT 1
                                    ", [$loan_id])->getRowArray();

                                    if(isset($balanceQuery['ending_balance'])) {
                                        // If there are paid records, show the last paid ending balance
                                        $outstanding = (float)$balanceQuery['ending_balance'];
                                    } else {
                                        // If no payments made yet, show full loan amount
                                        $outstanding = (float)$loan_amount;
                                    }
                                    ?>

                                    <h4 class="text-danger">₱<?= number_format($outstanding, 2); ?></h4>
                                </div>
                            </div>

                            <!-- Co-Maker -->
                            <div class="card">
                                <div class="card-header fw-bold">Co-Maker</div>
                                <div class="card-body">
                                    <?php
                                    foreach($members as $m){
                                        if($m['member_id'] == $loan_comakers){
                                            echo '<p>' . esc($m['first_name']) . ' ' . esc($m['last_name']) . '</p>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT SIDE -->
                        <div class="col-md-8">
                            <!-- PAYMENT UI ONLY -->
                            <div class="card mb-3">
                                <div class="card-header fw-bold">Make Payment</div>
                                <div class="card-body">
                                    <form action="<?=site_url();?>myloanprofile?meaction=LOAN-PAYMENT-SAVE" method="post" class="myloanprofile-validation">
                                        <input type="hidden" name="loan_id" id="loan_id" value="<?= $loan_id; ?>">
                                        <input type="hidden" name="member_id" id="member_id" value="<?= $member_id; ?>">
                                        <input type="hidden" name="interest" id="interest">
                                        <input type="hidden" name="principal" id="principal">
                                        <input type="hidden" name="ammortization_id" id="ammortization_id">
                                        
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label>Payment Date</label>
                                                <input type="date" name="payment_date" id="payment_date" class="form-control form-control-sm" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Amount</label>
                                                <input type="number" step="0.01" name="amount" id="total_payment" class="form-control form-control-sm" readonly style="background: #f8fafc;" required>
                                            </div>
                                        </div>
                                        
                                        <!-- Display selected amortization info -->
                                        <div class="row mb-2" id="selectedAmortizationInfo" style="display: none;">
                                            <div class="col-12">
                                                <div class="alert alert-info alert-sm p-2 mb-0" style="font-size: 0.75rem;">
                                                    <i class="ti ti-info-circle"></i> 
                                                    <strong>Selected Payment:</strong> Period <span id="info_period">-</span> | 
                                                    Principal: ₱<span id="info_principal">0.00</span> | 
                                                    Interest: ₱<span id="info_interest">0.00</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success btn-sm" id="payButton" disabled>
                                                <i class="ti ti-credit-card"></i> Pay Amortization
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- AMORTIZATION TABLE -->
                            <div class="card mb-3">
                                <div class="card-header fw-bold">Amortization Schedule</div>
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>Period</th>
                                                <th>Date</th>
                                                <th>Beginning</th>
                                                <th>Interest</th>
                                                <th>Principal</th>
                                                <th>Payment</th>
                                                <th>Ending</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sched = $this->db->query("
                                            SELECT * 
                                            FROM tbl_loans_ammortization 
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
                                                        <span class="badge bg-success text-white"><i class="ti ti-flag"></i> Paid</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary"><i class="ti ti-flag"></i> Unpaid</span>
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
                                                                data-beginning-balance="<?= (float)$row['beginning_balance']; ?>"
                                                                data-interest="<?= (float)$row['interest']; ?>"
                                                                data-principal="<?= (float)$row['principal']; ?>"
                                                                data-ending-balance="<?= (float)$row['ending_balance']; ?>"
                                                                title="Select this payment">
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

                        <div class="col-sm-12">
                            <!-- PAYMENT HISTORY UI -->
                            <div class="card">
                                <div class="card-header fw-bold">Payment History</div>
                                    <div class="card-body table-responsive">
                                        <?php
                                        $payments = $this->db->query("
                                            SELECT 
                                                payment_id,
                                                loan_id,
                                                member_id,
                                                interest,
                                                principal,
                                                total_payment,
                                                payment_date,
                                                created_by,
                                                created_at
                                            FROM tbl_loans_payment
                                            WHERE loan_id = ?
                                            ORDER BY payment_date ASC
                                        ", [$loan_id])->getResultArray();
                                        ?>

                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Processed By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(!empty($payments)): ?>
                                                    <?php foreach($payments as $pay): ?>
                                                        <tr>
                                                            <td><?= date('m/d/Y', strtotime($pay['payment_date'])); ?></td>
                                                            <td>₱<?= number_format((float)$pay['total_payment'], 2); ?></td>
                                                            <td><?= esc($pay['created_by']); ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">
                                                            No payments yet
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>

                                    </div>
                            </div>
                        </div>
                    </div>
                <?php endif;?>
            </div>
        </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/loan-availment/myloanprofile.js?v=1');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>

<script>
    __mysys_loanprofile_ent.__loanprofile_saving();
    $(document).ready(function () {
        $('#datatablesSimple').DataTable({
            pageLength: 5,
            lengthChange: false,
            order: [[0, 'desc']], // sort by hidden loan_id
            columnDefs: [
                { targets: 0, visible: false } // hide loan_id column
            ],
            language: {
                search: "Search:"
            }
        });

        // Handle select payment button click
    $('.select-payment').on('click', function() {
        // Get data from button
        let period = $(this).data('period');
        let paymentDate = $(this).data('payment-date');
        let amount = $(this).data('amount');
        let interest = $(this).data('interest');
        let principal = $(this).data('principal');
        let ammortization = $(this).data('ammortization-id');
        
        // Set values in payment form
        $('#payment_date').val(paymentDate);
        $('#total_payment').val(amount.toFixed(2));
        
        // Set hidden fields
        $('#hidden_period').val(period);
        $('#interest').val(interest);
        $('#principal').val(principal);
        $('#ammortization_id').val(ammortization);
        

        $('#info_principal').text(principal.toFixed(2));
        $('#info_interest').text(interest.toFixed(2));
        $('#selectedAmortizationInfo').show();
        
        // Enable pay button
        $('#payButton').prop('disabled', false);
        
        // Highlight selected row in table
        $('.select-payment').closest('tr').removeClass('table-primary');
        $(this).closest('tr').addClass('table-primary');
        
    });
    
    // Optional: Clear selection if payment date is manually changed
    $('#payment_date, #total_payment').on('change keyup', function() {
        if ($(this).val() !== '') {
            // Optionally reset selection
            // $('#payButton').prop('disabled', true);
            // $('#selectedAmortizationInfo').hide();
        }
    });
    });
</script>


<?php
    echo view('templates/myfooter.php');
?>