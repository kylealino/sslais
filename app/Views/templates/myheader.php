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
      `username` = '{$this->cuser}'"
  );

  $data = $query->getRowArray();
  $full_name = $data['full_name'];
  $position = $data['position'];
  $section = $data['section'];
  $division = $data['division'];

?>
<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Navy_Yellow_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/images/logos/sslai.png')?>" />

  <!-- Core Css -->
  <link rel="stylesheet" href="<?=base_url('assets/css/styles.css')?>" />
  
  <!-- Navy Blue & Yellow Theme Override -->
  <style>
    :root {
      --navy-dark: #0a1a3a;
      --navy-medium: #1a2e5a;
      --navy-light: #2a3e6a;
      --gold-primary: #f5b342;
      --gold-light: #f5c542;
      --gold-soft: #ffd966;
    }
    
    /* Override Bootstrap Primary Colors */
    [data-color-theme="Navy_Yellow_Theme"] {
      --bs-primary: var(--navy-dark) !important;
      --bs-primary-rgb: 10, 26, 58 !important;
      --bs-primary-bg-subtle: rgba(10, 26, 58, 0.1) !important;
      --bs-primary-text-emphasis: var(--navy-dark) !important;
      --bs-primary-border-subtle: rgba(10, 26, 58, 0.2) !important;
      
      /* Yellow/Gold as Success */
      --bs-success: var(--gold-primary) !important;
      --bs-success-rgb: 245, 179, 66 !important;
      --bs-success-bg-subtle: rgba(245, 179, 66, 0.1) !important;
      
      /* Link Colors */
      --bs-link-color: var(--navy-dark) !important;
      --bs-link-hover-color: var(--gold-primary) !important;
    }
    
    /* Sidebar Styling */
    .left-sidebar {
      background: linear-gradient(180deg, var(--navy-dark) 0%, var(--navy-medium) 100%) !important;
      border-right: 1px solid rgba(245, 180, 66, 0.2) !important;
    }
    
    .brand-logo {
      background: rgba(255, 255, 255, 0.05) !important;
      border-bottom: 1px solid rgba(245, 180, 66, 0.2) !important;
    }
    
    .brand-logo a {
      color: white !important;
      font-weight: 600;
    }
    
    .brand-logo img {
      border: 2px solid var(--gold-primary);
      padding: 2px;
      background: white;
    }
    
    .nav-small-cap {
      color: var(--gold-primary) !important;
      font-weight: 700 !important;
      letter-spacing: 0.5px;
    }
    
    .nav-small-cap i {
      color: var(--gold-primary) !important;
    }
    
    .sidebar-nav ul .sidebar-item .sidebar-link {
      color: rgba(255, 255, 255, 0.8) !important;
      transition: all 0.3s ease;
    }
    
    .sidebar-nav ul .sidebar-item .sidebar-link:hover {
      background: rgba(245, 180, 66, 0.15) !important;
      color: var(--gold-primary) !important;
    }
    
    .sidebar-nav ul .sidebar-item .sidebar-link:hover i {
      color: var(--gold-primary) !important;
    }
    
    .sidebar-nav ul .sidebar-item .sidebar-link.active {
      background: var(--gold-primary) !important;
      color: var(--navy-dark) !important;
      font-weight: 600;
    }
    
    .sidebar-nav ul .sidebar-item .sidebar-link.active i {
      color: var(--navy-dark) !important;
    }
    
    .sidebar-nav ul .sidebar-item .sidebar-link i {
      color: rgba(255, 255, 255, 0.6) !important;
      font-size: 1.2rem;
    }
    
    /* Scrollbar Styling */
    .left-sidebar.with-vertical::-webkit-scrollbar {
      width: 4px;
    }
    
    .left-sidebar.with-vertical::-webkit-scrollbar-track {
      background: var(--navy-light);
    }
    
    .left-sidebar.with-vertical::-webkit-scrollbar-thumb {
      background: var(--gold-primary);
      border-radius: 4px;
    }
    
    .left-sidebar.with-vertical::-webkit-scrollbar-thumb:hover {
      background: var(--gold-light);
    }
    
    /* Header/Topbar Styling */
    .topbar {
      background: white !important;
      border-bottom: 2px solid var(--gold-primary) !important;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .nav-icon-hover-bg:hover {
      background-color: rgba(245, 180, 66, 0.1) !important;
    }
    
    .nav-icon-hover-bg:hover i {
      color: var(--gold-primary) !important;
    }
    
    /* User Profile Dropdown */
    .dropdown-menu {
      border: 1px solid rgba(245, 180, 66, 0.2) !important;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
    }
    
    .dropdown-item:hover {
      background-color: rgba(245, 180, 66, 0.1) !important;
      color: var(--navy-dark) !important;
    }
    
    .border-bottom {
      border-bottom: 1px solid rgba(245, 180, 66, 0.2) !important;
    }
    
    /* Buttons */
    .btn-outline-primary {
      border-color: var(--navy-dark) !important;
      color: var(--navy-dark) !important;
    }
    
    .btn-outline-primary:hover {
      background-color: var(--navy-dark) !important;
      color: white !important;
      border-color: var(--navy-dark) !important;
    }
    
    .btn-primary {
      background-color: var(--navy-dark) !important;
      border-color: var(--navy-dark) !important;
      color: white !important;
    }
    
    .btn-primary:hover {
      background-color: var(--navy-medium) !important;
      border-color: var(--navy-medium) !important;
    }
    
    .btn-success {
      background-color: var(--gold-primary) !important;
      border-color: var(--gold-primary) !important;
      color: var(--navy-dark) !important;
    }
    
    .btn-success:hover {
      background-color: var(--gold-light) !important;
      border-color: var(--gold-light) !important;
    }
    
    /* Text Colors */
    .text-primary {
      color: var(--navy-dark) !important;
    }
    
    .text-success {
      color: var(--gold-primary) !important;
    }
    
    a.text-primary:hover {
      color: var(--gold-primary) !important;
    }
    
    /* Form Controls */
    .form-control:focus {
      border-color: var(--gold-primary) !important;
      box-shadow: 0 0 0 0.2rem rgba(245, 180, 66, 0.25) !important;
    }
    
    .form-check-input:checked {
      background-color: var(--navy-dark) !important;
      border-color: var(--navy-dark) !important;
    }
    
    /* Cards */
    .card {
      border: 1px solid rgba(245, 180, 66, 0.1) !important;
    }
    
    .card-header {
      background-color: rgba(245, 180, 66, 0.05) !important;
      border-bottom: 1px solid rgba(245, 180, 66, 0.2) !important;
    }
    
    /* Tables */
    .table thead th {
      color: var(--navy-dark) !important;
      border-bottom: 2px solid var(--gold-primary) !important;
    }
    
    .table tbody tr:hover {
      background-color: rgba(245, 180, 66, 0.05) !important;
    }
    
    /* Badges */
    .badge.bg-primary {
      background-color: var(--navy-dark) !important;
    }
    
    .badge.bg-success {
      background-color: var(--gold-primary) !important;
      color: var(--navy-dark) !important;
    }
    
    /* Custom Scrollbar */
    .left-sidebar.with-vertical {
      height: 100vh;
      overflow-y: auto;
    }

    .left-sidebar.with-vertical::-webkit-scrollbar {
      width: 4px;
    }

    .left-sidebar.with-vertical::-webkit-scrollbar-track {
      background: var(--navy-light);
    }

    .left-sidebar.with-vertical::-webkit-scrollbar-thumb {
      background: var(--gold-primary);
      border-radius: 4px;
    }
    
    .left-sidebar.with-vertical::-webkit-scrollbar-thumb:hover {
      background: var(--gold-light);
    }
    
    /* Active Menu Item */
    .sidebar-item.active > .sidebar-link {
      background: var(--gold-primary) !important;
      color: var(--navy-dark) !important;
    }
    
    .sidebar-item.active > .sidebar-link i {
      color: var(--navy-dark) !important;
    }
    
    /* Logout Button */
    .btn-outline-primary {
      border: 2px solid var(--navy-dark) !important;
      color: var(--navy-dark) !important;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
      background-color: var(--navy-dark) !important;
      color: white !important;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(10, 26, 58, 0.3);
    }
    
    /* User Profile Section */
    .user-profile-img {
      border: 2px solid var(--gold-primary);
      border-radius: 50%;
      padding: 2px;
    }
    
    .user-profile-img img {
      border: 2px solid white;
    }
    
    /* Dropdown Menu Items */
    .dropdown-item {
      transition: all 0.2s ease;
    }
    
    .dropdown-item:hover {
      background-color: rgba(245, 180, 66, 0.1) !important;
      padding-left: 2rem !important;
    }
    
    /* Simplebar Scrollbar */
    .simplebar-scrollbar:before {
      background: var(--gold-primary) !important;
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
      <div>
        <!-- Start Vertical Layout Sidebar -->
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="<?=site_url();?>mydashboard" class="text-nowrap logo-img d-flex align-items-center gap-2">
            <img src="<?=base_url('assets/images/logos/sslai.png')?>" style="width: 35px; height: auto;"/>
            <span class="fw-bold">SSLAIS</span>
          </a> 
          <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
            <i class="ti ti-x" style="color: var(--gold-primary);"></i>
          </a>
        </div>

        <nav class="sidebar-nav scroll-sidebar" data-simplebar style="height: 100vh !important;">
          <ul id="sidebarnav">
            <!-- Home Section -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu fs-2">HOME</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>mydashboard" aria-expanded="false">
                <span>
                  <i class="ti ti-aperture"></i>
                </span>
                <span class="hide-menu fs-2">Dashboard</span>
              </a>
            </li>
            
            <!-- Members Management -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu fs-2">MEMBERS MANAGEMENT</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myaccount?meaction=MAIN" aria-expanded="false">
                <span>
                  <i class="ti ti-settings"></i>
                </span>
                <span class="hide-menu fs-2">Account Settings</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>mymembers?meaction=MAIN" aria-expanded="false">
                <span>
                  <i class="ti ti-user-check"></i>
                </span>
                <span class="hide-menu fs-2">List of Members</span>
              </a>
            </li>

            <!-- Loan Management -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu fs-2">LOAN MANAGEMENT</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myloanavailment?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-cash"></i>
                </span>
                <span class="hide-menu fs-2">Loan Availment</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myloanprofile?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-cash"></i>
                </span>
                <span class="hide-menu fs-2">Loan Profile</span>
              </a>
            </li>

            <!-- Accounting -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu fs-2">ACCOUNTING</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myors?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-clipboard-text"></i>
                </span>
                <span class="hide-menu fs-2">Journal Entry</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>mysubsidiary?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-clipboard-check"></i>
                </span>
                <span class="hide-menu fs-2">Subsidiary Ledger</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myorsapproval?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-clipboard-check"></i>
                </span>
                <span class="hide-menu fs-2">Chart of Accounts</span>
              </a>
            </li>

            <!-- Reports -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu fs-2">REPORTS</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>mysaobrpt?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-file"></i>
                </span>
                <span class="hide-menu fs-2">Cash Receipts Journal</span>
              </a>
            </li> 
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myppmp?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-list-numbers"></i>
                </span>
                <span class="hide-menu fs-2">Cash Disbursement Journal</span>
              </a>
            </li> 
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myprocurement?meaction=PR-MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-playlist-add"></i>
                </span>
                <span class="hide-menu fs-2">Balance Sheet</span>
              </a>
            </li> 
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myabstract?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-mist"></i>
                </span>
                <span class="hide-menu fs-2">Income Statement</span>
              </a>
            </li> 
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>myabstract?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-mist"></i>
                </span>
                <span class="hide-menu fs-2">Cash Flow Statement</span>
              </a>
            </li> 
          </ul>
        </nav>
      </div>
    </aside>
    <!--  Sidebar End -->
    
    <div class="page-wrapper">
      <!--  Header Start -->
      <header class="topbar">
        <div class="with-vertical">
          <!-- Start Vertical Layout Header -->
          <nav class="navbar navbar-expand-lg p-0">
            <ul class="navbar-nav">
              <li class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
                <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                  <i class="ti ti-menu-2"></i>
                </a>
              </li>
            </ul>

            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
              <!-- Profile Dropdown -->
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
                      <h5 class="mb-0 fs-5 fw-semibold text-primary">User Profile</h5>
                    </div>
                    <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                      <img src="<?=base_url('assets/images/profile/user-1.jpg')?>" class="rounded-circle" width="80" height="80" alt="flexy-img" />
                      <div class="ms-3">
                        <h5 class="mb-1 fs-4 text-primary"><?=$this->cuser;?></h5>
                        <span class="mb-1 d-block"><?=$full_name;?></span>
                        <span class="mb-1 d-block fs-2 text-muted"><?=$position;?></span>
                        <span class="mb-1 d-block fs-2 text-muted"><?=$division . ' - ' . $section;?></span>
                      </div>
                    </div>
                    <div class="message-body">
                      <a href="" class="py-8 px-7 mt-8 d-flex align-items-center dropdown-item">
                        <span class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                          <img src="<?=base_url('assets/images/svgs/icon-account.svg')?>" alt="flexy-img" width="24" height="24" />
                        </span>
                        <div class="w-100 ps-3">
                          <h6 class="mb-0 fs-4 lh-base">My Profile</h6>
                          <span class="fs-3 d-block text-body-secondary">Account Settings</span>
                        </div>
                      </a>
                      <a href="" class="py-8 px-7 d-flex align-items-center dropdown-item">
                        <span class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                          <img src="<?=base_url('assets/images/svgs/icon-inbox.svg')?>" alt="flexy-img" width="24" height="24" />
                        </span>
                        <div class="w-100 ps-3">
                          <h6 class="mb-0 fs-4 lh-base">My Inbox</h6>
                          <span class="fs-3 d-block text-body-secondary">Messages & Emails</span>
                        </div>
                      </a>
                      <a href="" class="py-8 px-7 d-flex align-items-center dropdown-item">
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
                          <?= csrf_field(); ?>
                          <button type="submit" class="btn btn-outline-primary w-100">Log Out</button>
                      </form>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </nav>
        </div>
      </header>
      <!--  Header End -->

      <div class="body-wrapper">