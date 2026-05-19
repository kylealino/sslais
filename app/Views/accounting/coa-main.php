<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

// Get account_id from URL for editing
$account_id = $this->request->getGet('account_id');

$account_code = "";
$account_name = "";
$account_type = "";
$parent_code = "";
$is_active = "1";

if(!empty($account_id)) { 
    $query = $this->db->query("SELECT * FROM tbl_coa WHERE account_id = '$account_id'");
    $data = $query->getRowArray();
    if($data) {
        $account_code = $data['account_code'] ?? '';
        $account_name = $data['account_name'] ?? '';
        $account_type = $data['account_type'] ?? '';
        $parent_code = $data['parent_code'] ?? '';
        $is_active = $data['is_active'] ?? 1;
    }
}

echo view('templates/myheader.php');
?>

<style>
    :root {
        --navy-dark: #0a1a3a;
        --navy-medium: #1a2e5a;
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

    /* Attendance Card Style - Matching Other Modules */
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

    /* Status Pills */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 30px;
        letter-spacing: 0.3px;
    }

    .status-pill::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-active {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    .status-active::before { background: #10b981; }

    .status-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    .status-inactive::before { background: #ef4444; }

    /* Account Type Badges */
    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        font-size: 10px;
        font-weight: 600;
        border-radius: 30px;
        letter-spacing: 0.3px;
    }
    .type-asset { background: #dbeafe; color: #1e40af; }
    .type-liability { background: #fed7aa; color: #9a3412; }
    .type-equity { background: #dcfce7; color: #166534; }
    .type-revenue { background: #d1fae5; color: #065f46; }
    .type-expense { background: #fee2e2; color: #991b1b; }

    /* Cards */
    .card {
        border: 1px solid var(--gray-200);
        border-radius: 20px;
        background: var(--white-bg);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .card-header {
        background: var(--white-bg);
        border-bottom: 1px solid var(--gray-200);
        padding: 16px 20px;
    }

    .card-body {
        padding: 20px;
    }

    /* Form Controls */
    .form-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--gold-primary);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        outline: none;
    }

    /* Buttons */
    .btn-save {
        background: var(--gold-primary);
        border: none;
        border-radius: 10px;
        padding: 8px 24px;
        font-size: 12px;
        font-weight: 600;
        color: var(--navy-dark);
        transition: all 0.2s;
    }

    .btn-save:hover {
        background: var(--gold-dark);
        transform: translateY(-1px);
        color: white;
    }

    .btn-update {
        background: var(--navy-dark);
        border: none;
        border-radius: 10px;
        padding: 8px 24px;
        font-size: 12px;
        font-weight: 600;
        color: white;
        transition: all 0.2s;
    }

    .btn-update:hover {
        background: var(--navy-medium);
        transform: translateY(-1px);
    }

    .btn-outline-secondary {
        background: transparent;
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        padding: 6px 20px;
        font-size: 12px;
        transition: all 0.2s;
    }

    .btn-outline-secondary:hover {
        border-color: var(--gold-primary);
        color: var(--gold-dark);
    }

    /* Account Tree */
    .account-tree {
        font-size: 0.875rem;
    }
    
    .account-item {
        margin-bottom: 0;
        border-bottom: 1px solid var(--gray-100);
    }
    
    .account-item:last-child {
        border-bottom: none;
    }
    
    .account-card {
        background: var(--white-bg);
        padding: 12px 16px;
        transition: all 0.2s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        border-left: 2px solid transparent;
    }
    
    .account-card:hover {
        background: var(--gold-soft);
        border-left-color: var(--gold-primary);
    }
    
    .account-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .account-code {
        font-family: monospace;
        font-weight: 600;
        font-size: 12px;
        background: var(--gray-50);
        padding: 4px 10px;
        border-radius: 6px;
        color: var(--gold-dark);
    }
    
    .account-name {
        font-weight: 500;
        color: var(--gray-700);
        font-size: 13px;
    }
    
    .account-actions {
        display: flex;
        gap: 0.5rem;
        opacity: 0.5;
        transition: opacity 0.2s ease;
    }
    
    .account-card:hover .account-actions {
        opacity: 1;
    }
    
    .action-icon {
        background: none;
        border: none;
        padding: 6px;
        cursor: pointer;
        color: var(--gray-500);
        transition: all 0.2s ease;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .action-icon:hover {
        color: var(--gold-dark);
        background: var(--gold-soft);
    }

    /* Filter Buttons */
    .filter-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .filter-btn {
        padding: 5px 16px;
        font-size: 11px;
        border-radius: 30px;
        border: 1px solid var(--gray-200);
        background: var(--white-bg);
        color: var(--gray-600);
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
    }
    
    .filter-btn.active {
        background: var(--gold-primary);
        border-color: var(--gold-primary);
        color: var(--navy-dark);
    }
    
    .filter-btn:hover:not(.active) {
        background: var(--gold-soft);
        border-color: var(--gold-primary);
        color: var(--gold-dark);
    }

    /* Edit Mode Badge */
    .edit-mode-badge {
        background: var(--gold-soft);
        color: var(--gold-dark);
        font-size: 11px;
        padding: 6px 16px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    /* Breadcrumb */
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 1rem;
    }

    .breadcrumb-item a {
        text-decoration: none;
        color: var(--gray-500);
        font-size: 12px;
    }

    .breadcrumb-item.active {
        color: var(--gold-dark);
        font-weight: 600;
    }

    /* Stats Cards Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        .account-info {
            gap: 0.5rem;
        }
        .account-code {
            font-size: 10px;
        }
        .account-name {
            font-size: 11px;
        }
        .filter-group {
            margin-top: 10px;
        }
        .btn-save, .btn-update, .btn-outline-secondary {
            width: 100%;
            margin-top: 5px;
        }
    }
</style>

<div class="container-fluid">
    <div class="row me-mycoa-outp-msg mx-0">
    </div>
    <input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />
    
    <!-- Page Header -->
    <div class="row mb-2 mt-2">
        <div class="col-12">
            <h4 class="fw-semibold my-3">Chart of Accounts</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="text-muted text-decoration-none" href="<?=site_url();?>mydashboard">
                            <i class="ti ti-home fs-5"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">Accounting</li>
                    <li class="breadcrumb-item active">Chart of Accounts</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <!-- Stats Overview - Attendance Card Style -->
    <?php
    $totalAccountsQuery = $this->db->query("SELECT COUNT(*) as total FROM tbl_coa")->getRowArray();
    $activeAccountsQuery = $this->db->query("SELECT COUNT(*) as total FROM tbl_coa WHERE is_active = 1")->getRowArray();
    $assetCountQuery = $this->db->query("SELECT COUNT(*) as total FROM tbl_coa WHERE account_type = 'Asset'")->getRowArray();
    $expenseCountQuery = $this->db->query("SELECT COUNT(*) as total FROM tbl_coa WHERE account_type = 'Expense'")->getRowArray();
    ?>
    <div class="stats-grid">
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Total Accounts</div>
                    <div class="attendance-value"><?= number_format($totalAccountsQuery['total'] ?? 0); ?></div>
                    <div class="attendance-sub">All accounts</div>
                </div>
                <i class="ti ti-chart-bar attendance-icon"></i>
            </div>
        </div>
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Active</div>
                    <div class="attendance-value"><?= number_format($activeAccountsQuery['total'] ?? 0); ?></div>
                    <div class="attendance-sub">Active accounts</div>
                </div>
                <i class="ti ti-circle-check attendance-icon"></i>
            </div>
        </div>
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Assets</div>
                    <div class="attendance-value"><?= number_format($assetCountQuery['total'] ?? 0); ?></div>
                    <div class="attendance-sub">Asset accounts</div>
                </div>
                <i class="ti ti-wallet attendance-icon"></i>
            </div>
        </div>
        <div class="attendance-card">
            <div class="card-body">
                <div>
                    <div class="attendance-label">Expenses</div>
                    <div class="attendance-value"><?= number_format($expenseCountQuery['total'] ?? 0); ?></div>
                    <div class="attendance-sub">Expense accounts</div>
                </div>
                <i class="ti ti-shopping-cart attendance-icon"></i>
            </div>
        </div>
    </div>
    
    <!-- Add/Edit Account Card -->
    <div class="card">
        <div class="card-header">
            <h6 class="fw-semibold mb-0">
                <i class="ti ti-pencil me-2" style="color: var(--gold-primary);"></i>
                <?= !empty($account_id) ? 'Edit Account' : 'Add New Account'; ?>
            </h6>
        </div>
        <div class="card-body">
            <?php if(!empty($account_id)): ?>
                <div class="edit-mode-badge">
                    <i class="ti ti-edit fs-6"></i>
                    Editing: <?= $account_code; ?> - <?= $account_name; ?>
                </div>
            <?php endif; ?>
            
            <form class="mycoa-validation" id="coaForm">
                <input type="hidden" name="account_id" id="account_id" value="<?= $account_id; ?>">
                
                <div class="row">
                    <!-- LEFT COLUMN -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Account Code</label>
                            <input type="text" name="account_code" id="account_code" class="form-control form-control-sm" value="<?= $account_code; ?>" placeholder="e.g., 1010" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Account Name</label>
                            <input type="text" name="account_name" id="account_name" class="form-control form-control-sm" value="<?= $account_name; ?>" placeholder="e.g., Cash on Hand" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Account Type</label>
                            <select name="account_type" id="account_type" class="form-select form-select-sm" required>
                                <option value="">Select Type</option>
                                <option value="Asset" <?= $account_type == 'Asset' ? 'selected' : ''; ?>>Asset</option>
                                <option value="Liability" <?= $account_type == 'Liability' ? 'selected' : ''; ?>>Liability</option>
                                <option value="Equity" <?= $account_type == 'Equity' ? 'selected' : ''; ?>>Equity</option>
                                <option value="Revenue" <?= $account_type == 'Revenue' ? 'selected' : ''; ?>>Revenue</option>
                                <option value="Expense" <?= $account_type == 'Expense' ? 'selected' : ''; ?>>Expense</option>
                            </select>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Parent Account</label>
                            <select name="parent_code" id="parent_code" class="form-select form-select-sm">
                                <option value="">— None (Main Account) —</option>
                                <?php
                                $parents = $this->db->query("SELECT account_code, account_name FROM tbl_coa WHERE account_code != '$account_code' OR account_code IS NULL ORDER BY account_code")->getResultArray();
                                foreach($parents as $p) {
                                    $selected = ($parent_code == $p['account_code']) ? 'selected' : '';
                                    echo '<option value="' . $p['account_code'] . '" ' . $selected . '>' . $p['account_code'] . ' - ' . $p['account_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_active" id="is_active" class="form-select form-select-sm">
                                <option value="1" <?= $is_active == '1' ? 'selected' : ''; ?>>Active</option>
                                <option value="0" <?= $is_active == '0' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="text-end mt-3">
                    <?php if(!empty($account_id)): ?>
                        <a href="<?= site_url('mycoa?meaction=MAIN'); ?>" class="btn-outline-secondary btn-sm me-2">
                            <i class="ti ti-x"></i> Cancel
                        </a>
                    <?php endif; ?>
                    <button type="submit" class="<?= empty($account_id) ? 'btn-save' : 'btn-update' ?>">
                        <i class="ti ti-device-floppy me-1"></i>
                        <?= empty($account_id) ? 'Save Account' : 'Update Account'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Chart of Accounts Structure Card -->
    <div class="card mt-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="fw-semibold mb-0">
                    <i class="ti ti-list-tree me-2" style="color: var(--gold-primary);"></i>
                    Chart of Accounts Structure
                </h6>
                <div class="filter-group">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="Asset">Assets</button>
                    <button class="filter-btn" data-filter="Liability">Liabilities</button>
                    <button class="filter-btn" data-filter="Equity">Equity</button>
                    <button class="filter-btn" data-filter="Revenue">Revenue</button>
                    <button class="filter-btn" data-filter="Expense">Expenses</button>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="account-tree" id="accountTree">
                <?php
                $query = $this->db->query("SELECT * FROM tbl_coa ORDER BY account_code ASC");
                $accounts = $query->getResultArray();
                
                $tree = [];
                foreach ($accounts as $row) {
                    $parentKey = isset($row['parent_code']) && !empty($row['parent_code']) ? $row['parent_code'] : null;
                    $tree[$parentKey][] = $row;
                }
                
                function renderTree($parent, $tree, $level = 0, $filter = 'all') {
                    if (!isset($tree[$parent])) return;
                    
                    foreach ($tree[$parent] as $row) {
                        $display = ($filter === 'all' || $row['account_type'] === $filter);
                        if(!$display && $filter !== 'all') continue;
                        
                        $account_code = $row['account_code'] ?? '';
                        $account_name = $row['account_name'] ?? '';
                        $account_type = $row['account_type'] ?? '';
                        $is_active = $row['is_active'] ?? 1;
                        $account_id = $row['account_id'] ?? '';
                        ?>
                        <div class="account-item" data-type="<?= $account_type; ?>" data-active="<?= $is_active; ?>" style="padding-left: <?= $level * 28 ?>px;">
                            <div class="account-card">
                                <div class="account-info">
                                    <span class="account-code"><?= $account_code; ?></span>
                                    <span class="account-name"><?= htmlspecialchars($account_name); ?></span>
                                    <span class="type-badge type-<?= strtolower($account_type); ?>">
                                        <?= $account_type; ?>
                                    </span>
                                    <span class="status-pill <?= $is_active ? 'status-active' : 'status-inactive'; ?>">
                                        <?= $is_active ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                <div class="account-actions">
                                    <a href="<?= site_url('mycoa?meaction=MAIN&account_id=' . $account_id); ?>" class="action-icon" title="Edit Account">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                        renderTree($account_code, $tree, $level + 1, $filter);
                    }
                }
                ?>
                <div id="treeContent">
                    <?php renderTree(null, $tree, 0, 'all'); ?>
                </div>
                <?php if(empty($accounts)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="ti ti-folder-off fs-1 d-block mb-2"></i>
                        <p class="mb-0">No accounts found. Create your first account above.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/accounting/mycoa.js?v=2');?>"></script>

<script>
$(document).ready(function() {
    // Filter functionality
    $('.filter-btn').click(function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        const filter = $(this).data('filter');
        
        if(filter === 'all') {
            $('.account-item').show();
        } else {
            $('.account-item').hide();
            $(`.account-item[data-type="${filter}"]`).show();
        }
    });
    
    <?php if(!empty($account_id)): ?>
        setTimeout(function() {
            $('html, body').animate({
                scrollTop: $('.card').offset().top - 20
            }, 500);
        }, 300);
    <?php endif; ?>
});

__mysys_coa_ent.__coa_saving();
</script>

<?php
echo view('templates/myfooter.php');
?>