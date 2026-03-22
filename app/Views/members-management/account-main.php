<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');
$recid = $this->request->getPostGet('recid');

$full_name = "";
$division = "";
$section = "";
$position = "";
$username = "";
$hash_password = "";
$hash_value = "";
$email = "";
$contact_no = "";


if(!empty($recid) || !is_null($recid)) { 

    $query = $this->db->query("
    SELECT 
        `full_name`, 
        `division`,
        `section`, 
        `position`,
        `username`, 
        `hash_password`,
        `hash_value`
    FROM 
        `myua_user` 
    WHERE 
        `recid` = '$recid'"
    );

    $data = $query->getRowArray();
    $full_name = $data['full_name'];
    $division = $data['division'];
    $section = $data['section'];
    $position = $data['position'];
    $username = $data['username'];
    $hash_password = $data['hash_password'];
    $hash_value = $data['hash_value'];


}
echo view('templates/myheader.php');
?>

<div class="container-fluid">
    <div class="row mb-2 mt-0">
        <h4 class="fw-semibold mb-8">Account Settings</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Members Management</li>
                <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">Account Settings</span></li>
            </ol>
        </nav>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-account" role="tabpanel" aria-labelledby="pills-account-tab" tabindex="0">
                    <div class="row">
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <div class="card w-100 border position-relative overflow-hidden">
                            <div class="card-body p-4">
                                <h4 class="card-title">Change Profile</h4>
                                <p class="card-subtitle mb-4">Change your profile picture from here</p>
                                <div class="text-center">
                                <img src="<?=base_url('assets/images/profile/user-1.jpg')?>" alt="flexy-img" class="img-fluid rounded-circle" width="120" height="120">
                                <div class="d-flex align-items-center justify-content-center my-4 gap-6">
                                    <button class="btn btn-primary">Upload</button>
                                    <button class="btn bg-danger-subtle text-danger">Reset</button>
                                </div>
                                <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <div class="card w-100 border position-relative overflow-hidden">
                            <div class="card-body p-4">
                                <h4 class="card-title">Change Password</h4>
                                <p class="card-subtitle mb-4">To change your password please confirm here</p>
                                <form>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="exampleInputPassword1" value="12345678910">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword2" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="newpassword" value="12345678910">
                                </div>
                                <div>
                                    <label for="exampleInputPassword3" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirmpassword" value="12345678910">
                                </div>
                                </form>
                            </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card w-100 border position-relative overflow-hidden mb-0">
                                <div class="card-body p-4">
                                    <h4 class="card-title">Personal Details</h4>
                                    <p class="card-subtitle mb-4">To change your personal detail , edit and save from here</p>
                                    <form>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="exampleInputtext" class="form-label">Full Name</label>
                                                <input type="hidden" class="form-control" id="recid" name="recid" value="<?=$recid;?>"/>
                                                <input type="text" id="exampleInputtext" name="full_name" value="<?=$full_name;?>" class="form-control"/>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Division</label>
                                                <input type="text" id="division" name="division" value="<?=$division;?>" class="form-control"/>
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleInputtext1" class="form-label">Section</label>
                                                <input type="text" id="section" name="section" value="<?=$section;?>" class="form-control"/>
                                            </div>
                                            </div>
                                            <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="exampleInputtext1" class="form-label">Position</label>
                                                <input type="text" id="position" name="position" value="<?=$position;?>" class="form-control"/>
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleInputtext1" class="form-label">Email</label>
                                                <input type="text" id="email" name="email" value="<?=$email;?>" class="form-control"/>
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleInputtext1" class="form-label">Contact No.</label>
                                                <input type="text" id="contact_no" name="contact_no" value="<?=$contact_no;?>" class="form-control"/>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                        <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
                                            <button class="btn btn-primary">Save</button>
                                            <button class="btn bg-danger-subtle text-danger">Cancel</button>
                                        </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  function handleColorTheme(e) {
    document.documentElement.setAttribute("data-color-theme", e);
  }
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/libs/apexcharts/dist/apexcharts.min.js')?>"></script>
<script src="<?=base_url('assets/js/report/mysaobreport.js?v=3');?>"></script>
<script src="<?=base_url('assets/js/dashboards/dashboard2.js')?>"></script>
<script src="<?=base_url('assets/js/apex-chart/apex.radial.init.js')?>"></script>


<?php
    echo view('templates/myfooter.php');
?>
