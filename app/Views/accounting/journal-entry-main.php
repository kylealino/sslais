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
echo view('templates/myheader.php');
?>
<style>
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 50px;
    letter-spacing: 0.3px;
}

/* Dot indicator */
.status-pill::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

/* ACTIVE (Green - subtle) */
.status-active {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
}
.status-active::before {
    background: #198754;
}

/* INACTIVE (Blue - subtle) */
.status-inactive {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}
.status-inactive::before {
    background: #0d6efd;
}
</style>
<div class="container-fluid">
    <div class="row me-myjournalentry-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    <div class="row mb-2 mt-0">
        <h4 class="fw-semibold mb-8">Journal Entry</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Accounting</li>
            <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">Journal Entry</span></li>
            </ol>
        </nav>
    </div>
    <div class="card">
        <div class="card-header  p-1">
            <div class="row">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 fw-semibold d-flex align-items-center">
                        <i class="ti ti-pencil fs-5 me-1"></i>
                        <span class="pt-1">Add entry</span>
                    </h6>
                </div>
            </div>
		</div>						
        <div class="card-body p-0 px-4 py-2 my-2">
            <form action="<?=site_url();?>myjournalentry?meaction=JOURNAL-ENTRY-SAVE" method="post" class="myjournalentry-validation">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-sm-6">
                        <div class="row mb-2 mt-2">
                            <div class="col-sm-4"><span>Journal No:</span></div>
                            <div class="col-sm-8">
                                <input type="hidden" id="journal_id" name="journal_id" value="<?=$journal_id;?>" class="form-control form-control-sm"/>
                                <input type="text" id="journal_no" name="journal_no" value="<?=$journal_no;?>" class="form-control form-control-sm"/>
                                <!-- Auto-generated -->
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4"><span>Posting Date:</span></div>
                            <div class="col-sm-8">
                                <input type="date" id="posting_date" name="posting_date" value="<?=$posting_date;?>" class="form-control form-control-sm"/>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4"><span>Reference No:</span></div>
                            <div class="col-sm-8">
                                <input type="text" id="reference_no" name="reference_no" value="<?=$reference_no;?>" class="form-control form-control-sm" placeholder="OR, SI, DR, CR, etc."/>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4"><span>Journal Type:</span></div>
                            <div class="col-sm-8">
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
                    </div>

                    <!-- Right Column -->
                    <div class="col-sm-6 my-2">
                        <div class="row mb-2">
                            <div class="col-sm-4"><span>Remarks:</span></div>
                            <div class="col-sm-8">
                                <textarea id="remarks" name="remarks" rows="3" class="form-control form-control-sm"><?=$remarks;?></textarea>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4"><span>Status:</span></div>
                            <div class="col-sm-8">
                                <select id="status" name="status" class="form-select form-select-sm">
                                    <option value="Draft" <?=($status=='Draft')?'selected':''?>>Draft</option>
                                    <option value="Posted" <?=($status=='Posted')?'selected':''?>>Posted</option>
                                    <option value="Cancelled" <?=($status=='Cancelled')?'selected':''?>>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4"><span>Approved By:</span></div>
                            <div class="col-sm-8">
                                <input type="text" id="approved_by" name="approved_by" value="<?=$approved_by;?>" class="form-control form-control-sm"/>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="col-sm-12">
                    <div class="row mb-2">
                        <div class="table-responsive pe-2 ps-0">
                            <div class="col-md-12 mb-2">
                                <table id="journal_line_items" class="table-sm table-striped journaldata-list">
                                    <thead>
                                        <th class="text-center">
                                            <a class="text-info px-2 fs-7 bg-hover-primary nav-icon-hover position-relative z-index-5" 
                                            id="btn_journal_item_add" 
                                            href="javascript:__mysys_journal_ent.my_add_journal_line();">
                                                <i class="ti ti-new-section"></i>
                                            </a>
                                        </th>
                                        <th class="text-center align-middle">Account Code</th>
                                        <th class="text-center align-middle">Account Name</th>
                                        <th class="text-center align-middle">Debit</th>
                                        <th class="text-center align-middle">Credit</th>
                                        <th class="text-center align-middle">Description</th>
                                        <th class="text-center align-middle">Cost Center</th>
                                    </thead>
                                    <tbody>
                                        <tr style="display:none;">
                                            <td class="text-center align-middle">
                                                <div class="d-inline-flex gap-1 justify-content-center">
                                                    <a class="text-danger fs-5 bg-hover-danger nav-icon-hover" href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                    <a class="text-success fs-5 bg-hover-primary nav-icon-hover" href="javascript:void(0)" title="Add rows above" onclick="__mysys_journal_ent.my_add_journal_line_above(this);">
                                                        <i class="ti ti-plus"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <input type="text" name="account_code" id="account_code" class="account_code form-control form-control-sm text-center" />
                                            </td>
                                            <td class="text-center align-middle">
                                                <input type="text" name="account_name" id="account_name" class="account_name form-control form-control-sm" />
                                            </td>
                                            <td class="text-center align-middle">
                                                <input type="number" name="debit_amount" id="debit_amount" step="0.01" class="debit_amount form-control form-control-sm text-end debit_amount" />
                                            </td>
                                            <td class="text-center align-middle">
                                                <input type="number" name="credit_amount" id="credit_amount" step="0.01" class="credit_amount form-control form-control-sm text-end credit_amount" />
                                            </td>
                                            <td class="text-center align-middle">
                                                <textarea name="description" id="description" rows="1" class="description form-control form-control-sm"></textarea>
                                            </td>
                                            <td class="text-center align-middle">
                                                <input type="text" name="cost_center" id="cost_center" class="cost_center form-control form-control-sm text-center" />
                                            </td>
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
                                            <td class="text-center align-middle">
                                                <div class="d-inline-flex gap-1 justify-content-center">
                                                    <a class="text-danger fs-5 bg-hover-danger nav-icon-hover" href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                    <a class="text-success fs-5 bg-hover-primary nav-icon-hover" href="javascript:void(0)" title="Add rows above" onclick="__mysys_journal_ent.my_add_journal_line_above(this);">
                                                        <i class="ti ti-plus"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <input type="text" name="account_code" id="account_code" class="account_code form-control form-control-sm text-center" value="<?=$data['account_code'];?>" />
                                            </td>
                                            <td class="text-center align-middle">
                                                <input type="text" name="account_name" id="account_name" class="account_name form-control form-control-sm" value="<?=$data['account_name'];?>" />
                                            </td>
                                            <td class="text-center align-middle">
                                                <input type="number" name="debit_amount" id="debit_amount" step="0.01" class="debit_amount form-control form-control-sm text-end debit_amount" value="<?=$data['debit_amount'];?>" />
                                            </td>
                                            <td class="text-center align-middle">
                                                <input type="number" name="credit_amount" id="credit_amount" step="0.01" class="credit_amount form-control form-control-sm text-end credit_amount" value="<?=$data['credit_amount'];?>" />
                                            </td>
                                            <td class="text-center align-middle">
                                                <textarea name="description" rows="1" id="description" class="description form-control form-control-sm"><?=$data['description'];?></textarea>
                                            </td>
                                            <td class="text-center align-middle">
                                                <input type="text" name="cost_center" id="cost_center" class="cost_center form-control form-control-sm text-center" value="<?=$data['cost_center'];?>" />
                                            </td>
                                        </tr>
                                        <?php endforeach; endif;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mb-2">  
                    <div class="col-sm-12 text-end">
                        <button type="submit" class="btn bg-<?= empty($journal_id) ? 'success' : 'info' ?>-subtle text-<?= empty($journal_id) ? 'success' : 'info' ?> btn-sm">
                            <i class="ti ti-brand-doctrine mt-1 fs-4 me-1"></i>
                            <?= empty($journal_id) ? 'Save' : 'Update' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header p-1">
            <div class="row">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 fw-semibold d-flex align-items-center">
                        <i class="ti ti-list fs-5 me-1"></i>
                        <span class="pt-1">Journal Entries</span>
                    </h6>
                </div>
            </div>
        </div>						
        <div class="card-body p-0 px-4 py-2 my-2">
            <table id="datatablesSimple" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Journal No</th>
                        <th>Posting Date</th>
                        <th>Reference No</th>
                        <th>Journal Type</th>
                        <th>Remarks</th>
                        <th>Status</th>
                        <th>Approved By</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    <?php if(!empty($journaldata)):
                        
                        foreach ($journaldata as $data):
                            $journal_id   = $data['journal_id'];
                            $journal_no   = $data['journal_no'];
                            $posting_date = $data['posting_date'];
                            $reference_no = $data['reference_no'];
                            $journal_type = $data['journal_type'];
                            $remarks      = $data['remarks'];
                            $status       = $data['status'];
                            $approved_by  = $data['approved_by'];
                    ?>
                    <tr>
                        <td class="text-center align-middle">
                            <a class="text-info nav-icon-hover fs-6" href="myjournalentry?meaction=MAIN&journal_id=<?= $journal_id ?>" title="Edit Journal">
                                <i class="ti ti-pencil"></i>
                            </a>
                        </td>
                        <td class="text-center"><?=$journal_no;?></td>
                        <td class="text-center"><?=date('Y-m-d', strtotime($posting_date));?></td>
                        <td class="text-center"><?=$reference_no;?></td>
                        <td class="text-center"><?=$journal_type;?></td>
                        <td class="text-center"><?=$remarks;?></td>
                        <td class="text-center">
                            <span class="status-pill 
                                <?=($status=='Posted')?'status-active':(($status=='Draft')?'status-warning':'status-danger');?> 
                            ">
                                <?=$status;?>
                            </span>
                        </td>
                        <td class="text-center"><?=$approved_by;?></td>
                    </tr>
                    <?php endforeach; endif;?>
                </tbody>
            </table>
        </div>
    </div>
    
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/accounting/myjournalentry.js?v=1');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>
<script>
    __mysys_journal_ent.__journalentry_saving();
    $(document).ready(function () {
        $('#datatablesSimple').DataTable({
            pageLength: 5,
            lengthChange: false,
            language: {
            search: "Search:"
            }
        });
    });

</script>
<?php
    echo view('templates/myfooter.php');
?>


