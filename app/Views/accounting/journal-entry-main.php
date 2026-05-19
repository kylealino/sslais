<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$journal_id = $this->request->getPostGet('journal_id');

$journal_no = "";
$posting_date = "";
$reference_no = "";
$journal_type = "";
$remarks = "";
$status = "";
$approved_by = "";

if(!empty($journal_id) || !is_null($journal_id)) { 

    $query = $this->db->query("
    SELECT
        `journal_id`,
        `journal_no`,
        `posting_date`,
        `reference_no`,
        `journal_type`,
        `remarks`,
        `status`,
        `approved_by`,
        `created_by`,
        `created_at`
    FROM
        `tbl_journal`
    WHERE
        `journal_id` = '$journal_id'"
    );

    $data = $query->getRowArray();
    $journal_no = $data['journal_no'];
    $posting_date = $data['posting_date'];
    $reference_no = $data['reference_no'];
    $journal_type = $data['journal_type'];
    $remarks = $data['remarks'];
    $status = $data['status'];
    $approved_by = $data['approved_by'];
}

// Get all accounts from tbl_coa for autocomplete
$accounts = $this->db->query("SELECT account_code, account_name FROM tbl_coa WHERE is_active = 1 ORDER BY account_code")->getResultArray();

// Format for autocomplete
$accountList = [];
foreach($accounts as $acc) {
    $accountList[] = [
        'label' => $acc['account_code'] . ' - ' . $acc['account_name'],
        'value' => $acc['account_code'],
        'account_name' => $acc['account_name']
    ];
}

