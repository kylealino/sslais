<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

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
        padding: 14px 20px;
    }

    .card-header h6 {
        font-size: 14px;
        font-weight: 600;
        color: var(--navy-dark);
        margin: 0;
    }

    .card-body {
        padding: 20px;
    }

    /* Report Type Selector - Filter Buttons Style */
    .report-type-selector {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }
    
    .report-type-btn {
        padding: 6px 20px;
        font-size: 12px;
        border-radius: 30px;
        border: 1px solid var(--gray-200);
        background: var(--white-bg);
        color: var(--gray-600);
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
    }
    
    .report-type-btn.active {
        background: var(--gold-primary);
        border-color: var(--gold-primary);
        color: var(--navy-dark);
    }
    
    .report-type-btn:hover:not(.active) {
        background: var(--gold-soft);
        border-color: var(--gold-primary);
        color: var(--gold-dark);
    }

    /* Date Controls */
    .date-controls {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px;
    }
    
    .date-controls .form-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray-500);
        margin-bottom: 6px;
        display: block;
    }
    
    .date-controls .form-control-sm {
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
        transition: all 0.2s ease;
        width: 100%;
    }
    
    .date-controls .form-control-sm:focus {
        border-color: var(--gold-primary);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        outline: none;
    }
    
    /* Generate Button */
    .btn-generate {
        background: var(--gold-primary);
        border: none;
        color: var(--navy-dark);
        padding: 8px 24px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    
    .btn-generate:hover {
        background: var(--gold-dark);
        transform: translateY(-1px);
        color: white;
    }
    
    /* PDF Viewer */
    .pdf-viewer {
        width: 100%;
        height: 600px;
        border: 1px solid var(--gray-200);
        border-radius: 16px;
        position: relative;
        background: var(--gray-50);
        overflow: hidden;
    }
    
    .pdf-frame {
        width: 100%;
        height: 100%;
        border: none;
        display: none;
    }
    
    .pdf-placeholder {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: var(--gray-50);
        color: var(--gray-400);
        text-align: center;
    }
    
    .pdf-placeholder i {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
        color: var(--gold-primary);
    }
    
    .pdf-placeholder p {
        font-size: 14px;
        margin-bottom: 4px;
        color: var(--gray-500);
    }
    
    .pdf-placeholder small {
        font-size: 11px;
        color: var(--gray-400);
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

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 16px;
        }
        .report-type-selector {
            justify-content: center;
        }
        .report-type-btn {
            flex: 1;
            text-align: center;
        }
        .date-controls .row {
            flex-direction: column;
            gap: 12px;
        }
        .btn-generate {
            width: 100%;
            justify-content: center;
        }
        .pdf-viewer {
            height: 400px;
        }
    }
</style>

