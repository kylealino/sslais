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

    .form-control:disabled, .form-control[readonly] {
        background: var(--gray-50);
        border-color: var(--gray-200);
        color: var(--gray-600);
    }

    /* Buttons */
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

    /* Table Styles */
    .table-professional {
        width: 100%;
        font-size: 12px;
        border-collapse: collapse;
    }

    .table-professional thead th {
        background: var(--gray-50);
        color: var(--gray-600);
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 10px;
        border-bottom: 1px solid var(--gray-200);
    }

    .table-professional tbody td {
        padding: 10px;
        color: var(--gray-700);
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
    }

    .table-professional tbody tr:hover td {
        background: var(--gold-soft);
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

    .text-end {
        text-align: right !important;
    }

    .text-center {
        text-align: center !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 18px;
        }
        .btn-save, .btn-generate {
            width: 100%;
            margin-top: 10px;
        }
        .table-professional {
            font-size: 11px;
        }
        .table-professional thead th,
        .table-professional tbody td {
            padding: 6px 8px;
        }
    }
</style>

<div class="pe-3 ps-3">
    <div class="row me-myloanavailment-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    
    <div class="row mb-2">
        <div class="col-12">
            <h4 class="fw-semibold">Loan Availment</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="text-muted text-decoration-none" href="<?=site_url();?>mydashboard">
                            <i class="ti ti-home fs-5"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">Loan Management</li>
                    <li class="breadcrumb-item active">Loan Availment</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card">
        <div class="card-header">
            <h6 class="fw-semibold mb-0">
                <i class="ti ti-cash me-2" style="color: var(--gold-primary);"></i>
                <?= empty($loan_id) ? 'Add New Loan' : 'Edit Loan' ?>
            </h6>
        </div>
        <div class="card-body">
            <form class="myloanavailment-validation" id="loanForm">
                <input type="hidden" name="loan_id" id="loan_id" value="<?=$loan_id;?>">
                
                <div class="row">
                    <!-- LEFT COLUMN -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Member</label>
                            <select name="member_id" id="member_id" class="form-select form-select-sm" required>
                                <option value="">-- Select Member --</option>
                                <?php foreach($members as $m): ?>
                                <option value="<?=$m['member_id'];?>" <?=($member_id==$m['member_id'])?'selected':'';?>>
                                    <?=$m['first_name'].' '.$m['last_name'];?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Loan Type</label>
                            <select name="loan_type" id="loan_type" class="form-select form-select-sm" required>
                                <option value="">-- Select --</option>
                                <option <?=($loan_type=='Personal Loan')?'selected':'';?>>Personal Loan</option>
                                <option <?=($loan_type=='Home Loan')?'selected':'';?>>Home Loan</option>
                                <option <?=($loan_type=='Auto Loan')?'selected':'';?>>Auto Loan</option>
                                <option <?=($loan_type=='Business Loan')?'selected':'';?>>Business Loan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Loan Amount</label>
                            <input type="number" name="loan_amount" id="loan_amount" value="<?=$loan_amount;?>" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Interest Rate (%)</label>
                            <input type="number" step="0.01" name="interest_rate" id="interest_rate" value="<?=$interest_rate;?>" class="form-control form-control-sm" required>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Term (Months)</label>
                            <select name="term_months" id="term_months" class="form-select form-select-sm" required>
                                <?php foreach([12,24,36,48,60] as $t): ?>
                                <option value="<?=$t;?>" <?=($term_months==$t)?'selected':'';?>><?=$t;?> months</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="<?=$start_date;?>" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Maturity Date</label>
                            <input type="date" name="maturity_date" id="maturity_date" value="<?=$maturity_date;?>" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Co-maker</label>
                            <select name="loan_comakers" id="loan_comakers" class="form-select form-select-sm">
                                <option value="">-- Select --</option>
                                <?php foreach($members as $m): ?>
                                <option value="<?=$m['member_id'];?>" <?=($loan_comakers==$m['member_id'])?'selected':'';?>>
                                    <?=$m['first_name'].' '.$m['last_name'];?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-select form-select-sm" style="pointer-events:none; background:var(--gray-50);">
                                <option value="Pending" <?=($status=='Pending')?'selected':'';?>>Pending</option>
                                <option value="Approved" <?=($status=='Approved')?'selected':'';?>>Approved</option>
                                <option value="Active" <?=($status=='Active')?'selected':'';?>>Active</option>
                                <option value="Closed" <?=($status=='Closed')?'selected':'';?>>Closed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Amortization Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h6 class="fw-semibold mb-0" style="color: var(--navy-dark);">
                                    <i class="ti ti-chart-bar me-2" style="color: var(--gold-primary);"></i>
                                    Amortization Schedule
                                </h6>
                                <small class="text-muted">Generate payment schedule based on loan details</small>
                            </div>
                            <button type="button" id="generateAmortization" class="btn-generate">
                                <i class="ti ti-calculator me-1"></i> Generate Schedule
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="table-responsive" style="border-radius: 12px; border: 1px solid var(--gray-200);">
                            <table class="table table-professional mb-0 ammortization-list" id="amortizationTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="50">#</th>
                                        <th>Payment Date</th>
                                        <th class="text-end">Beginning Balance</th>
                                        <th class="text-end">Interest</th>
                                        <th class="text-end">Principal</th>
                                        <th class="text-end">Payment</th>
                                        <th class="text-end">Ending Balance</th>
                                    </tr>
                                </thead>
                                <tbody id="amortizationTableBody">
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="ti ti-chart-bar fs-1 text-muted d-block mb-2" style="opacity: 0.5;"></i>
                                            <p class="text-muted mb-1">No amortization schedule generated yet</p>
                                            <small class="text-muted">Fill in loan details and click "Generate Schedule"</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- BUTTONS -->
                <div class="row mt-4">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn-save">
                            <i class="ti ti-device-floppy me-1"></i> Save Loan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/loan-availment/myloanavailment.js?v=1');?>"></script>

<?php
echo view('templates/myfooter.php');
?>