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
      .amortization-disabled {
        opacity: 0.4;
        pointer-events: none;
        filter: grayscale(100%);
    }
    /* Professional Table Styles */
.table-professional {
    width: 100%;
    font-size: 0.8125rem;
    border-collapse: collapse;
    background: #fff;
}

.table-professional thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.875rem 1rem;
    border-bottom: 2px solid #e2e8f0;
    border-top: none;
}

.table-professional tbody td {
    padding: 0.75rem 1rem;
    color: #1e293b;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

.table-professional tbody tr:hover {
    background: #fafbfc;
}

.table-professional tbody tr:last-child td {
    border-bottom: none;
}

.table-professional tbody tr:last-child td:first-child {
    border-bottom-left-radius: 8px;
}

.table-professional tbody tr:last-child td:last-child {
    border-bottom-right-radius: 8px;
}

.text-end {
    text-align: right !important;
}

.text-center {
    text-align: center !important;
}

/* Professional Generate Button */
.btn-generate {
    background: #fff;
    border: 1px solid #4361ee;
    color: #4361ee;
    padding: 0.5rem 1.25rem;
    font-size: 0.8125rem;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-generate:hover {
    background: #4361ee;
    color: #fff;
    border-color: #4361ee;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(67,97,238,0.15);
}

.btn-generate:active {
    transform: translateY(0);
}

/* Loading state for button */
.btn-generate.loading {
    pointer-events: none;
    opacity: 0.7;
}

.btn-generate.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Amount formatting */
.amount-positive {
    color: #10b981;
    font-weight: 500;
}

.amount-negative {
    color: #ef4444;
    font-weight: 500;
}

.amount-zero {
    color: #94a3b8;
}

/* Status badges in table */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    font-size: 0.7rem;
    font-weight: 500;
    border-radius: 4px;
}

.status-paid {
    background: #d1fae5;
    color: #065f46;
}

.status-unpaid {
    background: #fee2e2;
    color: #991b1b;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-professional {
        font-size: 0.75rem;
    }
    
    .table-professional thead th,
    .table-professional tbody td {
        padding: 0.5rem 0.75rem;
    }
    
    .btn-generate {
        padding: 0.4rem 1rem;
        font-size: 0.75rem;
    }
}
</style>
<div class="container-fluid">
    <div class="row me-myloanavailment-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    <div class="row mb-2 mt-0">
        <h4 class="fw-semibold mb-8">Loan Availment</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Loan Management</li>
            <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">Loan Availment</span></li>
            </ol>
        </nav>
    </div>
    <div class="card">
        <div class="card-header  p-1">
            <div class="row">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 fw-semibold d-flex align-items-center">
                        <i class="ti ti-pencil fs-5 me-1"></i>
                        <span class="pt-1">Add new loan</span>
                    </h6>
                </div>
            </div>
		</div>						
        <div class="card-body p-0 px-4 py-2 my-2">
            <form action="<?=site_url();?>myloanavailment?meaction=LOAN-AVAILMENT-SAVE" method="post" id="loanForm" class="myloanavailment-validation">
                <input type="hidden" name="loan_id" id="loan_id" value="<?=$loan_id;?>">
                <div class="row">
                    <!-- LEFT -->
                    <div class="col-sm-6">
                        <!-- MEMBER -->
                        <div class="row mb-2">
                            <div class="col-sm-4">Member:</div>
                            <div class="col-sm-8">
                                <select name="member_id" id="member_id" class="form-control form-control-sm">
                                <option value="">-- Select --</option>
                                <?php foreach($members as $m): ?>
                                <option value="<?=$m['member_id'];?>" <?=($member_id==$m['member_id'])?'selected':'';?>>
                                <?=$m['first_name'].' '.$m['last_name'];?>
                                </option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!-- LOAN TYPE -->
                        <div class="row mb-2">
                            <div class="col-sm-4">Loan Type:</div>
                            <div class="col-sm-8">
                                <select name="loan_type" id="loan_type" class="form-control form-control-sm">
                                <option value="">-- Select --</option>
                                <option <?=($loan_type=='Personal Loan')?'selected':'';?>>Personal Loan</option>
                                <option <?=($loan_type=='Home Loan')?'selected':'';?>>Home Loan</option>
                                <option <?=($loan_type=='Auto Loan')?'selected':'';?>>Auto Loan</option>
                                <option <?=($loan_type=='Business Loan')?'selected':'';?>>Business Loan</option>
                                </select>
                            </div>
                        </div>
                        <!-- AMOUNT -->
                        <div class="row mb-2">
                            <div class="col-sm-4">Loan Amount:</div>
                            <div class="col-sm-8">
                            <input type="number" name="loan_amount"  id="loan_amount" value="<?=$loan_amount;?>" class="form-control form-control-sm">
                            </div>
                        </div>
                        <!-- INTEREST -->
                        <div class="row mb-2">
                            <div class="col-sm-4">Interest Rate:</div>
                            <div class="col-sm-8">
                            <input type="number" step="0.01" name="interest_rate"  id="interest_rate" value="<?=$interest_rate;?>" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT -->
                    <div class="col-sm-6">

                        <!-- TERM -->
                        <div class="row mb-2">
                            <div class="col-sm-4">Term:</div>
                            <div class="col-sm-8">
                                <select name="term_months" id="term_months" class="form-control form-control-sm">
                                <?php foreach([6,12,24,36,48,60] as $t): ?>
                                <option value="<?=$t;?>" <?=($term_months==$t)?'selected':'';?>><?=$t;?> months</option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- START DATE -->
                        <div class="row mb-2">
                            <div class="col-sm-4">Start Date:</div>
                            <div class="col-sm-8">
                            <input type="date" name="start_date" id="start_date" value="<?=$start_date;?>" class="form-control form-control-sm">
                            </div>
                        </div>

                        <!-- MATURITY DATE -->
                        <div class="row mb-2">
                            <div class="col-sm-4">Maturity Date:</div>
                            <div class="col-sm-8">
                            <input type="date" name="maturity_date" id="maturity_date" value="<?=$maturity_date;?>" class="form-control form-control-sm">
                            </div>
                        </div>

                        <!-- CO-MAKER -->
                        <div class="row mb-2">
                            <div class="col-sm-4">Co-maker:</div>
                            <div class="col-sm-8">
                                <select name="loan_comakers" id="loan_comakers" class="form-control form-control-sm">
                                    <option value="">-- Select --</option>
                                    <?php foreach($members as $m): ?>
                                    <option value="<?=$m['member_id'];?>" <?=($loan_comakers==$m['member_id'])?'selected':'';?>>
                                    <?=$m['first_name'].' '.$m['last_name'];?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- STATUS -->
                        <div class="row mb-2">
                            <div class="col-sm-4">Status:</div>
                            <div class="col-sm-8">
                                <select name="status" id="status" class="form-control form-control-sm" style="pointer-events:none; background:#e9ecef;" readonly>
                                <option value="<?=$status;?>" <?=($status=='Pending')?'selected':'';?>>Pending</option>
                                <option value="<?=$status;?>" <?=($status=='Approved')?'selected':'';?>>Approved</option>
                                <option value="<?=$status;?>" <?=($status=='Active')?'selected':'';?>>Active</option>
                                <option value="<?=$status;?>" <?=($status=='Closed')?'selected':'';?>>Closed</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Amortization Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0 fw-semibold" style="color: #1e293b;">
                                    <i class="ti ti-chart-bar me-2" style="color: #4361ee;"></i>
                                    Amortization Schedule
                                </h6>
                                <small class="text-muted" id="scheduleInfo" style="font-size: 0.7rem;">
                                    Generate payment schedule based on loan details
                                </small>
                            </div>
                            <button type="button" id="generateAmortization" class="btn btn-generate">
                                <i class="ti ti-calculator me-1"></i>
                                Generate Schedule
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="table-responsive" style="border-radius: 8px; border: 1px solid #e9ecef; overflow-x: auto;">
                            <table class="table table-professional mb-0 ammortization-list" id="amortizationTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 60px;">#</th>
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
                                        <td colspan="7" class="text-center py-5" style="background: #fafbfc;">
                                            <i class="ti ti-chart-bar fs-1 text-muted d-block mb-2" style="opacity: 0.5;"></i>
                                            <p class="text-muted mb-1" style="font-size: 0.875rem;">No amortization schedule generated yet</p>
                                            <small class="text-muted">Fill in loan details and click "Generate Schedule" to view payment plan</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- BUTTONS -->
                <div class="row mt-3">
                    <div class="col-sm-12 text-end">
                        <button type="submit" class="btn bg-success-subtle text-success btn-sm">
                        <i class="ti ti-brand-doctrine mt-1 fs-4 me-1"></i> Save Loan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/loan-availment/myloanavailment.js?v=1');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>

