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

  // Get profile photo from tbl_members
  $profile_photo_url = base_url('assets/images/profile/user-1.jpg'); // Default image
  
  if(!empty($this->cuser)) {
      $photo_query = $this->db->query("
          SELECT profile_photo_path 
          FROM tbl_members 
          WHERE username = ?", [$this->cuser]
      );
      $photo_data = $photo_query->getRowArray();
      
      if(!empty($photo_data) && !empty($photo_data['profile_photo_path'])) {
          $profile_photo_url = base_url($photo_data['profile_photo_path']);
      }
  }
  
  // Get current URL for active menu highlighting
  $current_url = current_url();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/images/logos/sslai.png')?>" />
  <title>DMIS</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <style>
    /* ===================== */
    /* FLEETSYS - TRUCKING/FLEET INSPIRED COLOR PALETTE */
    /* ===================== */
    :root {
      --fleet-dark: #0b1a2e;
      --fleet-mid: #1a2f44;
      --fleet-soft: #2c4058;
      --fleet-blue: #2a7de1;
      --fleet-blue-light: #4a9af5;
      --fleet-gold: #f5b342;
      --fleet-green: #34c759;
      --fleet-red: #ff6b6b;
      --fleet-white: #ffffff;
      --fleet-gray: #94a3b8;
      --fleet-gray-dark: #64748b;
      --fleet-border: rgba(255, 255, 255, 0.06);
      --fleet-hover: rgba(42, 125, 225, 0.12);
      --fleet-card-bg: rgba(255, 255, 255, 0.04);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: #f0f4f8;
      overflow-x: hidden;
    }

    /* ===================== */
    /* SIDEBAR - FLEET DARK */
    /* ===================== */
    .left-sidebar {
      background: var(--fleet-dark);
      box-shadow: 4px 0 20px rgba(0,0,0,0.4);
      border-right: 1px solid var(--fleet-border);
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 280px;
      z-index: 1000;
      transition: transform 0.3s ease, width 0.3s ease;
      overflow-y: auto;
      overflow-x: hidden;
      display: flex;
      flex-direction: column;
    }

    /* Collapsed Sidebar */
    .left-sidebar.collapsed {
      width: 80px;
    }

    .left-sidebar.collapsed .brand-text,
    .left-sidebar.collapsed .sidebar-link span:not(.ti),
    .left-sidebar.collapsed .nav-small-cap span,
    .left-sidebar.collapsed .logout-link span {
      display: none;
    }

    .left-sidebar.collapsed .sidebar-link {
      justify-content: center;
      padding: 10px;
    }

    .left-sidebar.collapsed .sidebar-link i,
    .left-sidebar.collapsed .sidebar-link .ti {
      margin: 0;
    }

    .left-sidebar.collapsed .brand-logo a {
      justify-content: center;
    }

    .left-sidebar.collapsed .collapse {
      display: none !important;
    }
    
    .left-sidebar.collapsed .sidebar-item.sub-item {
      display: none;
    }

    /* Scrollbar */
    .left-sidebar::-webkit-scrollbar {
      width: 3px;
    }

    .left-sidebar::-webkit-scrollbar-track {
      background: transparent;
    }

    .left-sidebar::-webkit-scrollbar-thumb {
      background: var(--fleet-blue);
      border-radius: 3px;
    }

    /* Mobile Sidebar */
    @media (max-width: 768px) {
      .left-sidebar {
        transform: translateX(-100%);
        width: 260px;
      }
      .left-sidebar.open {
        transform: translateX(0);
      }
      .left-sidebar.collapsed {
        width: 260px;
      }
      .left-sidebar.collapsed .brand-text,
      .left-sidebar.collapsed .sidebar-link span:not(.ti),
      .left-sidebar.collapsed .nav-small-cap span,
      .left-sidebar.collapsed .logout-link span {
        display: inline;
      }
      .left-sidebar.collapsed .collapse {
        display: block !important;
      }
      .left-sidebar.collapsed .sidebar-item.sub-item {
        display: block;
      }
    }

    .brand-logo {
      padding: 20px 24px;
      border-bottom: 1px solid var(--fleet-border);
      background: var(--fleet-dark);
    }

    .brand-logo a {
      color: var(--fleet-white);
      font-weight: 700;
      font-size: 1.1rem;
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }

    .brand-text {
      font-size: 1rem;
      letter-spacing: 0.5px;
      font-weight: 700;
    }

    .brand-text span {
      color: var(--fleet-blue);
    }

    .brand-logo img {
      border: 2px solid var(--fleet-blue);
      padding: 2px;
      background: white;
      border-radius: 4px;
    }

    /* Sidebar Navigation */
    .sidebar-nav {
      padding: 16px 0 0 0;
      flex: 1;
    }

    .nav-small-cap {
      padding: 8px 24px 4px 24px;
    }

    .nav-small-cap span {
      color: var(--fleet-gray);
      font-size: 0.6rem;
      letter-spacing: 1px;
      text-transform: uppercase;
      font-weight: 600;
    }

    .sidebar-item {
      list-style: none;
    }

    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 20px;
      margin: 2px 12px;
      color: var(--fleet-gray);
      border-radius: 10px;
      transition: all 0.2s ease;
      text-decoration: none;
      font-size: 0.85rem;
      font-weight: 500;
    }

    .sidebar-link:hover {
      background: var(--fleet-hover);
      color: var(--fleet-white);
    }

    .sidebar-item.active .sidebar-link {
      background: var(--fleet-blue);
      color: var(--fleet-white);
    }

    .sidebar-link i, .sidebar-link .ti {
      font-size: 1.2rem;
      width: 24px;
    }

    .sidebar-link .ti-chevron-down {
      transition: transform 0.3s ease;
      margin-left: auto;
      font-size: 0.8rem;
    }
    
    .sidebar-link[aria-expanded="true"] .ti-chevron-down {
      transform: rotate(180deg);
    }

    /* Sub-menu items */
    .sidebar-item.sub-item {
      margin-left: 8px;
    }
    
    .sidebar-item.sub-item .sidebar-link {
      padding: 6px 16px;
      font-size: 0.78rem;
      margin: 1px 8px;
      border-radius: 8px;
    }
    
    .sidebar-item.sub-item .sidebar-link i,
    .sidebar-item.sub-item .sidebar-link .ti {
      font-size: 1rem;
      width: 20px;
    }
    
    .sidebar-item.sub-item.active .sidebar-link {
      background: var(--fleet-blue);
      color: var(--fleet-white);
    }
    
    .sidebar-item.sub-item .sidebar-link:hover {
      background: var(--fleet-hover);
      color: var(--fleet-white);
    }

    /* Sidebar Footer - Logout */
    .sidebar-footer {
      padding: 20px 20px 30px 20px;
      border-top: 1px solid var(--fleet-border);
      margin-top: auto;
    }

    .logout-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 16px;
      color: var(--fleet-gray);
      border-radius: 10px;
      transition: all 0.2s ease;
      text-decoration: none;
      font-size: 0.85rem;
      font-weight: 500;
      background: rgba(42, 125, 225, 0.08);
      width: 100%;
      border: none;
      cursor: pointer;
    }

    .logout-link:hover {
      background: var(--fleet-blue);
      color: var(--fleet-white);
    }

    .logout-link i {
      font-size: 1.2rem;
      width: 24px;
    }

    /* ===================== */
    /* PAGE WRAPPER */
    /* ===================== */
    .page-wrapper {
      margin-left: 280px;
      transition: margin-left 0.3s ease;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .page-wrapper.expanded {
      margin-left: 80px;
    }

    @media (max-width: 768px) {
      .page-wrapper {
        margin-left: 0;
      }
      .page-wrapper.expanded {
        margin-left: 0;
      }
    }

    /* ===================== */
    /* TOPBAR - FLEET WHITE */
    /* ===================== */
    .topbar {
      background: var(--fleet-white);
      border-bottom: 1px solid #e5e7eb;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
      position: sticky;
      top: 0;
      z-index: 999;
      width: 100%;
    }

    .navbar {
      padding: 10px 24px;
    }

    .navbar-nav {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .nav-item {
      list-style: none;
    }

    #headerCollapse, .mobile-menu-toggle {
      background: transparent;
      border: none;
      cursor: pointer;
      padding: 8px;
      border-radius: 8px;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    #headerCollapse i, .mobile-menu-toggle i {
      font-size: 1.4rem;
      color: var(--fleet-dark);
    }

    #headerCollapse:hover, .mobile-menu-toggle:hover {
      background: rgba(42, 125, 225, 0.1);
    }

    #headerCollapse:hover i, .mobile-menu-toggle:hover i {
      color: var(--fleet-blue);
    }

    .mobile-menu-toggle {
      display: none;
    }

    @media (max-width: 768px) {
      .mobile-menu-toggle {
        display: flex;
      }
    }

    /* User Profile */
    .user-profile-img img {
      border: 2px solid var(--fleet-blue);
      transition: 0.2s;
      border-radius: 50%;
      width: 38px;
      height: 38px;
      object-fit: cover;
    }

    .user-profile-img img:hover {
      transform: scale(1.05);
      border-color: var(--fleet-blue-light);
    }

    /* Dropdown Menu - FIXED Z-INDEX */
    .dropdown-menu {
      border-radius: 12px;
      border: 1px solid #e5e7eb;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      min-width: 220px;
      padding: 8px;
      z-index: 9999 !important;
      position: absolute !important;
      right: 0 !important;
      left: auto !important;
      top: 100% !important;
      margin-top: 8px !important;
      background: var(--fleet-white) !important;
      display: none;
    }

    .dropdown-menu.show {
      display: block !important;
    }

    .profile-dropdown {
      background: var(--fleet-white);
      border-radius: 12px;
      overflow: hidden;
    }

    .profile-dropdown .dropdown-header {
      background: linear-gradient(135deg, var(--fleet-blue) 0%, var(--fleet-dark) 100%);
      color: var(--fleet-white);
      padding: 14px 18px;
    }

    .profile-dropdown .dropdown-header h5 {
      margin: 0;
      font-size: 0.9rem;
      font-weight: 600;
    }

    .profile-info {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      border-bottom: 1px solid #e5e7eb;
    }

    .profile-info h6 {
      margin: 0;
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--fleet-dark);
    }

    .profile-info span {
      font-size: 0.6rem;
      color: var(--fleet-gray-dark);
    }

    /* Dropdown Items */
    .dropdown-item {
      font-size: 0.75rem;
      padding: 6px 12px;
      border-radius: 6px;
      transition: all 0.2s ease;
    }

    .dropdown-item:hover {
      background: var(--fleet-hover);
      color: var(--fleet-blue);
    }

    .dropdown-item.logout-item {
      color: var(--fleet-red) !important;
    }

    .dropdown-item.logout-item:hover {
      background: rgba(255, 107, 107, 0.1);
      color: var(--fleet-red) !important;
    }

    .dropdown-divider {
      margin: 4px 0;
      border-top: 1px solid #e5e7eb;
    }

    .btn-outline-primary {
      border-radius: 8px;
      border: 1px solid var(--fleet-blue);
      color: var(--fleet-blue);
      background: transparent;
      padding: 8px 16px;
      font-weight: 600;
      font-size: 0.75rem;
      transition: 0.2s;
      width: 100%;
    }

    .btn-outline-primary:hover {
      background: var(--fleet-blue);
      color: var(--fleet-white);
    }

    /* Sidebar Overlay */
    .sidebar-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 998;
      display: none;
    }

    .sidebar-overlay.active {
      display: block;
    }

    /* Body Wrapper */
    .body-wrapper {
      background: #f0f4f8;
      flex: 1;
      padding: 24px;
    }

    @media (max-width: 768px) {
      .body-wrapper {
        padding: 16px;
      }
    }

    /* ===================== */
    /* TABLE STYLING - CLEAN, NO BORDERS */
    /* ===================== */
    .table {
      width: 100%;
      border-collapse: collapse;
    }
    
    .table thead th {
      color: var(--fleet-dark);
      font-weight: 600;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 12px 12px;
      border-bottom: 1px solid #e5e7eb;
      background: transparent;
    }
    
    .table tbody td {
      padding: 12px 12px;
      font-size: 13px;
      color: var(--fleet-gray-dark);
      border-bottom: 1px solid #f0f4f8;
      vertical-align: middle;
    }
    
    .table tbody tr:hover td {
      background-color: rgba(42, 125, 225, 0.05);
    }

    /* ===================== */
    /* CARDS - FLEET STYLE */
    /* ===================== */
    .card {
      border: 1px solid #e5e7eb;
      border-radius: 16px;
      background: var(--fleet-white);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
      background-color: var(--fleet-white);
      border-bottom: 1px solid #e5e7eb;
    }

    /* ===================== */
    /* BUTTONS - FLEET STYLE */
    /* ===================== */
    .btn-primary {
      background-color: var(--fleet-blue) !important;
      border-color: var(--fleet-blue) !important;
      color: white !important;
    }
    
    .btn-primary:hover {
      background-color: var(--fleet-dark) !important;
      border-color: var(--fleet-dark) !important;
    }

    .btn-outline-primary {
      border: 2px solid var(--fleet-blue) !important;
      color: var(--fleet-blue) !important;
      font-weight: 600;
      transition: all 0.3s ease;
      background: transparent;
    }
    
    .btn-outline-primary:hover {
      background-color: var(--fleet-blue) !important;
      color: var(--fleet-white) !important;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(42, 125, 225, 0.3);
    }

    /* ===================== */
    /* BADGES - FLEET STYLE */
    /* ===================== */
    .badge.bg-primary {
      background-color: var(--fleet-blue) !important;
      color: white !important;
    }
    
    .badge.bg-success {
      background-color: var(--fleet-green) !important;
      color: white !important;
    }
    
    .badge.bg-warning {
      background-color: var(--fleet-gold) !important;
      color: var(--fleet-dark) !important;
    }

    /* ===================== */
    /* FORM CONTROLS - FLEET STYLE */
    /* ===================== */
    .form-control:focus {
      border-color: var(--fleet-blue) !important;
      box-shadow: 0 0 0 0.2rem rgba(42, 125, 225, 0.25) !important;
    }
    
    .form-check-input:checked {
      background-color: var(--fleet-blue) !important;
      border-color: var(--fleet-blue) !important;
    }

    /* ===================== */
    /* FOOTER */
    /* ===================== */
    .footer {
      background: var(--fleet-white);
      border-top: 1px solid #e5e7eb;
      color: var(--fleet-gray-dark);
      font-size: 0.7rem;
      padding: 12px 24px;
      text-align: center;
    }

    /* Topbar User Profile - Dropdown toggle fix */
    .dropdown-toggle-no-caret::after {
      display: none !important;
    }

    /* Ensure dropdown appears above everything */
    .navbar-nav .dropdown-menu {
      position: absolute !important;
      right: 0 !important;
      left: auto !important;
      top: 100% !important;
    }

    /* Fix for dropdown being hidden behind content */
    .topbar .navbar {
      position: relative;
    }

    .topbar .navbar-nav {
      position: relative;
    }

    .topbar .nav-item.dropdown {
      position: relative;
    }
  </style>
