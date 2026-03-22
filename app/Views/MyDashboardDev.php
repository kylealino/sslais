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
        --sslais-blue: #2563eb;
        --sslais-indigo: #4f46e5;
        --sslais-success: #10b981;
        --sslais-danger: #ef4444;
        --sslais-warning: #f59e0b;
        --sslais-bg: #f1f5f9;
    }
    
    body { 
        background-color: var(--sslais-bg);
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
    }
    
    .dashboard-wrapper { 
        padding: 1rem;
    }
    
    /* Smaller Cards */
    .stat-card {
        border: none;
        border-radius: 16px;
        background: white;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Smaller Icons */
    .icon-wrapper {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        background: linear-gradient(135deg, rgba(37,99,235,0.1), rgba(79,70,229,0.1));
        color: var(--sslais-blue);
    }
    
    /* Compact Metrics */
    .metric-value {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.2;
    }
    
    .metric-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    /* Smaller Section Cards */
    .section-card {
        background: white;
        border: none;
        border-radius: 16px;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 1rem;
    }
    
    /* Compact Tables */
    .table-compact {
        width: 100%;
        font-size: 0.85rem;
    }
    
    .table-compact td, .table-compact th {
        padding: 0.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .table-compact thead th {
        color: #64748b;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        background: #f8fafc;
    }
    
    /* Smaller Badges */
    .badge-sm {
        padding: 0.25rem 0.75rem;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .badge-active {
        background: #d1fae5;
        color: #065f46;
    }
    
    /* Mini Progress Bar */
    .progress-mini {
        height: 4px;
        border-radius: 100px;
        background: #e2e8f0;
        margin: 0.25rem 0;
    }
    
    .progress-bar-mini {
        height: 4px;
        border-radius: 100px;
        background: linear-gradient(90deg, var(--sslais-blue), var(--sslais-indigo));
    }
    
    /* Stats Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    
    /* Action Buttons */
    .action-btn {
        padding: 0.25rem;
        color: #94a3b8;
        background: none;
        border: none;
        font-size: 1rem;
    }
    
    .action-btn:hover {
        color: var(--sslais-blue);
    }
    
    /* Professional Welcome Section - NEW VERSION */
    .welcome-professional {
        background: white;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        border: 1px solid #e9edf2;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .welcome-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e2a3a;
        margin-bottom: 0.2rem;
    }
    
    .welcome-title span {
        font-weight: 500;
        color: #52677a;
    }
    
    .welcome-subtitle {
        color: #5f6d7c;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .welcome-subtitle i {
        color: var(--sslais-blue);
        font-size: 0.6rem;
    }
    
    .date-badge {
        background: #f2f5f9;
        padding: 0.5rem 1.25rem;
        border-radius: 40px;
        color: #2c3e50;
        font-weight: 500;
        font-size: 0.8rem;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .date-badge i {
        color: var(--sslais-blue);
    }
    /* End of Professional Welcome Section */
    
    /* Two Column Layout for Content */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    /* Quick Action Buttons */
    .quick-action {
        padding: 0.5rem;
        border-radius: 10px;
        background: #f8fafc;
        border: none;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #1e293b;
        width: 100%;
    }
    
    .quick-action:hover {
        background: #e2e8f0;
    }
</style>

<div class="dashboard-wrapper">
    <div class="container-fluid px-0">
        
        <!-- Professional Welcome Section - REPLACED -->
        <div class="welcome-professional">
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

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="icon-wrapper"><i class="bi bi-people-fill"></i></div>
                    <span class="badge-sm bg-light">+12</span>
                </div>
                <div class="metric-value">1,250</div>
                <div class="metric-label">Total Members</div>
            </div>
            
            <div class="stat-card">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="icon-wrapper" style="color: var(--sslais-danger);"><i class="bi bi-cash-stack"></i></div>
                    <span class="badge-sm bg-light">+5.2%</span>
                </div>
                <div class="metric-value">1000000</div>
                <div class="metric-label">Active Loans</div>
                <div class="progress-mini mt-1">
                    <div class="progress-bar-mini" style="width: 78%;"></div>
                </div>
                <small class="text-muted">78% utilized</small>
            </div>
            
            <div class="stat-card">
                <div class="icon-wrapper mb-1" style="color: var(--sslais-danger);"><i class="bi bi-piggy-bank-fill"></i></div>
                <div class="metric-value">₱5.2M</div>
                <div class="metric-label">Outstanding</div>
                <small class="text-danger">₱450K overdue (8.7%)</small>
            </div>
            
            <div class="stat-card">
                <div class="icon-wrapper mb-1" style="color: var(--sslais-warning);"><i class="bi bi-graph-up-arrow"></i></div>
                <div class="metric-value">₱85K</div>
                <div class="metric-label">Daily Collections</div>
                <div class="progress-mini mt-1">
                    <div class="progress-bar-mini" style="width: 85%;"></div>
                </div>
                <small class="text-muted">Target: ₱100K</small>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Member Management -->
            <div class="section-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-people me-1 text-primary"></i>Member Management</h6>
                    <small class="text-muted">Recent activity</small>
                </div>
                
                <table class="table-compact">
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
                            <td><span class="fw-semibold">2026-0057</span></td>
                            <td>
                                Dexter Y. 
                                <small class="text-muted d-block">Joined Jan 2026</small>
                            </td>
                            <td><span class="badge-sm badge-active"><i class="bi bi-circle-fill" style="font-size: 0.4rem;"></i> Active</span></td>
                            <td>2</td>
                            <td>₱150K</td>
                            <td>
                                <button class="action-btn"><i class="bi bi-eye"></i></button>
                                <button class="action-btn"><i class="bi bi-pencil"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="fw-semibold">2026-0061</span></td>
                            <td>
                                Rex B. Cas
                                <small class="text-muted d-block">Joined Feb 2026</small>
                            </td>
                            <td><span class="badge-sm badge-active"><i class="bi bi-circle-fill" style="font-size: 0.4rem;"></i> Active</span></td>
                            <td>1</td>
                            <td>₱75K</td>
                            <td>
                                <button class="action-btn"><i class="bi bi-eye"></i></button>
                                <button class="action-btn"><i class="bi bi-pencil"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <button class="btn btn-light btn-sm w-100 mt-2" style="border-radius: 10px;">
                    <i class="bi bi-plus-circle me-1"></i>View All Members
                </button>
            </div>

            <!-- Recent Ledger Entries -->
            <div class="section-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-journal-text me-1 text-primary"></i>Recent Ledger Entries</h6>
                    <small class="text-muted">Today</small>
                </div>
                
                <table class="table-compact">
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
                            <td class="text-end text-primary">₱514</td>
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
                            <td class="text-end text-primary">₱25,000</td>
                            <td class="text-end">—</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="d-flex gap-2 mt-2">
                    <button class="quick-action"><i class="bi bi-plus-circle" style="color: var(--sslais-blue);"></i>Journal Entry</button>
                    <button class="quick-action"><i class="bi bi-journal-bookmark-fill" style="color: var(--sslais-success);"></i>Ledger</button>
                </div>
            </div>

            <!-- Loan Management Section -->
            <div class="section-card">
                <h6 class="fw-bold mb-2"><i class="bi bi-cash-stack me-1 text-primary"></i>Loan Management</h6>
                <div class="d-flex gap-2 mb-2">
                    <button class="quick-action"><i class="bi bi-file-text"></i>Loan Application</button>
                    <button class="quick-action"><i class="bi bi-folder"></i>Loan Profile</button>
                </div>
                
                <!-- Mini Loan Summary -->
                <div class="mt-2 p-2 bg-light rounded">
                    <div class="d-flex justify-content-between small">
                        <span>Regular Loans</span>
                        <span class="fw-semibold">₱3.2M</span>
                    </div>
                    <div class="progress-mini mb-2">
                        <div class="progress-bar-mini" style="width: 62%;"></div>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span>Emergency Loans</span>
                        <span class="fw-semibold">₱1.5M</span>
                    </div>
                    <div class="progress-mini">
                        <div class="progress-bar-mini" style="width: 29%; background: linear-gradient(90deg, #f59e0b, #fbbf24);"></div>
                    </div>
                </div>
            </div>

            <!-- Reports Section -->
            <div class="section-card">
                <h6 class="fw-bold mb-2"><i class="bi bi-file-earmark-text me-1 text-primary"></i>Reports</h6>
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
        
        <!-- Chart of Accounts Quick View -->
        <div class="section-card mt-1">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-list-columns me-1 text-primary"></i>Chart of Accounts</h6>
                <small class="text-muted">Subsidiary Ledger</small>
            </div>
            <div class="d-flex gap-3 mt-2 small">
                <span><i class="bi bi-circle-fill text-primary me-1" style="font-size: 0.5rem;"></i>Assets: ₱8.2M</span>
                <span><i class="bi bi-circle-fill text-success me-1" style="font-size: 0.5rem;"></i>Liabilities: ₱5.2M</span>
                <span><i class="bi bi-circle-fill text-warning me-1" style="font-size: 0.5rem;"></i>Equity: ₱3.0M</span>
            </div>
        </div>

    </div>
</div>

<?php echo view('templates/myfooter.php'); ?>