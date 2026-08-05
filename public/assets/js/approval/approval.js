/**
 * SSLAI Approval Management System
 * Handles all approval workflows, risk assessment calculations,
 * credit assessment calculations, and AJAX form submissions
 * 
 * @version 3.0
 */

// ============================================
// MAIN APPROVAL HANDLER
// ============================================
var __mysys_approval_ent = new __mysys_approval_ent();

function __mysys_approval_ent() {  
    const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

    /**
     * Handle approval form submissions via AJAX
     * @param {string} formId - jQuery selector for the form
     * @param {string} action - The action to perform (meaction value)
     */
    this.__handle_approval_action = function(formId, action) {
        $(formId).on('submit', function(e) {
            e.preventDefault();
            
            var formData = $(this).serializeArray();
            formData.push({name: 'meaction', value: action});
            
            // Show loading overlay
            $('#uploadOverlay').addClass('active');
            
            $.ajax({
                type: "POST",
                url: mesiteurl + 'myapprovals',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('#uploadOverlay').removeClass('active');
                    if(response.status == 'success'){
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    $('#uploadOverlay').removeClass('active');
                    toastr.error("Error: " + error);
                }
            });
        });
    };
}

// ============================================
// RISK ASSESSMENT CALCULATIONS
// ============================================

/**
 * Calculate Numerical Rating for Leave Credits
 * Criteria: <=20=5, 21-30=4, 31-40=3, 41-50=2, >50=1
 */
function calculateLeaveCreditsRating(value) {
    if(value <= 20) return 5;
    if(value <= 30) return 4;
    if(value <= 40) return 3;
    if(value <= 50) return 2;
    return 1;
}

/**
 * Calculate Numerical Rating for Capital Contribution
 * Criteria: <=20000=5, 20001-30000=4, 30001-40000=3, 40001-50000=2, >50000=1
 */
function calculateCapitalContributionRating(value) {
    if(value <= 20000) return 5;
    if(value <= 30000) return 4;
    if(value <= 40000) return 3;
    if(value <= 50000) return 2;
    return 1;
}

/**
 * Calculate Numerical Rating for Take Home Pay
 * Criteria: <=5000=5, 5001-7500=4, 7501-10000=3, 10001-12500=2, >12500=1
 */
function calculateTakeHomePayRating(value) {
    if(value <= 5000) return 5;
    if(value <= 7500) return 4;
    if(value <= 10000) return 3;
    if(value <= 12500) return 2;
    return 1;
}

/**
 * Calculate Numerical Rating for Existing SSLAI Loans
 * Criteria: >50000=5, 35001-50000=4, 25001-35000=3, 15001-25000=2, 1-15000=1, 0=0
 */
function calculateExistingLoansRating(value) {
    if(value > 50000) return 5;
    if(value > 35000) return 4;
    if(value > 25000) return 3;
    if(value > 15000) return 2;
    if(value > 0) return 1;
    return 0;
}

/**
 * Calculate Numerical Rating for Years in Service
 * Criteria: <3=5, 4-6=4, 6-8=3, 8-10=2, >10=1
 */
function calculateYearsInServiceRating(value) {
    if(value < 3) return 5;
    if(value <= 6) return 4;
    if(value <= 8) return 3;
    if(value <= 10) return 2;
    return 1;
}

/**
 * Calculate Numerical Rating for Other Loans
 * Criteria: >50000=5, 35001-50000=4, 25001-35000=3, 15001-25000=2, 1-15000=1, 0=0
 */
function calculateOtherLoansRating(value) {
    if(value > 50000) return 5;
    if(value > 35000) return 4;
    if(value > 25000) return 3;
    if(value > 15000) return 2;
    if(value > 0) return 1;
    return 0;
}

/**
 * Calculate Numerical Rating for Health Condition
 * Criteria: severe=3, mild=2, good=1
 */
function calculateHealthRating(value) {
    if(value == 'severe') return 3;
    if(value == 'mild') return 2;
    return 1;
}

/**
 * Calculate Numerical Rating for Status/Dependents
 * Criteria: >3 dependents=3, 1-3 dependents=2, 0 dependents=1
 */
function calculateStatusRating(dependents) {
    if(dependents > 3) return 3;
    if(dependents >= 1) return 2;
    return 1;
}

/**
 * Calculate Numerical Rating for Age
 * Criteria: >=55=5, 50-54=4, 41-49=3, 31-40=2, 20-30=1
 */
function calculateAgeRating(value) {
    if(value >= 55) return 5;
    if(value >= 50) return 4;
    if(value >= 41) return 3;
    if(value >= 31) return 2;
    return 1;
}

