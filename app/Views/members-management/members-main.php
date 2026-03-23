<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$member_id = $this->request->getPostGet('member_id');

$member_no = "";
$first_name = "";
$last_name = "";
$middle_name = "";
$address = "";
$contact_number = "";
$email = "";
$username = "";
$password = "";

if(!empty($member_id) || !is_null($member_id)) { 

    $query = $this->db->query("
    SELECT
        `member_id`,
        `member_no`,
        `first_name`,
        `last_name`,
        `middle_name`,
        `address`,
        `contact_number`,
        `email`,
        `username`,
        `password`,
        `created_by`,
        `created_at`
    FROM
        `tbl_members`
    WHERE
        `member_id` = '$member_id'"
    );

    $data = $query->getRowArray();
    $member_no = $data['member_no'];
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $middle_name = $data['middle_name'];
    $address = $data['address'];
    $contact_number = $data['contact_number'];
    $email = $data['email'];
    $username = $data['username'];
    $password = $data['password'];

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
    <div class="row me-mymembers-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    <div class="row mb-2 mt-0">
        <h4 class="fw-semibold mb-8">List of Members</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Members Management</li>
            <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">List of Members</span></li>
            </ol>
        </nav>
    </div>
    <div class="card">
        <div class="card-header  p-1">
            <div class="row">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 fw-semibold d-flex align-items-center">
                        <i class="ti ti-pencil fs-5 me-1"></i>
                        <span class="pt-1">Add new member</span>
                    </h6>
                </div>
            </div>
		</div>						
        <div class="card-body p-0 px-4 py-2 my-2">
            <form action="<?=site_url();?>mymembers?meaction=MEMBERS-SAVE" method="post" class="mymembers-validation">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row mb-2 mt-2">
                            <div class="col-sm-4">
                                <span>Member No:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="member_no" name="member_no" value="<?=$member_no;?>" class="form-control form-control-sm"/>
                            </div>
                        </div>
                        <div class="row mb-2 mt-2">
                            <div class="col-sm-4">
                                <span>First Name:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="hidden" class="form-control form-control-sm" id="member_id" name="member_id" value="<?=$member_id;?>"/>
                                <input type="text" id="first_name" name="first_name" value="<?=$first_name;?>" class="form-control form-control-sm"/>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span>Last Name:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="last_name" name="last_name" value="<?=$last_name;?>" class="form-control form-control-sm"/>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span>Middle Name:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="middle_name" name="middle_name" value="<?=$middle_name;?>" class="form-control form-control-sm"/>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span>Contact No.:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="contact_number" name="contact_number" value="<?=$contact_number;?>" class="form-control form-control-sm"/>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span>Email:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="email" name="email" value="<?=$email;?>" class="form-control form-control-sm"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 my-2">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span>Address:</span>
                            </div>
                            <div class="col-sm-8">
                            <textarea name="address" id="address" placeholder="" rows="3" class="form-control form-control-sm"><?=$address;?></textarea>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span>Username:</span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="username" name="username" value="<?=$username;?>" class="form-control form-control-sm"/>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <span>Password:</span>
                            </div>
                            <div class="col-sm-8">
                                <div class="input-group input-group-sm">
                                <input type="password" id="password" name="password" value="<?=$password;?>" class="form-control"/>
                                <button class="btn btn-light" type="button" id="togglePassword">
                                    <i class="ti ti-eye" id="toggleIcon"></i>
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">  
                    <div class="col-sm-12 text-end">
                        <button type="submit" class="btn bg-<?= empty($member_id) ? 'success' : 'info' ?>-subtle text-<?= empty($member_id) ? 'success' : 'info' ?> btn-sm"><i class="ti ti-brand-doctrine mt-1 fs-4 me-1"></i>
                            <?= empty($member_id) ? 'Save' : 'Update' ?>
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
                        <span class="pt-1">List</span>
                    </h6>
                </div>
            </div>
		</div>						
        <div class="card-body p-0 px-4 py-2 my-2">
            <table id="datatablesSimple" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Members Number</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Contact number</th>
                        <th>Email</th>
                        <th>Loan Count</th>
                        <th>Loan Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    <?php if(!empty($membersdata)):
                        
                        foreach ($membersdata as $data):
                            $member_id = $data['member_id'];
                            $member_no = $data['member_no'];
                            $first_name = $data['first_name'];
                            $last_name = $data['last_name'];
                            $middle_name = $data['middle_name'];
                            $address = $data['address'];
                            $contact_number = $data['contact_number'];
                            $email = $data['email'];
                    ?>
                    <tr>
                        <td class="text-center align-middle">
                            <a class="text-info nav-icon-hover fs-6" href="mymembers?meaction=MAIN&member_id=<?= $member_id ?>" title="Edit Member">
                                <i class="ti ti-pencil" aria-hidden="true"></i>
                            </a>
                            <a class="text-warning nav-icon-hover fs-6" href="mymembers?meaction=MAIN&member_id=<?= $member_id ?>" title="View loan">
                                <i class="ti ti-file-dollar" aria-hidden="true"></i>
                            </a>
                        </td>
                        <td class="text-center"><?=$member_no;?></td>
                        <td class="text-center"><?=$last_name;?></td>
                        <td class="text-center"><?=$first_name;?></td>
                        <td class="text-center"><?=$contact_number;?></td>
                        <td class="text-center"><?=$email;?></td>
                        <td class="text-center">1</td>
                        <td class="text-center">P1,000,000.00</td>
                        <td class="text-center"><span class="status-pill status-active">Active</span></td>
                    </tr>
                    <?php endforeach; endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/members-management/mymembers.js?v=2');?>"></script>
<script src="<?=base_url('assets/js/mysysapps.js');?>"></script>
<script>
    __mysys_members_ent.__members_saving();
    $(document).ready(function () {
        $('#datatablesSimple').DataTable({
            pageLength: 5,
            lengthChange: false,
            language: {
            search: "Search:"
            }
        });
    });

    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');

        if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('ti-eye');
        icon.classList.add('ti-eye-off');
        } else {
        input.type = 'password';
        icon.classList.remove('ti-eye-off');
        icon.classList.add('ti-eye');
        }
    });
</script>
<?php
    echo view('templates/myfooter.php');
?>


