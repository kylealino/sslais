<?php
// =============================================
// SSLAIS - LOAN MANAGEMENT DASHBOARD
// FLEETSYS COLOR PALETTE - MOCKUP DATA ONLY
// =============================================

$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');

// Get current user info
$query = $this->db->query("
    SELECT 
        `full_name`, 
        `division`,
        `section`, 
        `position`,
        `username`
    FROM `myua_user` 
    WHERE `username` = '$this->cuser'
");
$data = $query->getRowArray();
$full_name = $data['full_name'] ?? 'Admin';
$position = $data['position'] ?? 'Loan Officer';
$section = $data['section'] ?? 'Loan Operations';
$division = $data['division'] ?? 'Credit Division';

// Get profile photo from tbl_members
$profile_photo_url = base_url('assets/images/profile/user-1.jpg');
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

// =============================================
// MOCKUP DATA ONLY - NO DATABASE QUERIES
// =============================================

// Loan Stats (Mockup)
$totalMembers = 1250;
$activeLoans = 1000000;
$outstandingBalance = 5200000;
$overdueAmount = 450000;
$dailyCollections = 85000;
$pendingApprovals = 12;

// Recent Members (Mockup)
$recentMembers = [
    ['member_id' => '2026-0057', 'full_name' => 'Dexter Y.', 'membership_date' => '2026-01-15', 'status' => 'Active'],
    ['member_id' => '2026-0061', 'full_name' => 'Rex B. Cas', 'membership_date' => '2026-02-03', 'status' => 'Active'],
    ['member_id' => '2026-0068', 'full_name' => 'Maria C. Santos', 'membership_date' => '2026-02-20', 'status' => 'Active'],
    ['member_id' => '2026-0072', 'full_name' => 'Jose R. Garcia', 'membership_date' => '2026-03-01', 'status' => 'Pending'],
    ['member_id' => '2026-0075', 'full_name' => 'Ana P. Reyes', 'membership_date' => '2026-03-10', 'status' => 'Active'],
];

// Recent Loans (Mockup)
$recentLoans = [
    ['member_name' => 'Dexter Y.', 'loan_amount' => 150000, 'loan_status' => 'Active', 'approval_status' => 'Approved', 'date_applied' => '2026-03-15'],
    ['member_name' => 'Rex B. Cas', 'loan_amount' => 75000, 'loan_status' => 'Active', 'approval_status' => 'Approved', 'date_applied' => '2026-03-12'],
    ['member_name' => 'Maria C. Santos', 'loan_amount' => 200000, 'loan_status' => 'Pending', 'approval_status' => 'Pending', 'date_applied' => '2026-03-10'],
    ['member_name' => 'Jose R. Garcia', 'loan_amount' => 50000, 'loan_status' => 'Pending', 'approval_status' => 'Submitted', 'date_applied' => '2026-03-08'],
    ['member_name' => 'Ana P. Reyes', 'loan_amount' => 100000, 'loan_status' => 'Active', 'approval_status' => 'Approved', 'date_applied' => '2026-03-05'],
];

// Monthly Loan Disbursements (Mockup - Last 6 Months)
$monthlyDisbursements = [
    ['month' => 'Aug', 'total' => 185500],
    ['month' => 'Sep', 'total' => 192300],
    ['month' => 'Oct', 'total' => 178900],
    ['month' => 'Nov', 'total' => 201400],
    ['month' => 'Dec', 'total' => 215700],
    ['month' => 'Jan', 'total' => 210000],
];

// Loan Status Distribution (Mockup)
$loanStatusDistribution = [
    ['loan_status' => 'Active', 'count' => 45],
    ['loan_status' => 'Approved', 'count' => 28],
    ['loan_status' => 'Pending', 'count' => 15],
    ['loan_status' => 'Overdue', 'count' => 8],
    ['loan_status' => 'Rejected', 'count' => 4],
];

// Member Growth (Mockup - Last 6 Months)
$memberGrowth = [
    ['month' => 'Aug', 'total' => 15],
    ['month' => 'Sep', 'total' => 22],
    ['month' => 'Oct', 'total' => 18],
    ['month' => 'Nov', 'total' => 25],
    ['month' => 'Dec', 'total' => 30],
    ['month' => 'Jan', 'total' => 28],
];

echo view('templates/myheader.php');
?>

<style>
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
        --fleet-border: #e5e7eb;
        --fleet-bg: #f0f4f8;
    }

    /* Dashboard Cards */
    .fleet-card {
        background: var(--fleet-white);
        border-radius: 16px;
        border: 1px solid var(--fleet-border);
        transition: all 0.3s ease;
        padding: 16px 18px;
        height: 100%;
    }
    
    .fleet-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -8px rgba(0,0,0,0.08);
        border-color: var(--fleet-blue);
    }
    
    .fleet-value {
        font-size: 28px;
        font-weight: 700;
        line-height: 1.2;
        color: var(--fleet-dark);
    }
    
    .fleet-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--fleet-gray);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 2px;
    }
    
    .fleet-sub {
        font-size: 10px;
        color: var(--fleet-gray-dark);
        margin-top: 2px;
    }
    
    /* Stat Cards */
    .stat-card {
        background: var(--fleet-white);
        border-radius: 16px;
        padding: 14px 16px;
        border: 1px solid var(--fleet-border);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -8px rgba(0,0,0,0.08);
    }
    
    .section-title {
        font-size: 12px;
        font-weight: 600;
        color: var(--fleet-dark);
        margin-bottom: 12px;
        padding-bottom: 4px;
        border-bottom: 2px solid var(--fleet-blue);
        display: inline-block;
    }
    
    /* Profit Card */
    .profit-card {
        background: linear-gradient(135deg, var(--fleet-dark) 0%, var(--fleet-mid) 100%);
        border-radius: 16px;
        padding: 16px 20px;
        color: white;
        height: 100%;
    }
    
    .profit-card .profit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .profit-card .profit-header h6 {
        font-size: 0.75rem;
        font-weight: 600;
        color: rgba(255,255,255,0.6);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
    }
    
    .profit-card .profit-amount {
        font-size: 1.6rem;
        font-weight: 700;
        color: white;
        line-height: 1.2;
    }
    
    .profit-card .profit-change {
        font-size: 0.65rem;
        font-weight: 600;
        padding: 1px 10px;
        border-radius: 30px;
        background: rgba(52, 199, 89, 0.15);
        color: var(--fleet-green);
        display: inline-block;
        margin-left: 6px;
    }
    
    .profit-card .profit-years {
        display: flex;
        gap: 12px;
        margin-top: 4px;
        font-size: 0.7rem;
        color: rgba(255,255,255,0.5);
    }
    
    .profit-card .profit-years .active {
        color: white;
        font-weight: 600;
    }
    
    .profit-card .mini-chart {
        height: 30px;
        margin-top: 6px;
    }
    
    /* Progress Bars */
    .progress {
        background-color: #f3f4f6;
        border-radius: 8px;
        height: 4px;
    }
    
    .progress-bar {
        border-radius: 8px;
    }
    
    /* Badges */
    .badge {
        font-size: 9px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 30px;
    }
    
    .status-badge {
        padding: 2px 10px;
        border-radius: 30px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    
    .status-badge.approved { background: rgba(52, 199, 89, 0.12); color: var(--fleet-green); }
    .status-badge.pending { background: rgba(245, 179, 66, 0.12); color: var(--fleet-gold); }
    .status-badge.active { background: rgba(42, 125, 225, 0.12); color: var(--fleet-blue); }
    .status-badge.overdue { background: rgba(255, 107, 107, 0.12); color: var(--fleet-red); }
    .status-badge.rejected { background: rgba(255, 107, 107, 0.12); color: var(--fleet-red); }
    .status-badge.submitted { background: rgba(42, 125, 225, 0.12); color: var(--fleet-blue); }
    
    /* Activity Items */
    .activity-item {
        padding: 6px 0;
        border-bottom: 1px solid var(--fleet-border);
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.7rem;
    }
    
    .activity-icon.bg-danger { background: rgba(255, 107, 107, 0.15); color: var(--fleet-red); }
    .activity-icon.bg-info { background: rgba(42, 125, 225, 0.15); color: var(--fleet-blue); }
    .activity-icon.bg-success { background: rgba(52, 199, 89, 0.15); color: var(--fleet-green); }
    .activity-icon.bg-warning { background: rgba(245, 179, 66, 0.15); color: var(--fleet-gold); }
    
    .activity-content .title {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--fleet-dark);
    }
    
    .activity-content .message {
        font-size: 0.65rem;
        color: var(--fleet-gray-dark);
        margin: 0;
    }
    
    .activity-content .time {
        font-size: 0.55rem;
        color: var(--fleet-gray);
    }
    
    /* Tables */
    .table td, .table th {
        padding: 6px 6px;
        vertical-align: middle;
        font-size: 11px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .fleet-value {
            font-size: 22px;
        }
        .profit-card .profit-amount {
            font-size: 1.3rem;
        }
    }