/**
 * Calculate Numerical Rating for Credit Standing
 * Criteria: >4 months=5, 4 months=4, 3 months=3, 2 months=2, 1 month=1, 0=0
 */
function calculateCreditStandingRating(months) {
    if(months > 4) return 5;
    if(months == 4) return 4;
    if(months == 3) return 3;
    if(months == 2) return 2;
    if(months == 1) return 1;
    return 0;
}

/**
 * Get Descriptive Risk Rating based on total score
 * Criteria: >35=High Risk, 26-35=Cautionary, 16-25=Moderate Risk, 0-15=Low Risk
 */
function getDescriptiveRating(score) {
    if(score > 35) {
        return { text: 'High Risk', cssClass: 'high-risk' };
    } else if(score >= 26 && score <= 35) {
        return { text: 'Cautionary', cssClass: 'cautionary' };
    } else if(score >= 16 && score <= 25) {
        return { text: 'Moderate Risk', cssClass: 'moderate-risk' };
    } else {
        return { text: 'Low Risk', cssClass: 'low-risk' };
    }
}

/**
 * Get Qualitative Risk Rating based on total score
 * Criteria: >35=High, 26-35=Medium, 16-25=Medium, 0-15=Low
 */
function getQualitativeRating(score) {
    if(score > 35) {
        return { text: 'High', cssClass: 'high-risk' };
    } else if(score >= 16 && score <= 35) {
        return { text: 'Medium', cssClass: 'cautionary' };
    } else {
        return { text: 'Low', cssClass: 'low-risk' };
    }
}

/**
 * Get CSS class for numerical rating display
 */
function getNumRatingClass(rating) {
    if(rating >= 4) return 'high';
    if(rating >= 2) return 'medium';
    if(rating == 0) return 'zero';
    return 'low';
}

/**
 * Calculate all risk ratings and update the form
 * Quantitative Rating = User Input (value entered)
 * Numerical Rating = Auto-Calculated (1-5 based on criteria)
 */
function calculateRiskRatings() {
    // Get quantitative values from user inputs
    var values = {
        leave_credits: parseFloat($('input[name="leave_credits_quant"]').val()) || 0,
        capital_contribution: parseFloat($('input[name="capital_contribution_quant"]').val()) || 0,
        take_home_pay: parseFloat($('input[name="take_home_pay_quant"]').val()) || 0,
        existing_sslai_loans: parseFloat($('input[name="existing_sslai_loans_quant"]').val()) || 0,
        years_in_service: parseFloat($('input[name="years_in_service_quant"]').val()) || 0,
        other_loans: parseFloat($('input[name="other_loans_quant"]').val()) || 0
    };
    
    // Calculate numerical ratings (auto-calculated)
    var numRatings = {
        leave_credits: calculateLeaveCreditsRating(values.leave_credits),
        capital_contribution: calculateCapitalContributionRating(values.capital_contribution),
        take_home_pay: calculateTakeHomePayRating(values.take_home_pay),
        existing_sslai_loans: calculateExistingLoansRating(values.existing_sslai_loans),
        years_in_service: calculateYearsInServiceRating(values.years_in_service),
        other_loans: calculateOtherLoansRating(values.other_loans)
    };
    
    // Update Numerical Rating displays
    updateNumRatingDisplay('leave_credits_num', numRatings.leave_credits);
    updateNumRatingDisplay('capital_contribution_num', numRatings.capital_contribution);
    updateNumRatingDisplay('take_home_pay_num', numRatings.take_home_pay);
    updateNumRatingDisplay('existing_sslai_loans_num', numRatings.existing_sslai_loans);
    updateNumRatingDisplay('years_in_service_num', numRatings.years_in_service);
    updateNumRatingDisplay('other_loans_num', numRatings.other_loans);
    
    // Get other factors values
    var health = $('select[name="health_condition"]').val();
    var dependents = parseInt($('input[name="dependents"]').val()) || 0;
    var age = parseInt($('input[name="age"]').val()) || 0;
    var delinquency = parseInt($('input[name="delinquency_months"]').val()) || 0;
    
    // Calculate other factors numerical ratings
    var healthRating = calculateHealthRating(health);
    var statusRating = calculateStatusRating(dependents);
    var ageRating = calculateAgeRating(age);
    var creditRating = calculateCreditStandingRating(delinquency);
    
    // Calculate total numerical score
    var totalScore = numRatings.leave_credits + numRatings.capital_contribution + numRatings.take_home_pay +
                     numRatings.existing_sslai_loans + numRatings.years_in_service + numRatings.other_loans +
                     healthRating + statusRating + ageRating + creditRating;
    
    // Get descriptive and qualitative ratings
    var descriptive = getDescriptiveRating(totalScore);
    var qualitative = getQualitativeRating(totalScore);
    
    // Update results
    var totalScoreEl = document.getElementById('total_score');
    if (totalScoreEl) {
        totalScoreEl.textContent = totalScore;
        totalScoreEl.className = 'result-value ' + descriptive.cssClass;
    }
    
    var qualitativeEl = document.getElementById('qualitative_rating');
    if (qualitativeEl) {
        qualitativeEl.textContent = qualitative.text;
        qualitativeEl.className = 'risk-badge ' + qualitative.cssClass;
    }
    
    var descriptiveEl = document.getElementById('descriptive_rating');
    if (descriptiveEl) {
        descriptiveEl.textContent = descriptive.text;
        descriptiveEl.className = 'risk-badge ' + descriptive.cssClass;
    }
}