<script>
    let amortizationGenerated = false;

    const table = document.getElementById('amortizationTable');
    const form = document.getElementById('loanForm');
    const saveBtn = document.getElementById('saveLoanBtn');

    // Initially disable table visually
    table.classList.add('amortization-disabled');

    // Generate button click
    document.getElementById('generateAmortization').addEventListener('click', function () {
        amortizationGenerated = true;

        table.classList.remove('amortization-disabled');

    });

    // 🔥 HARD BLOCK SUBMIT
    form.addEventListener('submit', function (e) {
        if (!amortizationGenerated) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation(); // 🔥 critical

            showToast('Please generate amortization first before saving.', 'error');

            return false; // 🔥 ensures no further execution
        }
    }, true); // 🔥 capture phase (runs FIRST)

    function showToast(message, type) {
        console.log(type.toUpperCase() + ': ' + message);

        if (type === 'error') {
            toastr.error(message);
        } else {
            toastr.success(message);
        }
    }
</script>

<script>
    __mysys_loanavailment_ent.__loanavailment_saving();
    document.getElementById('generateAmortization').addEventListener('click', function() {

    // Collect form values
    let loanAmount = parseFloat(document.querySelector('input[name="loan_amount"]').value);
    let annualRate = parseFloat(document.querySelector('input[name="interest_rate"]').value);
    let termMonths = parseInt(document.querySelector('select[name="term_months"]').value);
    let startDate = new Date(document.querySelector('input[name="start_date"]').value);

    
    if (!loanAmount || !annualRate || !termMonths || isNaN(startDate)) {
        showToast('Please fill all loan details first.', 'Oops');
        table.classList.add('amortization-disabled');
        return;
    }

    function showToast(message, type) {
        console.log(type.toUpperCase() + ': ' + message);

         toastr.error(message);

    }
    // Calculate monthly payment
    let monthlyRate = annualRate / 12 / 100;
    let payment = loanAmount * monthlyRate / (1 - Math.pow(1 + monthlyRate, -termMonths));

    let balance = loanAmount;

    // Clear previous table rows
    let tbody = document.querySelector('#amortizationTable tbody');
    tbody.innerHTML = '';

    // Generate rows
    for (let period = 1; period <= termMonths; period++) {
        let interest = balance * monthlyRate;
        let principal = payment - interest;
        let endingBalance = balance - principal;

        let row = `<tr>
            <td>${period}</td>
            <td>${startDate.toISOString().split('T')[0]}</td>
            <td>${balance.toFixed(2)}</td>
            <td>${interest.toFixed(2)}</td>
            <td>${principal.toFixed(2)}</td>
            <td>${payment.toFixed(2)}</td>
            <td>${endingBalance.toFixed(2)}</td>
        </tr>`;

        tbody.insertAdjacentHTML('beforeend', row);

        balance = endingBalance;
        startDate.setMonth(startDate.getMonth() + 1);
    }
});
</script>
<?php
    echo view('templates/myfooter.php');
?>