</head>

<body>
  <!-- Mobile Sidebar Overlay -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <div id="main-wrapper">
    <!-- Sidebar Start -->
    <aside class="left-sidebar" id="sidebar">
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="<?=site_url();?>mydashboard" class="text-nowrap logo-img d-flex align-items-center gap-2">
          <img src="<?=base_url('assets/images/logos/sslai.png')?>" style="width: 35px; height: auto;"/>
          <span class="brand-text">DMIS</span>
        </a> 
        <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none d-block d-xl-none" id="closeSidebar">
          <i class="ti ti-x" style="color: var(--fleet-gray); font-size: 1.2rem;"></i>
        </a>
      </div>

      <nav class="sidebar-nav scroll-sidebar" data-simplebar style="height: 100vh !important;">
        <ul id="sidebarnav" style="list-style: none; padding-left: 0;">
          <!-- Home Section -->
          <li class="nav-small-cap">
            <span>HOME</span>
          </li>
          <li class="sidebar-item <?= strpos($current_url, 'mydashboard') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>mydashboard" aria-expanded="false">
              <i class="ti ti-aperture"></i>
              <span>Dashboard</span>
            </a>
          </li>
          
          <!-- Members Management -->
          <li class="nav-small-cap">
            <span>MEMBERS MANAGEMENTS</span>
          </li>
          <li class="sidebar-item <?= strpos($current_url, 'myaccount') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>myaccount?meaction=MAIN" aria-expanded="false">
              <i class="ti ti-settings"></i>
              <span>Account Settings</span>
            </a>
          </li>
          <li class="sidebar-item <?= strpos($current_url, 'mymembers') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>mymembers?meaction=MAIN" aria-expanded="false">
              <i class="ti ti-user-check"></i>
              <span>List of Members</span>
            </a>
          </li>

          <!-- Loan Management -->
          <li class="nav-small-cap">
            <span>LOAN MANAGEMENT</span>
          </li>
          <li class="sidebar-item <?= strpos($current_url, 'myloanavailment') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>myloanavailment?meaction=MAIN" aria-expanded="false">
              <i class="ti ti-cash"></i>
              <span>Loan Availment</span>
            </a>
          </li>
          <li class="sidebar-item <?= strpos($current_url, 'myapprovals') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>myapprovals" aria-expanded="false">
              <i class="ti ti-timeline"></i>
              <span>Loan Approval</span>
              <?php
              // Get pending count for badge
              $pendingCount = $this->db->query("SELECT COUNT(*) as total FROM tbl_loans WHERE approval_status IN ('Pending', 'Submitted')")->getRowArray()['total'];
              if($pendingCount > 0): ?>
              <span class="badge bg-warning text-dark ms-auto rounded-pill"><?=$pendingCount?></span>
              <?php endif; ?>
            </a>
          </li>
          <li class="sidebar-item <?= strpos($current_url, 'myloanprofile') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>myloanprofile?meaction=MAIN" aria-expanded="false">
              <i class="ti ti-file-invoice"></i>
              <span>Loan Profile</span>
            </a>
          </li>

          <!-- Accounting -->
          <li class="nav-small-cap">
            <span>ACCOUNTING</span>
          </li>
          <li class="sidebar-item <?= strpos($current_url, 'myjournalentry') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>myjournalentry?meaction=MAIN" aria-expanded="false">
              <i class="ti ti-clipboard-text"></i>
              <span>Journal Entry</span>
            </a>
          </li>
          <li class="sidebar-item <?= strpos($current_url, 'mycoa') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>mycoa?meaction=MAIN" aria-expanded="false">
              <i class="ti ti-list-check"></i>
              <span>Chart of Accounts</span>
            </a>
          </li>

          <!-- Reports -->
          <li class="nav-small-cap">
            <span>REPORTS</span>
          </li>
          <li class="sidebar-item <?= strpos($current_url, 'myaccountingreport') !== false ? 'active' : ''; ?>">
            <a class="sidebar-link" href="<?=site_url();?>myaccountingreport?meaction=MAIN" aria-expanded="false">
              <i class="ti ti-file"></i>
              <span>Financial Reports</span>
            </a>
          </li> 
        </ul>
      </nav>

      <!-- Logout Section at Bottom -->
      <div class="sidebar-footer">
        <form action="<?= site_url('mylogout'); ?>" method="post" id="logoutForm" style="display: block; width: 100%;">
          <?= csrf_field(); ?>
          <button type="submit" class="logout-link">
            <i class="ti ti-logout"></i>
            <span>Logout</span>
          </button>
        </form>
      </div>
    </aside>
    <!-- Sidebar End -->

    <div class="page-wrapper" id="pageWrapper">
      <!-- Header Start - FLEET STYLE WITH USER PROFILE -->
      <header class="topbar">
        <div class="with-vertical">
          <nav class="navbar navbar-expand-lg p-0 d-flex justify-content-between w-100">
            <!-- Left side - Menu Toggle -->
            <ul class="navbar-nav">
              <li class="nav-item d-block d-xl-none">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                  <i class="ti ti-menu-2"></i>
                </button>
              </li>
              <li class="nav-item d-none d-xl-block">
                <button class="nav-link sidebartoggler" id="sidebarToggle">
                  <i class="ti ti-menu-2"></i>
                </button>
              </li>
            </ul>

            <!-- Right side - User Profile with Logout -->
            <ul class="navbar-nav flex-row ms-auto align-items-center mx-4">
              <li class="nav-item dropdown">
                <a class="nav-link pe-0 dropdown-toggle-no-caret" href="javascript:void(0)" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <div class="d-flex align-items-center">
                    <div class="user-profile-img">
                      <img src="<?=$profile_photo_url?>" class="rounded-circle" width="38" height="38" alt="Profile" />
                    </div>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                  <div class="profile-dropdown">
                    <!-- User Info -->
                    <div class="profile-info">
                      <img src="<?=$profile_photo_url?>" class="rounded-circle" width="40" height="40" alt="Profile" style="border: 2px solid var(--fleet-blue);" />
                      <div>
                        <h6><?=$this->cuser;?></h6>
                        <span><?=$full_name;?></span>
                      </div>
                    </div>
                    
                    <!-- Menu Items -->
                    <div style="padding: 4px 8px;">
                      <a href="<?=site_url();?>myaccount?meaction=MAIN" class="dropdown-item">
                        <i class="bi bi-person-circle me-2"></i> My Profile
                      </a>
                      <a href="#" class="dropdown-item">
                        <i class="bi bi-envelope me-2"></i> Messages
                      </a>
                      <div class="dropdown-divider"></div>
                      <!-- Logout Button -->
                      <form action="<?= site_url('mylogout'); ?>" method="post">
                        <?= csrf_field(); ?>
                        <button type="submit" class="dropdown-item logout-item" style="width: 100%; border: none; background: none; text-align: left;">
                          <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </nav>
        </div>
      </header>
      <!-- Header End -->

      <div class="body-wrapper">
        <!-- CONTENT STARTS HERE - YOUR MODULE CONTENT GOES INSIDE THIS DIV -->