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
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Navy_Gold_White_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/images/logos/sslai.png')?>" />

  <!-- Core Css -->
  <link rel="stylesheet" href="<?=base_url('assets/css/styles.css')?>" />
  
  <!-- Navy Blue, Gold & White Theme - Clean, No Table Borders -->
  <style>
    :root {
      --navy-dark: #0a1a3a;
      --navy-medium: #1a2e5a;
      --navy-light: #2a3e6a;
      --gold-primary: #d4af37;
      --gold-dark: #b8960c;
      --gold-light: #f5e6a3;
      --gold-soft: #fef7e0;
      --white-bg: #ffffff;
      --white-off: #f8f9fa;
      --gray-light: #e9ecef;
      --gray-medium: #6c757d;
      --gray-dark: #495057;
      --text-dark: #1e2a3a;
    }
    
    /* Sidebar Styling - NAVY BLUE */
    .left-sidebar {
      background: linear-gradient(180deg, var(--navy-dark) 0%, var(--navy-medium) 100%) !important;
      border-right: 1px solid rgba(212, 175, 55, 0.15) !important;
      box-shadow: 2px 0 12px rgba(0, 0, 0, 0.08);
    }
    
    .brand-logo {
      background: rgba(0, 0, 0, 0.15) !important;
      border-bottom: 1px solid rgba(212, 175, 55, 0.2) !important;
    }
    
    .brand-logo a {
      color: white !important;
      font-weight: 700;
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
      color: rgba(255, 255, 255, 0.85) !important;
      transition: all 0.3s ease;
    }
    
    .sidebar-nav ul .sidebar-item .sidebar-link:hover {
      background: rgba(212, 175, 55, 0.15) !important;
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
      color: rgba(255, 255, 255, 0.7) !important;
      font-size: 1.2rem;
    }
    
    /* Scrollbar Styling - Navy Sidebar */
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
      background: var(--gold-dark);
    }
    
    /* Header/Topbar Styling - Clean */
    .topbar {
      background: var(--white-bg) !important;
      border-bottom: 1px solid var(--gray-light) !important;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }
    
    /* Remove gold border from nav icons */
    .nav-icon-hover-bg {
      background: transparent !important;
      border: none !important;
    }
    
    .nav-icon-hover-bg:hover {
      background-color: var(--gold-soft) !important;
    }
    
    .nav-icon-hover-bg:hover i {
      color: var(--gold-primary) !important;
    }
    
    .nav-icon-hover-bg i {
      color: var(--navy-dark) !important;
      font-size: 1.2rem;
    }
    
    /* User Profile - No Gold Border */
    .user-profile-img {
      border: none !important;
    }
    
    .user-profile-img img {
      border: 2px solid var(--white-bg);
    }
    
    /* Dropdown Menu */
    .dropdown-menu {
      border: 1px solid var(--gray-light) !important;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
    }
    
    .dropdown-item {
      transition: all 0.2s ease;
    }
    
    .dropdown-item:hover {
      background-color: var(--gold-soft) !important;
      color: var(--navy-dark) !important;
      padding-left: 2rem !important;
    }
    
    .border-bottom {
      border-bottom: 1px solid var(--gray-light) !important;
    }
    
    /* Buttons */
    .btn-outline-primary {
      border: 2px solid var(--gold-primary) !important;
      color: var(--gold-dark) !important;
      font-weight: 600;
      transition: all 0.3s ease;
      background: transparent;
    }
    
    .btn-outline-primary:hover {
      background-color: var(--gold-primary) !important;
      color: var(--navy-dark) !important;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
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
    
    /* Body Background - FLAT WHITE */
    body {
      background: var(--white-bg) !important;
    }
    
    .body-wrapper {
      background: var(--white-bg) !important;
    }
    
    /* Form Controls */
    .form-control:focus {
      border-color: var(--gold-primary) !important;
      box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
    }
    
    .form-check-input:checked {
      background-color: var(--navy-dark) !important;
      border-color: var(--navy-dark) !important;
    }
    
    /* Cards - Clean, No Top Border, Just Shadow */
    .card {
      border: 1px solid var(--gray-light) !important;
      border-radius: 16px !important;
      background: var(--white-bg) !important;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
      background-color: var(--white-off) !important;
      border-bottom: 1px solid var(--gray-light) !important;
    }
    
    /* TABLES - CLEAN, NO BORDERS, JUST HOVER */
    .table {
      width: 100%;
      border-collapse: collapse;
    }
    
    .table thead th {
      color: var(--navy-dark);
      font-weight: 600;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 12px 12px;
      border-bottom: 1px solid var(--gray-light);
      background: transparent;
    }
    
    .table tbody td {
      padding: 12px 12px;
      font-size: 13px;
      color: var(--gray-700);
      border-bottom: 1px solid var(--gray-100);
      vertical-align: middle;
    }
    
    .table tbody tr:hover td {
      background-color: var(--gold-soft);
    }
    
    /* DataTables specific */
    .dataTables_wrapper {
      font-family: 'Inter', sans-serif;
    }
    
    .dataTables_filter {
      float: right;
      margin-bottom: 20px;
    }
    
    .dataTables_filter input {
      border: 1.5px solid var(--gray-light);
      border-radius: 10px;
      padding: 8px 14px;
      font-size: 13px;
    }
    
    .dataTables_filter input:focus {
      border-color: var(--gold-primary);
      outline: none;
    }
    
    .dataTables_paginate {
      float: right;
      margin-top: 20px;
    }
    
    .dataTables_paginate .paginate_button {
      padding: 6px 12px !important;
      margin: 0 3px !important;
      border-radius: 8px !important;
      border: 1px solid var(--gray-light) !important;
      background: var(--white-bg) !important;
      color: var(--gray-medium) !important;
      font-size: 12px !important;
    }
    
    .dataTables_paginate .paginate_button.current {
      background: var(--gold-primary) !important;
      border-color: var(--gold-primary) !important;
      color: var(--navy-dark) !important;
    }
    
    .dataTables_info {
      float: left;
      font-size: 12px;
      color: var(--gray-medium);
      margin-top: 20px;
    }
    
    /* Badges */
    .badge.bg-primary {
      background-color: var(--navy-dark) !important;
      color: white !important;
    }
    
    .badge.bg-success {
      background-color: var(--gold-primary) !important;
      color: var(--navy-dark) !important;
    }
    
    /* Active Menu Item - Navy Sidebar with Gold */
    .sidebar-item.active > .sidebar-link {
      background: var(--gold-primary) !important;
      color: var(--navy-dark) !important;
    }
    
    .sidebar-item.active > .sidebar-link i {
      color: var(--navy-dark) !important;
    }
    
    /* Scrollbar for sidebar */
    .left-sidebar.with-vertical {
      height: 100vh;
      overflow-y: auto;
    }
    
    /* Page Wrapper Background */
    .page-wrapper {
      background: var(--white-bg) !important;
    }
    
    /* Dashboard Cards */
    .stat-card {
      border-top: 3px solid var(--gold-primary) !important;
    }
    
    /* Section Cards */
    .section-card {
      border-top: 2px solid var(--gold-primary) !important;
    }
    
    /* Quick Actions */
    .quick-action:hover {
      background: var(--gold-soft) !important;
      border-color: var(--gold-primary) !important;
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
              <span class="hide-menu fs-2">MEMBERS MANAGEMENTS</span>
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
              <a class="sidebar-link" href="<?=site_url();?>myjournalentry?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-clipboard-text"></i>
                </span>
                <span class="hide-menu fs-2">Journal Entry</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?=site_url();?>mycoa?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-list-check"></i>
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
              <a class="sidebar-link" href="<?=site_url();?>myaccountingreport?meaction=MAIN" aria-expanded="false">
                <span class="rounded-3">
                  <i class="ti ti-file"></i>
                </span>
                <span class="hide-menu fs-2">Financial Reports</span>
              </a>
            </li> 

          </ul>
        </nav>
      </div>
    </aside>
    <!--  Sidebar End -->
    
    <div class="page-wrapper">
      <!--  Header Start - NO YELLOW BORDERS -->
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