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
            <form action="<?=site_url();?>myloanavailment?meaction=LOAN-AVAILMENT-SAVE" method="post" class="myloanavailment-validation">
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
                <!-- BUTTONS -->
                <div class="row mt-3">
                    <div class="col-sm-12 text-end">
                        <button type="submit" class="btn bg-success-subtle text-success btn-sm">
                        Save Loan
                        </button>
                    </div>
                </div>
            </form>
            <div class="row mt-3">
                <!-- Button to generate amortization -->
                <button type="button" id="generateAmortization" class="btn bg-primary-subtle text-success btn-sm">
                    Generate Amortization
                </button>

                <!-- Table placeholder -->
                <div class="table-responsive mt-3">
                    <table class="table table-bordered ammortization-list" id="amortizationTable">
                        <thead>
                            <tr>
                                <th>Period</th>
                                <th>Payment Date</th>
                                <th>Beginning Balance</th>
                                <th>Interest</th>
                                <th>Principal</th>
                                <th>Payment</th>
                                <th>Ending Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                        <!-- Skeletal rows with placeholders -->
                            <tr>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/loan-availment/myloanavailment.js?v=1');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>

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
        alert("Please fill all loan details first.");
        return;
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


