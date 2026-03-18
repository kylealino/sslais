
<?php 
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');

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
      `username` = '$this->cuser'"
  );

  $data = $query->getRowArray();
  $full_name = $data['full_name'];
  $position = $data['position'];
  $section = $data['section'];
  $division = $data['division'];

?>
<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/images/logos/sslai.png')?>" />

  <!-- Core Css -->
  <link rel="stylesheet" href="<?=base_url('assets/css/styles.css')?>" />

  <title>S S L A I S</title>
  <style>
    .left-sidebar.with-vertical {
      height: 100vh;
      overflow-y: auto;
    }

    .left-sidebar.with-vertical::-webkit-scrollbar {
      width: 1px;  /* hides scrollbar */
      background: lightblue;
    }


  </style>
</head>
<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="<?=base_url('assets/images/logos/preloader.svg')?>" alt="loader" class="lds-ripple img-fluid" />
  </div>
  <div id="main-wrapper">
    <!-- Sidebar Start -->
    <aside class="left-sidebar with-vertical">
      <div><!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="<?=site_url();?>mydashboard" class="text-nowrap logo-img">
            <img src="<?=base_url('assets/images/logos/sslai.png')?>" style="width: 35px; height: auto;"/>
            SSLAIS
          </a> 
          <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
            <i class="ti ti-x"></i>
          </a>
        </div>


        <nav class="sidebar-nav scroll-sidebar" data-simplebar style="height: 100vh !important;">
          <ul id="sidebarnav">
            <!-- ---------------------------------- -->
            <!-- Home -->
            <!-- ---------------------------------- -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu fs-2">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>mydashboard" aria-expanded="false">
                <span>
                  <i class="ti ti-aperture"></i>
                </span>
                <span class="hide-menu fs-2">Dashboard</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu fs-2">Members Management</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>mypayee?meaction=MAIN" aria-expanded="false">
                <span>
                  <i class="ti ti-user-check"></i>
                </span>
                <span class="hide-menu fs-2">List of Members</span>
              </a>
            </li>

            <!-- <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myaccount?meaction=MAIN" aria-expanded="false">
                <span>
                  <i class="ti ti-user"></i>
                </span>
                <span class="hide-menu fs-2">Account Settings</span>
              </a>
            </li> -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu fs-2">Loan Management</span>
            </li>

            </li>      
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>mybudgetallotment?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-cash"></i>
                </span>
                <span class="hide-menu fs-2">Loan Application</span>
              </a>
            </li>
              <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>mybudgetallotment?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-cash"></i>
                </span>
                <span class="hide-menu fs-2">Loan Profile</span>
              </a>
            </li>

            <!-- ---------------------------------- -->
            <!-- OTHER -->
            <!-- ---------------------------------- -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu fs-2">Accounting</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>myors?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-clipboard-text"></i>
                </span>
                <span class="hide-menu fs-2">Journal Entry</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>mysubsidiary?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-clipboard-check"></i> </span>
                <span class="hide-menu fs-2">Subsidiary Ledger</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>myorsapproval?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-clipboard-check"></i>
                </span>
                <span class="hide-menu fs-2">Chart of Accounts</span>
              </a>
            </li>

            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu fs-2">Reports</span>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>mysaobrpt?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-file"></i>
                </span>
                <span class="hide-menu fs-2">Cash Receipts Journal</span>
              </a>
            </li> 

            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>myppmp?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-list-numbers"></i>
                </span>
                <span class="hide-menu fs-2">Cash Disbursement Journal</span>
              </a>
            </li> 
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>myprocurement?meaction=PR-MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-playlist-add"></i>
                </span>
                <span class="hide-menu fs-2">Balance Sheet</span>
              </a>
            </li> 
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>myabstract?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-mist"></i>
                </span>
                <span class="hide-menu fs-2">Income Statement</span>
              </a>
            </li> 
            <li class="sidebar-item">
              <a class="sidebar-link sidebar-link" href="<?=site_url();?>myabstract?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-mist"></i>
                </span>
                <span class="hide-menu fs-2">Cash Flow Statement</span>
              </a>
            </li> 
          </ul>
        </nav>

        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
      </div>
    </aside>
    <!--  Sidebar End -->
    <div class="page-wrapper">
      <!--  Header Start -->
      <header class="topbar">
        <div class="with-vertical"><!-- ---------------------------------- -->
          <!-- Start Vertical Layout Header -->
          <!-- ---------------------------------- -->
          <nav class="navbar navbar-expand-lg p-0">
            <ul class="navbar-nav">
              <li class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
                <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                  <i class="ti ti-menu-2"></i>
                </a>
              </li>
            </ul>

            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">

              <!-- ------------------------------- -->
              <!-- start profile Dropdown -->
              <!-- ------------------------------- -->
              <li class="nav-item dropdown">
                <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" aria-expanded="false">
                  <div class="d-flex align-items-center">
                    <div class="user-profile-img">
                      <img src="<?=base_url('assets/images/profile/user-1.jpg')?>" class="rounded-circle" width="35" height="35" alt="flexy-img" />
                    </div>
                  </div>
                </a>
                <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                  <div class="profile-dropdown position-relative" data-simplebar>
                    <div class="py-3 px-7 pb-0">
                      <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                    </div>
                    <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                      <img src="<?=base_url('assets/images/profile/user-1.jpg')?>" class="rounded-circle" width="80" height="80" alt="flexy-img" />
                      <div class="ms-3">
                        <h5 class="mb-1 fs-4"><?=$this->cuser;?></h5>
                        <span class="mb-1 d-block"><?=$full_name;?></span>
                        <span class="mb-1 d-block fs-2"><?=$position;?></span>
                        <span class="mb-1 d-block fs-2"><?=$division . ' - ' . $section;?></span>
                      </div>
                    </div>
                    <div class="message-body">
                      <a href="" class="py-8 px-7 mt-8 d-flex align-items-center">
                        <span class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                          <img src="<?=base_url('assets/images/svgs/icon-account.svg')?>" alt="flexy-img" width="24" height="24" />
                        </span>
                        <div class="w-100 ps-3">
                          <h6 class="mb-0 fs-4 lh-base">My Profile</h6>
                          <span class="fs-3 d-block text-body-secondary">Account Settings</span>
                        </div>
                      </a>
                      <a href="" class="py-8 px-7 d-flex align-items-center">
                        <span class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                          <img src="<?=base_url('assets/images/svgs/icon-inbox.svg')?>" alt="flexy-img" width="24" height="24" />
                        </span>
                        <div class="w-100 ps-3">
                          <h6 class="mb-0 fs-4 lh-base">My Inbox</h6>
                          <span class="fs-3 d-block text-body-secondary">Messages & Emails</span>
                        </div>
                      </a>
                      <a href="" class="py-8 px-7 d-flex align-items-center">
                        <span class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                          <img src="<?=base_url('assets/images/svgs/icon-tasks.svg')?>" alt="flexy-img" width="24" height="24" />
                        </span>
                        <div class="w-100 ps-3">
                          <h6 class="mb-0 fs-4 lh-base">My Task</h6>
                          <span class="fs-3 d-block text-body-secondary">To-do and Daily Tasks</span>
                        </div>
                      </a>
                    </div>
                    <div class="d-grid py-4 px-7 pt-8">
                      <form action="<?= site_url('mylogout'); ?>" method="post" novalidate>
                          <!-- Add a CSRF token for security -->
                          <?= csrf_field(); ?>
                          
                          <button type="submit" class="btn btn-outline-primary">Log Out</button>
                      </form>
                    </div>
                  </div>
                </div>
              </li>
              <!-- ------------------------------- -->
              <!-- end profile Dropdown -->
              <!-- ------------------------------- -->
            </ul>
          </nav>

        </div>
        <div class="app-header with-horizontal">
          <nav class="navbar navbar-expand-xl container-fluid p-0">
            <ul class="navbar-nav align-items-center">
              <li class="nav-item nav-icon-hover-bg rounded-circle d-flex d-xl-none ms-n2">
                <a class="nav-link sidebartoggler" id="sidebarCollapse" href="javascript:void(0)">
                  <i class="ti ti-menu-2"></i>
                </a>
              </li>
              <li class="nav-item d-none d-xl-block">
                <a href="../main/index.html" class="text-nowrap nav-link">
                  <img src="../assets/images/logos/dark-logo.svg" class="dark-logo" alt="flexy-img" />
                  <img src="../assets/images/logos/light-logo.svg" class="light-logo" alt="flexy-img" />
                </a>
              </li>
              <li class="nav-item nav-icon-hover-bg rounded-circle d-none d-xl-flex">
                <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  <i class="ti ti-search"></i>
                </a>
              </li>
            </ul>


          </nav>
        </div>
      </header>
      <!--  Header End -->

      <div class="body-wrapper">