<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');

echo view('templates/myheader.php');
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

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

    /* Dashboard Cards - Matching your attendance card style */
    .attendance-card {
        background: var(--white-bg);
        border-radius: 20px;
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
    }

    .attendance-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -12px rgba(0,0,0,0.1);
        border-color: var(--gray-300);
    }

    .attendance-card .card-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
    }

    .attendance-value {
        font-size: 32px;
        font-weight: 700;
        line-height: 1.2;
        color: var(--gray-800);
    }

    .attendance-icon {
        font-size: 42px;
        opacity: 0.12;
        color: var(--gold-primary);
    }

    .attendance-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .attendance-sub {
        font-size: 11px;
        color: var(--gray-400);
        margin-top: 6px;
    }

    /* Section Cards */
    .section-card {
        background: var(--white-bg);
        border-radius: 20px;
        border: 1px solid var(--gray-200);
        padding: 20px;
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--gold-primary);
        display: inline-block;
    }

    /* Welcome Section */
    .welcome-section {
        background: var(--white-bg);
        border-radius: 20px;
        padding: 20px 24px;
        margin-bottom: 24px;
        border: 1px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .welcome-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 4px;
    }

    .welcome-title span {
        color: var(--gold-dark);
    }

    .welcome-subtitle {
        font-size: 12px;
        color: var(--gray-500);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .welcome-subtitle i {
        color: var(--gold-primary);
        font-size: 6px;
    }

    .date-badge {
        background: var(--gray-50);
        padding: 8px 20px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 500;
        color: var(--gray-600);
        border: 1px solid var(--gray-200);
    }

    .date-badge i {
        color: var(--gold-primary);
        margin-right: 6px;
    }

    /* Stats Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    /* Progress Bar */
    .progress-mini {
        height: 4px;
        background: var(--gray-200);
        border-radius: 10px;
        overflow: hidden;
        margin: 8px 0 4px;
    }

    .progress-bar-mini {
        height: 4px;
        background: var(--gold-primary);
        border-radius: 10px;
    }

    /* Tables */
    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }

    .table-custom thead th {
        font-size: 11px;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 8px;
        border-bottom: 1px solid var(--gray-200);
        text-align: left;
    }

    .table-custom tbody td {
        font-size: 12px;
        color: var(--gray-700);
        padding: 10px 8px;
        border-bottom: 1px solid var(--gray-100);
    }

    .table-custom tbody tr:hover td {
        background: var(--gray-50);
    }

    /* Badges */
    .badge-sm {
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-active {
        background: #ecfdf5;
        color: #10b981;
    }

    /* Action Buttons */
    .action-btn {
        background: transparent;
        border: none;
        padding: 4px;
        color: var(--gray-400);
        transition: all 0.2s;
    }

    .action-btn:hover {
        color: var(--gold-primary);
    }

    /* Quick Action Buttons */
    .quick-action {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 500;
        color: var(--gray-700);
        width: 100%;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .quick-action:hover {
        background: var(--gold-soft);
        border-color: var(--gold-primary);
        color: var(--gold-dark);
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    /* Buttons */
    .btn-light-custom {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        padding: 10px;
        font-size: 12px;
        font-weight: 500;
        color: var(--gray-600);
        width: 100%;
        transition: all 0.2s;
    }

    .btn-light-custom:hover {
        background: var(--gold-soft);
        border-color: var(--gold-primary);
        color: var(--gold-dark);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        .content-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }

    @media (max-width: 576px) {
        .stats-row {
            grid-template-columns: 1fr;
        }
        .welcome-section {
            flex-direction: column;
            text-align: center;
            gap: 12px;
        }
        .attendance-value {
            font-size: 24px;
        }
        .attendance-icon {
            font-size: 34px;
        }
    }
</style>

<div class="dashboard-wrapper">
    <div class="ps-3 pe-3">
        
        <!-- Welcome Section - Flat Style -->
        <div class="welcome-section">
            <div>
                <div class="welcome-title">
                    Good afternoon, <span>Admin</span>
                </div>
                <div class="welcome-subtitle">
                    <i class="bi bi-circle-fill"></i>
                    <span>Science Savings and Loan Association</span>
                </div>
            </div>
            <div class="date-badge">
                <i class="bi bi-calendar3"></i>
                Wednesday, March 18, 2026
            </div>
        </div>

        <!-- Stats Row - Attendance Card Style -->
        <div class="stats-row">
            <div class="attendance-card">
                <div class="card-body">
                    <div>
                        <div class="attendance-label">Total Members</div>
                        <div class="attendance-value">1,250</div>
                        <div class="attendance-sub">+12 this month</div>
                    </div>
                    <i class="bi bi-people-fill attendance-icon"></i>
                </div>
            </div>

            <div class="attendance-card">
                <div class="card-body">
                    <div>
                        <div class="attendance-label">Active Loans</div>
                        <div class="attendance-value">₱1,000,000</div>
                        <div class="attendance-sub">+5.2% from last month</div>
                        <div class="progress-mini">
                            <div class="progress-bar-mini" style="width: 78%;"></div>
                        </div>
                    </div>
                    <i class="bi bi-cash-stack attendance-icon"></i>
                </div>
            </div>

            <div class="attendance-card">
                <div class="card-body">
                    <div>
                        <div class="attendance-label">Outstanding</div>
                        <div class="attendance-value">₱5.2M</div>
                        <div class="attendance-sub">₱450K overdue</div>
                    </div>
                    <i class="bi bi-piggy-bank-fill attendance-icon"></i>
                </div>
            </div>

            <div class="attendance-card">
                <div class="card-body">
                    <div>
                        <div class="attendance-label">Daily Collections</div>
                        <div class="attendance-value">₱85K</div>
                        <div class="attendance-sub">85% of target</div>
                        <div class="progress-mini">
                            <div class="progress-bar-mini" style="width: 85%;"></div>
                        </div>
                    </div>
                    <i class="bi bi-graph-up-arrow attendance-icon"></i>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Member Management -->
            <div class="section-card">
                <h6 class="section-title">
                    <i class="bi bi-people me-2" style="color: var(--gold-primary);"></i>Member Management
                </h6>
                
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Member ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Loans</th>
                            <th>Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>2026-0057</strong></td>
                            <td>
                                Dexter Y.
                                <div class="text-muted" style="font-size: 10px;">Joined Jan 2026</div>
                            </td>
                            <td><span class="badge-sm badge-active"><i class="bi bi-circle-fill" style="font-size: 6px;"></i> Active</span></td>
                            <td>2</td>
                            <td>₱150K</td>
                            <td>
                                <button class="action-btn"><i class="bi bi-eye"></i></button>
                                <button class="action-btn"><i class="bi bi-pencil"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2026-0061</strong></td>
                            <td>
                                Rex B. Cas
                                <div class="text-muted" style="font-size: 10px;">Joined Feb 2026</div>
                            </td>
                            <td><span class="badge-sm badge-active"><i class="bi bi-circle-fill" style="font-size: 6px;"></i> Active</span></td>
                            <td>1</td>
                            <td>₱75K</td>
                            <td>
                                <button class="action-btn"><i class="bi bi-eye"></i></button>
                                <button class="action-btn"><i class="bi bi-pencil"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <button class="btn-light-custom mt-3">
                    <i class="bi bi-plus-circle me-1"></i>View All Members
                </button>
            </div>

            <!-- Recent Ledger Entries -->
            <div class="section-card">
                <h6 class="section-title">
                    <i class="bi bi-journal-text me-2" style="color: var(--gold-primary);"></i>Recent Ledger Entries
                </h6>
                
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Account</th>
                            <th class="text-end">Debit</th>
                            <th class="text-end">Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code class="bg-light px-1 rounded">02-101...0070</code></td>
                            <td>Loan Repayment</td>
                            <td class="text-end" style="color: var(--gold-dark);">₱514</td>
                            <td class="text-end">—</td>
                        </tr>
                        <tr>
                            <td><code class="bg-light px-1 rounded">01-104...0066</code></td>
                            <td>Interest Earned</td>
                            <td class="text-end">—</td>
                            <td class="text-end text-success">₱485</td>
                        </tr>
                        <tr>
                            <td><code class="bg-light px-1 rounded">02-105...0089</code></td>
                            <td>Loan Disbursement</td>
                            <td class="text-end" style="color: var(--gold-dark);">₱25,000</td>
                            <td class="text-end">—</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="d-flex gap-2 mt-3">
                    <button class="quick-action"><i class="bi bi-plus-circle"></i>Journal Entry</button>
                    <button class="quick-action"><i class="bi bi-journal-bookmark-fill"></i>Ledger</button>
                </div>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="section-card">
                    <h6 class="section-title">
                        <i class="bi bi-cash-stack me-2" style="color: var(--gold-primary);"></i>Loan Management
                    </h6>
                    <div class="d-flex gap-2 mb-3">
                        <button class="quick-action"><i class="bi bi-file-text"></i>Loan Application</button>
                        <button class="quick-action"><i class="bi bi-folder"></i>Loan Profile</button>
                    </div>
                    
                    <div class="p-3 bg-light rounded-3" style="border-left: 3px solid var(--gold-primary);">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Regular Loans</span>
                            <span class="fw-semibold">₱3.2M</span>
                        </div>
                        <div class="progress-mini">
                            <div class="progress-bar-mini" style="width: 62%;"></div>
                        </div>
                        <div class="d-flex justify-content-between small mt-2 mb-1">
                            <span>Emergency Loans</span>
                            <span class="fw-semibold">₱1.5M</span>
                        </div>
                        <div class="progress-mini">
                            <div class="progress-bar-mini" style="width: 29%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="section-card">
                    <h6 class="section-title">
                        <i class="bi bi-file-earmark-text me-2" style="color: var(--gold-primary);"></i>Reports
                    </h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <button class="quick-action"><i class="bi bi-receipt"></i>Cash Receipts</button>
                        </div>
                        <div class="col-6">
                            <button class="quick-action"><i class="bi bi-credit-card"></i>Cash Disbursement</button>
                        </div>
                        <div class="col-6">
                            <button class="quick-action"><i class="bi bi-file-spreadsheet"></i>Balance Sheet</button>
                        </div>
                        <div class="col-6">
                            <button class="quick-action"><i class="bi bi-graph-up"></i>Summary</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart of Accounts -->
        <div class="section-card mt-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="section-title mb-0">
                    <i class="bi bi-list-columns me-2" style="color: var(--gold-primary);"></i>Chart of Accounts
                </h6>
                <small class="text-muted">Subsidiary Ledger</small>
            </div>
            <div class="d-flex gap-4 mt-3 small">
                <span><i class="bi bi-circle-fill me-1" style="color: var(--gold-primary); font-size: 8px;"></i>Assets: ₱8.2M</span>
                <span><i class="bi bi-circle-fill me-1" style="color: var(--gold-light); font-size: 8px;"></i>Liabilities: ₱5.2M</span>
                <span><i class="bi bi-circle-fill me-1" style="color: var(--gold-dark); font-size: 8px;"></i>Equity: ₱3.0M</span>
            </div>
        </div>

    </div>
</div>

<?php echo view('templates/myfooter.php'); ?>