/**
 * Update numerical rating display with appropriate styling
 */
function updateNumRatingDisplay(elementId, rating) {
    var el = document.getElementById(elementId);
    if (!el) return;
    
    el.textContent = rating;
    el.className = 'num-rating-display ' + getNumRatingClass(rating);
}

// ============================================
// CREDIT ASSESSMENT CALCULATIONS
// ============================================

/**
 * Calculate total loan balance from all balance inputs
 */
function calculateTotalLoanBalance() {
    var total = 0;
    $('.credit-balance').each(function() {
        var val = parseFloat($(this).val()) || 0;
        total += val;
    });
    $('#total_loan_balance').val(total.toFixed(2));
}

/**
 * Calculate total loanable amount
 */
function calculateTotalLoanable() {
    var loanable = parseFloat($('#loanable_amount').val()) || 0;
    var capcon = parseFloat($('input[name="withdrawable_capcon"]').val()) || 0;
    $('#total_loanable_amount').val((loanable + capcon).toFixed(2));
}

// ============================================
// DOCUMENT READY - INITIALIZE
// ============================================

$(document).ready(function() {
    // ============================================
    // DATATABLE INITIALIZATION
    // ============================================
    if ($('#approvalTable').length && !$.fn.DataTable.isDataTable('#approvalTable')) {
        $('#approvalTable').DataTable({
            pageLength: 10,
            lengthChange: true,
            order: [[0, 'desc']],
            language: { 
                search: "Search:",
                info: "Showing _START_ to _END_ of _TOTAL_ applications",
                infoEmpty: "No applications found",
                lengthMenu: "Show _MENU_ applications"
            },
            dom: 'frtip'
        });
    }
    
    // ============================================
    // STAT CARDS CLICK HANDLERS
    // ============================================
    $('.stat-card').on('click', function() {
        var status = $(this).data('status');
        filterTable(status);
        $('.stat-card').removeClass('active-filter');
        $(this).addClass('active-filter');
    });
    
    $('.stat-card').on('dblclick', function() {
        filterTable('All');
        $('.stat-card').removeClass('active-filter');
    });

    // ============================================
    // RISK ASSESSMENT - AUTO CALCULATION
    // ============================================
    // Trigger calculation on input change (Quantitative Rating inputs)
    $('.quant-input, .other-factor').on('change keyup', function() {
        calculateRiskRatings();
    });
    
    // Also trigger on select change
    $('select[name="health_condition"]').on('change', function() {
        calculateRiskRatings();
    });
    
    // Initial calculation if there are values
    if ($('.quant-input').length > 0) {
        calculateRiskRatings();
    }

    // ============================================
    // RISK COMMITTEE CHECKBOX TRACKING
    // ============================================
    $('.committee-check').on('change', function() {
        var total = 4;
        var checked = $('.committee-check:checked').length;
        $('#selectedCount').text(checked);
        var percentage = (checked / total) * 100;
        $('#committeeProgress').css('width', percentage + '%');
        
        // Update button text based on selection
        if (checked == total) {
            $('#submitRiskBtn').html('<i class="ti ti-save me-1"></i> Submit Risk Assessment (All Members Approved)');
            $('#submitRiskBtn').removeClass('btn-secondary').addClass('btn-review');
            $('#submitRiskBtn').prop('disabled', false);
        } else if (checked > 0) {
            $('#submitRiskBtn').html('<i class="ti ti-save me-1"></i> Submit Risk Assessment (' + checked + ' of ' + total + ' members selected)');
            $('#submitRiskBtn').removeClass('btn-secondary').addClass('btn-review');
            $('#submitRiskBtn').prop('disabled', false);
        } else {
            $('#submitRiskBtn').html('<i class="ti ti-save me-1"></i> Select Committee Members First');
            $('#submitRiskBtn').addClass('btn-secondary').removeClass('btn-review');
            $('#submitRiskBtn').prop('disabled', true);
        }
    });
    
    // ============================================
    // CREDIT COMMITTEE CHECKBOX TRACKING
    // ============================================
    // ============================================
    // CREDIT COMMITTEE CHECKBOX TRACKING
    // ============================================
    $('.credit-committee-check').on('change', function() {
        var total = 5;
        var checked = $('.credit-committee-check:checked').length;
        $('#creditSelectedCount').text(checked);
        var percentage = (checked / total) * 100;
        $('#creditCommitteeProgress').css('width', percentage + '%');
        
        // Get the count of each member for debugging
        var sharra = $('#committee_sharra_credit').is(':checked') ? 1 : 0;
        var roseann = $('#committee_roseann').is(':checked') ? 1 : 0;
        var gerry = $('#committee_gerry_credit').is(':checked') ? 1 : 0;
        var michael = $('#committee_michael_credit').is(':checked') ? 1 : 0;
        var jovelyn = $('#committee_jovelyn').is(':checked') ? 1 : 0;
        var totalChecked = sharra + roseann + gerry + michael + jovelyn;
        
        // Update button text based on selection
        if (totalChecked == total) {
            $('#submitCreditBtn').html('<i class="ti ti-save me-1"></i> Submit Credit Assessment (All Members Approved)');
            $('#submitCreditBtn').removeClass('btn-secondary').addClass('btn-review');
            $('#submitCreditBtn').prop('disabled', false);
        } else if (totalChecked > 0) {
            $('#submitCreditBtn').html('<i class="ti ti-save me-1"></i> Submit Credit Assessment (' + totalChecked + ' of ' + total + ' members selected)');
            $('#submitCreditBtn').removeClass('btn-secondary').addClass('btn-review');
            $('#submitCreditBtn').prop('disabled', false);
        } else {
            $('#submitCreditBtn').html('<i class="ti ti-save me-1"></i> Select Committee Members First');
            $('#submitCreditBtn').addClass('btn-secondary').removeClass('btn-review');
            $('#submitCreditBtn').prop('disabled', true);
        }
    });
    
    // ============================================
    // CREDIT ASSESSMENT - AUTO CALCULATION
    // ============================================
    // Calculate total loan balance on any balance input change
    $('.credit-balance').on('input', function() {
        calculateTotalLoanBalance();
    });
    
    // Calculate total loanable amount on input change
    $('#loanable_amount, input[name="withdrawable_capcon"]').on('input', function() {
        calculateTotalLoanable();
    });

    // ============================================
    // FORM SUBMISSIONS - APPROVAL ACTIONS
    // ============================================
    // Submit for approval (Pending -> Risk Assessment)
    __mysys_approval_ent.__handle_approval_action('#submitApprovalForm', 'SUBMIT-APPROVAL');
    
    // Risk Assessment (Risk Assessment -> Credit Assessment)
    __mysys_approval_ent.__handle_approval_action('#riskAssessmentForm', 'SAVE-RISK-ASSESSMENT');
    
    // Credit Assessment (Credit Assessment -> Decision)
    __mysys_approval_ent.__handle_approval_action('#creditAssessmentForm', 'SAVE-CREDIT-ASSESSMENT');
    
    // Approve loan (Decision -> Approved)
    __mysys_approval_ent.__handle_approval_action('#approveLoanForm', 'APPROVE-LOAN');
    
    // Decline loan (Decision -> Declined)
    __mysys_approval_ent.__handle_approval_action('#declineLoanForm', 'DECLINE-LOAN');
    
    // Request revision (Decision -> Pending)
    __mysys_approval_ent.__handle_approval_action('#reviseLoanForm', 'REVISE-LOAN');
});

// ============================================
// TABLE FILTER FUNCTION
// ============================================

/**
 * Filter the DataTable by status/stage
 * @param {string} status - The status to filter by
 */
function filterTable(status) {
    var table = $('#approvalTable').DataTable();
    if (status !== 'All') {
        table.column(5).search(status).draw();
        $('#filterStatus').text('(' + status + ')');
    } else {
        table.column(5).search('').draw();
        $('#filterStatus').text('(All)');
    }
}