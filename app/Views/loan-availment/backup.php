<div class="row">

    <!-- LEFT: LOAN SUMMARY -->
    <div class="col-md-4">

        <!-- Loan Summary -->
        <div class="card mb-3">
            <div class="card-header fw-bold">Loan Summary</div>
            <div class="card-body">

                <p><strong>Loan Type:</strong> <?= esc($loan_type); ?></p>
                <p><strong>Loan Amount:</strong> ₱<?= number_format((float)$loan_amount, 2); ?></p>
                <p><strong>Interest Rate:</strong> <?= number_format((float)$interest_rate, 2); ?>%</p>
                <p><strong>Term:</strong> <?= (int)$term_months; ?> months</p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-warning"><?= esc($status); ?></span>
                </p>

            </div>
        </div>

        <!-- Outstanding Balance -->
        <div class="card mb-3">
            <div class="card-header fw-bold">Outstanding Balance</div>
            <div class="card-body">

                <?php
                $balanceQuery = $this->db->query("
                    SELECT ending_balance 
                    FROM tbl_loans_ammortization 
                    WHERE loan_id = '$loan_id' 
                    ORDER BY amortization_id DESC 
                    LIMIT 1
                ")->getRowArray();

                $outstanding = isset($balanceQuery['ending_balance']) ? (float)$balanceQuery['ending_balance'] : (float)$loan_amount;
                ?>

                <h4 class="text-danger">₱<?= number_format($outstanding, 2); ?></h4>

            </div>
        </div>

        <!-- Co-Maker -->
        <div class="card">
            <div class="card-header fw-bold">Co-Maker</div>
            <div class="card-body">
                <?php
                foreach($members as $m){
                    if($m['member_id'] == $loan_comakers){
                        echo '<p>' . esc($m['first_name']) . ' ' . esc($m['last_name']) . '</p>';
                    }
                }
                ?>
            </div>
        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="col-md-8">

        <!-- PAYMENT UI ONLY -->
        <div class="card mb-3">
            <div class="card-header fw-bold">Make Payment</div>
            <div class="card-body">

                <form>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>Payment Date</label>
                            <input type="date" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-6">
                            <label>Amount</label>
                            <input type="number" step="0.01" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-success btn-sm">
                            Pay Amortization
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- AMORTIZATION TABLE -->
        <div class="card mb-3">
            <div class="card-header fw-bold">Amortization Schedule</div>
            <div class="card-body table-responsive">

                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Date</th>
                            <th>Beginning</th>
                            <th>Interest</th>
                            <th>Principal</th>
                            <th>Payment</th>
                            <th>Ending</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                    $sched = $this->db->query("
                        SELECT * 
                        FROM tbl_loans_ammortization 
                        WHERE loan_id = '$loan_id'
                    ")->getResultArray();

                    foreach($sched as $row):
                    ?>
                        <tr>
                            <td><?= (int)$row['period']; ?></td>
                            <td><?= date('m/d/Y', strtotime($row['payment_date'])); ?></td>
                            <td><?= number_format((float)$row['beginning_balance'], 2); ?></td>
                            <td><?= number_format((float)$row['interest'], 2); ?></td>
                            <td><?= number_format((float)$row['principal'], 2); ?></td>
                            <td><?= number_format((float)$row['payment'], 2); ?></td>
                            <td><?= number_format((float)$row['ending_balance'], 2); ?></td>
                            <td>
                                <?php if(isset($row['payment_status']) && $row['payment_status'] === 'Paid'): ?>
                                    <span class="badge bg-success">Paid</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Unpaid</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>

            </div>
        </div>

        <!-- PAYMENT HISTORY UI -->
        <div class="card">
            <div class="card-header fw-bold">Payment History</div>
            <div class="card-body table-responsive">

                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Processed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                No payments yet
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

    </div>

</div>