</style>

<div class="container-fluid px-0">
    
    <!-- Header Welcome Section -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="fw-semibold mb-0" style="color: var(--fleet-dark);">Dashboard</h5>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">Loan Operations Overview · <?= htmlspecialchars($full_name) ?></p>
        </div>
        <div class="text-end">
            <div class="mb-0">
                <span class="text-muted me-2" style="font-size: 0.75rem;"><i class="bi bi-calendar3"></i> <?= date('F d, Y') ?></span>
                <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-clock"></i> <span id="liveClock"><?= date('h:i A') ?></span></span>
            </div>
        </div>
    </div>

    <!-- ========================================= -->
    <!-- TOP STATS ROW - 4 Cards -->
    <!-- ========================================= -->
    <div class="row g-3 mb-3">
        <!-- Total Members -->
        <div class="col-xl-3 col-md-6">
            <div class="profit-card">
                <div class="profit-header">
                    <h6>Total Members</h6>
                    <span class="badge" style="background: rgba(42, 125, 225, 0.2); color: var(--fleet-blue);">Active</span>
                </div>
                <div>
                    <span class="profit-amount"><?= number_format($totalMembers) ?></span>
                    <span class="profit-change"><i class="bi bi-arrow-up"></i> +12%</span>
                </div>
                <div class="profit-years">
                    <span class="active">2024</span>
                    <span>2025</span>
                </div>
                <div class="mini-chart">
                    <canvas id="membersMiniChart" style="height: 30px; width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Active Loans -->
        <div class="col-xl-3 col-md-6">
            <div class="fleet-card">
                <div class="fleet-label">Active Loans</div>
                <div class="fleet-value">₱<?= number_format($activeLoans, 2) ?></div>
                <div class="fleet-sub"><span class="text-success"><i class="bi bi-arrow-up"></i> +5.2%</span> vs last month</div>
            </div>
        </div>

        <!-- Outstanding Balance -->
        <div class="col-xl-3 col-md-6">
            <div class="fleet-card">
                <div class="fleet-label">Outstanding Balance</div>
                <div class="fleet-value">₱<?= number_format($outstandingBalance, 2) ?></div>
                <div class="fleet-sub"><span class="text-danger"><i class="bi bi-arrow-up"></i> ₱<?= number_format($overdueAmount, 2) ?></span> overdue</div>
            </div>
        </div>

        <!-- Daily Collections -->
        <div class="col-xl-3 col-md-6">
            <div class="fleet-card">
                <div class="fleet-label">Daily Collections</div>
                <div class="fleet-value">₱<?= number_format($dailyCollections, 2) ?></div>
                <div class="fleet-sub"><span class="text-success"><i class="bi bi-arrow-up"></i> 85%</span> of target</div>
            </div>
        </div>
    </div>

    <!-- ========================================= -->
    <!-- MIDDLE ROW - 3 Cards -->
    <!-- ========================================= -->
    <div class="row g-3 mb-3">
        <!-- Loan Disbursement Trend -->
        <div class="col-xl-5 col-lg-12">
            <div class="stat-card">
                <h6 class="section-title"><i class="bi bi-graph-up me-1" style="color: var(--fleet-blue);"></i> Loan Disbursement (Last 6 Months)</h6>
                <canvas id="disbursementChart" style="height: 250px; width: 100%;"></canvas>
            </div>
        </div>

        <!-- Loan Status Distribution -->
        <div class="col-xl-3 col-lg-6">
            <div class="stat-card">
                <h6 class="section-title"><i class="bi bi-pie-chart-fill me-1" style="color: var(--fleet-blue);"></i> Loan Status</h6>
                <canvas id="loanStatusChart" style="height: 180px; width: 100%;"></canvas>
                <div class="row mt-2 text-center small g-0">
                    <?php 
                    $colors = ['#2a7de1', '#34c759', '#f5b342', '#ff6b6b', '#94a3b8'];
                    $index = 0;
                    foreach($loanStatusDistribution as $status): 
                        $color = $colors[$index % count($colors)];
                    ?>
                    <div class="col-4"><span style="color: <?= $color ?>;">●</span> <?= $status['loan_status'] ?></div>
                    <?php $index++; endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Pending Approvals & Quick Actions -->
        <div class="col-xl-4 col-lg-12">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="section-title mb-0"><i class="bi bi-bell-fill me-1" style="color: var(--fleet-gold);"></i> Pending Approvals</h6>
                    <span style="font-size: 0.65rem; color: var(--fleet-gray);">
                        <span style="color: var(--fleet-blue); font-weight: 600;"><?= $pendingApprovals ?></span> pending
                    </span>
                </div>
                
                <!-- Pending Approvals List -->
                <div class="activity-feed" style="max-height: 180px; overflow-y: auto;">
                    <?php foreach(array_slice($recentLoans, 0, 3) as $loan): ?>
                    <div class="activity-item">
                        <div class="activity-icon bg-<?= $loan['approval_status'] == 'Pending' ? 'warning' : 'info' ?>">
                            <i class="bi bi-file-text"></i>
                        </div>
                        <div class="activity-content">
                            <div class="title"><?= htmlspecialchars($loan['member_name']) ?></div>
                            <p class="message">₱<?= number_format($loan['loan_amount'], 2) ?> · <?= $loan['loan_status'] ?></p>
                            <div class="time"><?= date('M d, Y', strtotime($loan['date_applied'])) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="d-flex gap-2 mt-2">
                    <button class="btn btn-sm" style="background: var(--fleet-blue); color: white; border: none; border-radius: 30px; padding: 4px 16px; font-size: 0.7rem;">
                        <i class="bi bi-check2"></i> Review All
                    </button>
                    <button class="btn btn-sm" style="background: var(--fleet-border); color: var(--fleet-gray-dark); border: none; border-radius: 30px; padding: 4px 16px; font-size: 0.7rem;">
                        <i class="bi bi-plus"></i> New Loan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================= -->
    <!-- BOTTOM ROW - Recent Members + Recent Loans -->
    <!-- ========================================= -->
    <div class="row g-3 mb-3">
        <!-- Recent Members -->
        <div class="col-xl-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="section-title mb-0"><i class="bi bi-people me-1" style="color: var(--fleet-blue);"></i> Recent Members</h6>
                    <a href="<?= site_url('mymembers?meaction=MAIN') ?>" class="text-decoration-none" style="color: var(--fleet-blue); font-size: 0.7rem;">View all →</a>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Joined Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentMembers as $member): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($member['member_id']) ?></strong></td>
                                <td><?= htmlspecialchars($member['full_name']) ?></td>
                                <td><?= date('M d, Y', strtotime($member['membership_date'])) ?></td>
                                <td><span class="status-badge <?= strtolower($member['status']) ?>"><?= $member['status'] ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Loans -->
        <div class="col-xl-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="section-title mb-0"><i class="bi bi-file-text me-1" style="color: var(--fleet-blue);"></i> Recent Loan Applications</h6>
                    <a href="<?= site_url('myloanprofile?meaction=MAIN') ?>" class="text-decoration-none" style="color: var(--fleet-blue); font-size: 0.7rem;">View all →</a>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Approval</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentLoans as $loan): ?>
                            <tr>
                                <td><?= htmlspecialchars($loan['member_name']) ?></td>
                                <td>₱<?= number_format($loan['loan_amount'], 2) ?></td>
                                <td><span class="status-badge <?= strtolower($loan['loan_status']) ?>"><?= $loan['loan_status'] ?></span></td>
                                <td><span class="status-badge <?= strtolower($loan['approval_status']) ?>"><?= $loan['approval_status'] ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Members Mini Chart (Sparkline)
    const membersData = <?php echo json_encode(array_column($memberGrowth, 'total')); ?>;
    const memberLabels = <?php echo json_encode(array_column($memberGrowth, 'month')); ?>;
    
    if(document.getElementById('membersMiniChart')) {
        new Chart(document.getElementById('membersMiniChart'), {
            type: 'line',
            data: {
                labels: memberLabels,
                datasets: [{
                    data: membersData,
                    borderColor: '#f5b342',
                    backgroundColor: 'rgba(245, 179, 66, 0.1)',
                    borderWidth: 2,
                    pointRadius: 0,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { 
                    x: { display: false },
                    y: { display: false } 
                },
                elements: { line: { borderWidth: 2 } }
            }
        });
    }

    // Loan Disbursement Chart
    const disbursementData = <?php echo json_encode($monthlyDisbursements); ?>;
    
    if(document.getElementById('disbursementChart')) {
        new Chart(document.getElementById('disbursementChart'), {
            type: 'line',
            data: {
                labels: disbursementData.map(d => d.month),
                datasets: [{
                    label: 'Loan Disbursements (₱)',
                    data: disbursementData.map(d => d.total),
                    borderColor: '#2a7de1',
                    backgroundColor: 'rgba(42, 125, 225, 0.05)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#2a7de1',
                    pointBorderColor: '#fff',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { 
                    legend: { 
                        display: false 
                    } 
                },
                scales: { 
                    y: { 
                        ticks: { 
                            callback: (v) => '₱' + (v/1000) + 'K', 
                            font: { size: 10 } 
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Loan Status Chart (Doughnut)
    const loanStatusData = <?php echo json_encode($loanStatusDistribution); ?>;
    const statusColors = ['#2a7de1', '#34c759', '#f5b342', '#ff6b6b', '#94a3b8'];
    
    if(document.getElementById('loanStatusChart') && loanStatusData.length > 0) {
        new Chart(document.getElementById('loanStatusChart'), {
            type: 'doughnut',
            data: {
                labels: loanStatusData.map(d => d.loan_status),
                datasets: [{ 
                    data: loanStatusData.map(d => d.count), 
                    backgroundColor: statusColors.slice(0, loanStatusData.length),
                    borderWidth: 0
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: true, 
                cutout: '60%',
                plugins: { legend: { display: false } }
            }
        });
    }

    // Live Clock
    function updateClock() {
        const now = new Date();
        document.getElementById('liveClock').textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>

<?php echo view('templates/myfooter.php'); ?>