$journaldataquery = $this->db->query("
    SELECT
        `journal_id`,
        `journal_no`,
        `posting_date`,
        `reference_no`,
        `journal_type`,
        `remarks`,
        `status`,
        `approved_by`,
        `created_by`,
        `created_at`
    FROM
        `tbl_journal`
    ORDER BY
        journal_id DESC
");
$journaldata = $journaldataquery->getResultArray();

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

    /* Status Pills */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 30px;
        letter-spacing: 0.3px;
    }

    .status-pill::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-active {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    .status-active::before { background: #10b981; }

    .status-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    .status-warning::before { background: #f59e0b; }

    .status-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    .status-danger::before { background: #ef4444; }

    /* Buttons */
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

    .btn-update {
        background: var(--navy-dark);
        border: none;
        border-radius: 10px;
        padding: 8px 24px;
        font-size: 12px;
        font-weight: 600;
        color: white;
        transition: all 0.2s;
    }

    .btn-update:hover {
        background: var(--navy-medium);
        transform: translateY(-1px);
    }

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
    }

    /* Journal Lines Table */
    .table-journal {
        width: 100%;
        border-collapse: collapse;
    }

    .table-journal thead th {
        background: var(--gray-50);
        color: var(--gray-600);
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 12px 8px;
        border-bottom: 1px solid var(--gray-200);
        text-align: center;
    }

    .table-journal tbody td {
        padding: 8px;
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
    }

    .table-journal tbody tr:hover td {
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
        width: 250px;
    }

    .dataTables_paginate .paginate_button.current {
        background: var(--gold-primary) !important;
        border-color: var(--gold-primary) !important;
        color: var(--navy-dark) !important;
    }

    /* jQuery UI Autocomplete */
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid var(--gray-200);
        border-radius: 10px;
        background: var(--white-bg);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 9999;
    }

    .ui-autocomplete .ui-menu-item-wrapper {
        padding: 8px 12px;
        font-size: 12px;
        cursor: pointer;
    }

    .ui-autocomplete .ui-menu-item-wrapper:hover {
        background: var(--gold-soft);
        color: var(--gold-dark);
    }

    .ui-autocomplete .ui-state-active {
        background: var(--gold-primary);
        color: var(--navy-dark);
        border: none;
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

    /* Table for Journal List */
    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background: var(--gray-50);
        color: var(--gray-600);
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 12px 8px;
        border-bottom: 1px solid var(--gray-200);
    }

    .table tbody td {
        padding: 10px 8px;
        color: var(--gray-700);
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
    }

    .table tbody tr:hover td {
        background: var(--gold-soft);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 16px;
        }
        .dataTables_filter {
            float: none;
            text-align: center;
            margin-bottom: 15px;
        }
        .dataTables_filter input {
            width: 100%;
        }
        .btn-save, .btn-update {
            width: 100%;
        }
        .table-journal {
            font-size: 12px;
        }
        .table-journal thead th,
        .table-journal tbody td {
            padding: 6px 4px;
        }
    }
</style>

<div class="container-fluid">
    <div class="row me-myjournalentry-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    
    <div class="row mb-2 mt-2">
        <div class="col-12">
            <h4 class="fw-semibold my-3">Journal Entry</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="text-muted text-decoration-none" href="<?=site_url();?>mydashboard">
                            <i class="ti ti-home fs-5"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">Accounting</li>
                    <li class="breadcrumb-item active">Journal Entry</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Journal Entry Form Card -->
    <div class="card">
        <div class="card-header">
            <h6 class="fw-semibold mb-0">
                <i class="ti ti-pencil me-2" style="color: var(--gold-primary);"></i>
                <?= empty($journal_id) ? 'Add Journal Entry' : 'Edit Journal Entry' ?>
            </h6>
        </div>
        <div class="card-body">
            <form class="myjournalentry-validation" id="journalForm">
                <input type="hidden" id="journal_id" name="journal_id" value="<?=$journal_id;?>">
                
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Journal No.</label>
                            <input type="text" id="journal_no" name="journal_no" value="<?=$journal_no;?>" class="form-control form-control-sm" placeholder="Auto-generated if left empty">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Posting Date</label>
                            <input type="date" id="posting_date" name="posting_date" value="<?=$posting_date;?>" class="form-control form-control-sm">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reference No.</label>
                            <input type="text" id="reference_no" name="reference_no" value="<?=$reference_no;?>" class="form-control form-control-sm" placeholder="OR, SI, DR, CR, etc.">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Journal Type</label>
                            <select id="journal_type" name="journal_type" class="form-select form-select-sm">
                                <option value="">Select Type</option>
                                <option value="General" <?=($journal_type=='General')?'selected':''?>>General</option>
                                <option value="Sales" <?=($journal_type=='Sales')?'selected':''?>>Sales</option>
                                <option value="Purchase" <?=($journal_type=='Purchase')?'selected':''?>>Purchase</option>
                                <option value="Cash Receipt" <?=($journal_type=='Cash Receipt')?'selected':''?>>Cash Receipt</option>
                                <option value="Cash Disbursement" <?=($journal_type=='Cash Disbursement')?'selected':''?>>Cash Disbursement</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea id="remarks" name="remarks" rows="4" class="form-control form-control-sm"><?=$remarks;?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select id="status" name="status" class="form-select form-select-sm">
                                <option value="Draft" <?=($status=='Draft')?'selected':''?>>Draft</option>
                                <option value="Posted" <?=($status=='Posted')?'selected':''?>>Posted</option>
                                <option value="Cancelled" <?=($status=='Cancelled')?'selected':''?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Approved By</label>
                            <input type="text" id="approved_by" name="approved_by" value="<?=$approved_by;?>" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                <!-- Journal Lines Section -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0">Journal Lines</label>
                        <button type="button" class="btn btn-primary btn-sm" onclick="__mysys_journal_ent.my_add_journal_line()">
                            <i class="ti ti-plus"></i> Add Line
                        </button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table-journal w-100" id="journal_line_items">
                            <thead>
                                <tr>
                                    <th width="50"></th>
                                    <th>Account Code</th>
                                    <th>Account Name</th>
                                    <th width="120">Debit</th>
                                    <th width="120">Credit</th>
                                    <th>Description</th>
                                    <th width="100">Cost Center</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- HIDDEN TEMPLATE ROW - ONLY ONE -->
                                <tr style="display:none;">
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-sm text-danger" onclick="$(this).closest('tr').remove();">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm text-success" onclick="__mysys_journal_ent.my_add_journal_line_above(this);">
                                                <i class="ti ti-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td><input type="text" name="account_code" class="account_code form-control form-control-sm" autocomplete="off"></td>
                                    <td><input type="text" name="account_name" class="account_name form-control form-control-sm" readonly style="background:var(--gray-50);"></td>
                                    <td><input type="number" step="0.01" name="debit_amount" class="debit_amount form-control form-control-sm text-end"></td>
                                    <td><input type="number" step="0.01" name="credit_amount" class="credit_amount form-control form-control-sm text-end"></td>
                                    <td><textarea name="description" rows="1" class="description form-control form-control-sm"></textarea></td>
                                    <td><input type="text" name="cost_center" class="cost_center form-control form-control-sm"></td>
                                </tr>

                                <?php if(!empty($journal_id)):
                                    $query = $this->db->query("
                                        SELECT account_code, account_name, debit_amount, credit_amount, description, cost_center
                                        FROM tbl_journal_details
                                        WHERE journal_id = '$journal_id'
                                    ");
                                    $result = $query->getResultArray();
                                    foreach ($result as $data):
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-sm text-danger" onclick="$(this).closest('tr').remove();">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm text-success" onclick="__mysys_journal_ent.my_add_journal_line_above(this);">
                                                <i class="ti ti-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td><input type="text" name="account_code" class="account_code form-control form-control-sm" value="<?=$data['account_code'];?>" autocomplete="off"></td>
                                    <td><input type="text" name="account_name" class="account_name form-control form-control-sm" value="<?=$data['account_name'];?>" readonly style="background:var(--gray-50);"></td>
                                    <td><input type="number" step="0.01" name="debit_amount" class="debit_amount form-control form-control-sm text-end" value="<?=$data['debit_amount'];?>"></td>
                                    <td><input type="number" step="0.01" name="credit_amount" class="credit_amount form-control form-control-sm text-end" value="<?=$data['credit_amount'];?>"></td>
                                    <td><textarea name="description" rows="1" class="description form-control form-control-sm"><?=$data['description'];?></textarea></td>
                                    <td><input type="text" name="cost_center" class="cost_center form-control form-control-sm" value="<?=$data['cost_center'];?>"></td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-end mt-3">
                    <button type="submit" class="<?= empty($journal_id) ? 'btn-save' : 'btn-update' ?>">
                        <i class="ti ti-device-floppy me-1"></i>
                        <?= empty($journal_id) ? 'Save Journal' : 'Update Journal' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Journal List Card -->
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="fw-semibold mb-0">
                <i class="ti ti-list me-2" style="color: var(--gold-primary);"></i>
                Journal Entries
            </h6>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table mb-0">
                    <thead>
                        <tr>
                            <th width="50">Action</th>
                            <th>Journal No</th>
                            <th>Posting Date</th>
                            <th>Reference No</th>
                            <th>Journal Type</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Approved By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($journaldata)):
                            foreach ($journaldata as $data):
                                $jid = $data['journal_id'];
                                $jno = $data['journal_no'];
                                $pdate = $data['posting_date'];
                                $refno = $data['reference_no'];
                                $jtype = $data['journal_type'];
                                $rem = $data['remarks'];
                                $stat = $data['status'];
                                $appby = $data['approved_by'];
                                
                                $statusClass = '';
                                if($stat == 'Posted') $statusClass = 'status-active';
                                elseif($stat == 'Draft') $statusClass = 'status-warning';
                                else $statusClass = 'status-danger';
                        ?>
                        <tr>
                            <td class="text-center">
                                <a class="text-primary nav-icon-hover" href="myjournalentry?meaction=MAIN&journal_id=<?= $jid ?>" title="Edit Journal">
                                    <i class="ti ti-pencil"></i>
                                </a>
                            </td>
                            <td class="text-center"><strong><?=$jno;?></strong></td>
                            <td class="text-center"><?=date('Y-m-d', strtotime($pdate));?></td>
                            <td class="text-center"><?=$refno;?></td>
                            <td class="text-center"><?=$jtype;?></td>
                            <td class="text-center"><?=$rem;?></td>
                            <td class="text-center">
                                <span class="status-pill <?=$statusClass;?>"><?=$stat;?></span>
                            </td>
                            <td class="text-center"><?=$appby;?></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="<?=base_url('assets/js/accounting/myjournalentry.js?v=1');?>"></script>

<script>
// Account list for autocomplete
var accountList = <?= json_encode($accountList); ?>;

$(document).ready(function () {
    $('#datatablesSimple').DataTable({
        pageLength: 5,
        lengthChange: false,
        language: {
            search: "Search:"
        }
    });
    
    // Initialize autocomplete for existing rows
    $('.account_code').each(function() {
        if (!$(this).data("ui-autocomplete")) {
            $(this).autocomplete({
                source: accountList,
                minLength: 0,
                select: function (event, ui) {
                    let row = $(this).closest('tr');
                    $(this).val(ui.item.value);
                    row.find('.account_name').val(ui.item.account_name);
                    return false;
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>")
                    .append("<div class='ui-menu-item-wrapper'>" + item.label + "</div>")
                    .appendTo(ul);
            };
        }
    });
});

// Global autocomplete function for dynamically added rows
function initAccountAutocomplete(element) {
    if (element && !$(element).data("ui-autocomplete")) {
        $(element).autocomplete({
            source: accountList,
            minLength: 0,
            select: function (event, ui) {
                let row = $(this).closest('tr');
                $(this).val(ui.item.value);
                row.find('.account_name').val(ui.item.account_name);
                return false;
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            return $("<li>")
                .append("<div class='ui-menu-item-wrapper'>" + item.label + "</div>")
                .appendTo(ul);
        };
    }
}
</script>

<?php echo view('templates/myfooter.php'); ?>