<div class="ps-3 pe-3">
    <!-- Page Header -->
    <div class="row mb-2 mt-2">
        <div class="col-12">
            <h4 class="fw-semibold my-3">Accounting Reports</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="text-muted text-decoration-none" href="<?=site_url();?>mydashboard">
                            <i class="ti ti-home fs-5"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">Accounting</li>
                    <li class="breadcrumb-item active">Financial Reports</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <!-- Main Report Card -->
    <div class="card">
        <div class="card-header">
            <h6>
                <i class="ti ti-file-report me-2" style="color: var(--gold-primary);"></i>
                Financial Reports
            </h6>
        </div>
        
        <div class="card-body">
            <!-- Report Type Selector -->
            <div class="report-type-selector">
                <button class="report-type-btn active" data-report="cash-receipts">
                    <i class="ti ti-receipt"></i> Cash Receipts
                </button>
                <button class="report-type-btn" data-report="cash-disbursement">
                    <i class="ti ti-cash-banknote"></i> Cash Disbursement
                </button>
                <button class="report-type-btn" data-report="balance-sheet">
                    <i class="ti ti-report-money"></i> Balance Sheet
                </button>
                <button class="report-type-btn" data-report="income-statement">
                    <i class="ti ti-chart-line"></i> Income Statement
                </button>
                <button class="report-type-btn" data-report="trial-balance">
                    <i class="ti ti-scale"></i> Trial Balance
                </button>
            </div>
            
            <!-- Date Controls -->
            <div id="dateControls">
                <!-- Populated by JavaScript -->
            </div>
            
            <!-- PDF Viewer -->
            <div class="pdf-viewer">
                <iframe id="reportFrame" class="pdf-frame"></iframe>
                <div id="reportPlaceholder" class="pdf-placeholder">
                    <i class="ti ti-file-report"></i>
                    <p>Select a report type and date range</p>
                    <small>Click "Generate Report" to view</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    let currentReport = 'cash-receipts';
    
    // Report configurations
    const reports = {
        'cash-receipts': {
            title: 'Cash Receipts Journal',
            url: '<?= base_url('myaccountingreport?meaction=cash-receipts')?>',
            type: 'range',
            icon: 'ti ti-receipt'
        },
        'cash-disbursement': {
            title: 'Cash Disbursement Journal',
            url: '<?= base_url('myaccountingreport?meaction=cash-disbursement')?>',
            type: 'range',
            icon: 'ti ti-cash-banknote'
        },
        'balance-sheet': {
            title: 'Balance Sheet',
            url: '<?= base_url('myaccountingreport?meaction=balance-sheet')?>',
            type: 'single',
            icon: 'ti ti-report-money'
        },
        'income-statement': {
            title: 'Income Statement',
            url: '<?= base_url('myaccountingreport?meaction=income-statement')?>',
            type: 'range',
            icon: 'ti ti-chart-line'
        },
        'trial-balance': {
            title: 'Trial Balance',
            url: '<?= base_url('myaccountingreport?meaction=trial-balance')?>',
            type: 'range',
            icon: 'ti ti-scale'
        }
    };
    
    // Render date controls based on report type
    function renderDateControls(reportKey) {
        const report = reports[reportKey];
        const isRange = report.type === 'range';
        
        let html = `
            <div class="date-controls">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">${isRange ? 'FROM DATE' : 'REPORT DATE'}</label>
                        <input type="date" id="dateFrom" class="form-control form-control-sm">
                    </div>
        `;
        
        if (isRange) {
            html += `
                    <div class="col-md-4">
                        <label class="form-label">TO DATE</label>
                        <input type="date" id="dateTo" class="form-control form-control-sm">
                    </div>
            `;
        }
        
        html += `
                    <div class="col-md-4">
                        <button class="btn-generate" onclick="generateReport()">
                            <i class="ti ti-printer"></i>
                            Generate Report
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        $('#dateControls').html(html);
        
        // Set default dates
        const today = new Date().toISOString().split('T')[0];
        const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
        
        $('#dateFrom').val(firstDayOfMonth);
        if (isRange) {
            $('#dateTo').val(today);
        }
    }
    
    // Generate and load report
    window.generateReport = function() {
        const report = reports[currentReport];
        const isRange = report.type === 'range';
        
        let fullUrl = report.url;
        
        if (isRange) {
            const fromDate = $('#dateFrom').val();
            const toDate = $('#dateTo').val();
            
            if (!fromDate || !toDate) {
                alert('Please select both FROM and TO dates');
                return;
            }
            
            fullUrl += '&from_date=' + fromDate + '&to_date=' + toDate;
        } else {
            const date = $('#dateFrom').val();
            
            if (!date) {
                alert('Please select a date');
                return;
            }
            
            fullUrl += '&as_of_date=' + date;
        }
        
        // Load PDF
        $('#reportFrame').attr('src', fullUrl).show();
        $('#reportPlaceholder').hide();
    };
    
    // Handle report type selection
    $('.report-type-btn').click(function() {
        $('.report-type-btn').removeClass('active');
        $(this).addClass('active');
        
        currentReport = $(this).data('report');
        renderDateControls(currentReport);
        
        // Reset PDF viewer
        $('#reportFrame').hide().attr('src', '');
        $('#reportPlaceholder').show();
    });
    
    // Initialize with default report
    renderDateControls('cash-receipts');
});
</script>

<?php echo view('templates/myfooter.php'); ?>