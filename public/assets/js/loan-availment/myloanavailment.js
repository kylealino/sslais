var __mysys_loanavailment_ent = new __mysys_loanavailment_ent();

function __mysys_loanavailment_ent() {  
    const mesiteurl = $('#__siteurl').attr('data-mesiteurl');
    let amortizationGenerated = false;

    this.__loanavailment_saving = function() { 
        'use strict';
        
        var forms = document.querySelectorAll('.myloanavailment-validation');
        
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                try {
                    event.preventDefault();
                    event.stopPropagation();

                    if (!amortizationGenerated) {
                        toastr.error('Please generate amortization schedule first!');
                        return false;
                    }

                    var loan_id = document.getElementById("loan_id");
                    var member_id = document.getElementById("member_id");
                    var loan_type = document.getElementById("loan_type");
                    var loan_amount = document.getElementById("loan_amount");
                    var interest_rate = document.getElementById("interest_rate");
                    var term_months = document.getElementById("term_months");
                    var start_date = document.getElementById("start_date");
                    var maturity_date = document.getElementById("maturity_date");
                    var loan_comakers = document.getElementById("loan_comakers");
                    var status = document.getElementById("status");

                    var rowcount = jQuery('.ammortization-list tbody tr').length;
                    var ammortizationdata = [];

                    for (var aa = 0; aa < rowcount; aa++) {
                        var clonedRow = jQuery('.ammortization-list tbody tr:eq(' + aa + ')'); 
                        var period = clonedRow.find('td').eq(0).text();
                        var payment_date = clonedRow.find('td').eq(1).text();
                        var beginning_balance = clonedRow.find('td').eq(2).text();
                        var interest = clonedRow.find('td').eq(3).text();
                        var principal = clonedRow.find('td').eq(4).text();
                        var payment = clonedRow.find('td').eq(5).text();
                        var ending_balance = clonedRow.find('td').eq(6).text();

                        var monthlydata = period + 'x|x' + payment_date + 'x|x' + beginning_balance + 'x|x' + interest + 'x|x' + principal + 'x|x' + payment + 'x|x' + ending_balance;
                        ammortizationdata.push(monthlydata);
                    }

                    var mparam = { 
                        loan_id: loan_id ? loan_id.value : '',
                        member_id: member_id ? member_id.value : '',
                        loan_type: loan_type ? loan_type.value : '',
                        loan_amount: loan_amount ? loan_amount.value : '',
                        interest_rate: interest_rate ? interest_rate.value : '',
                        term_months: term_months ? term_months.value : '',
                        start_date: start_date ? start_date.value : '',
                        maturity_date: maturity_date ? maturity_date.value : '',
                        loan_comakers: loan_comakers ? loan_comakers.value : '',
                        status: status ? status.value : '',
                        ammortizationdata: ammortizationdata,
                        meaction: 'LOAN-AVAILMENT-SAVE'
                    };

                    jQuery.ajax({
                        type: "POST",
                        url: mesiteurl + 'myloanavailment',
                        data: mparam,
                        dataType: 'json',
                        success: function(response) {
                            if(response.status == 'success'){
                                toastr.success(response.message);
                                setTimeout(function() {
                                    window.location.href = mesiteurl + 'myloanavailment?meaction=MAIN';
                                }, 1500);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            toastr.error("Error: " + error);
                        }
                    });

                } catch(err) { 
                    alert(err.message);
                    return false;
                }
            }, false);
        });
    };

    this.generateAmortization = function() {
        let loanAmount = parseFloat(document.querySelector('input[name="loan_amount"]').value);
        let annualRate = parseFloat(document.querySelector('input[name="interest_rate"]').value);
        let termMonths = parseInt(document.querySelector('select[name="term_months"]').value);
        let startDate = new Date(document.querySelector('input[name="start_date"]').value);

        if (!loanAmount || !annualRate || !termMonths || isNaN(startDate.getTime())) {
            toastr.error('Please fill all loan details first.');
            return;
        }

        // Calculate maturity date
        let maturityDate = new Date(startDate);
        maturityDate.setMonth(maturityDate.getMonth() + termMonths);
        let formattedMaturity = maturityDate.toISOString().split('T')[0];
        document.getElementById('maturity_date').value = formattedMaturity;

        // Calculate monthly payment
        let monthlyRate = annualRate / 12 / 100;
        let payment = loanAmount * monthlyRate / (1 - Math.pow(1 + monthlyRate, -termMonths));

        let balance = loanAmount;
        let currentDate = new Date(startDate);

        // Clear previous table rows
        let tbody = document.querySelector('#amortizationTable tbody');
        tbody.innerHTML = '';

        // Generate rows
        for (let period = 1; period <= termMonths; period++) {
            let interest = balance * monthlyRate;
            let principal = payment - interest;
            let endingBalance = balance - principal;
            let displayEndingBalance = (Math.abs(endingBalance) < 0.005) ? 0 : endingBalance;

            let row = `<tr>
                <td class="text-center">${period}</td>
                <td>${currentDate.toISOString().split('T')[0]}</td>
                <td class="text-end">${balance.toFixed(2)}</td>
                <td class="text-end">${interest.toFixed(2)}</td>
                <td class="text-end">${principal.toFixed(2)}</td>
                <td class="text-end">${payment.toFixed(2)}</td>
                <td class="text-end">${displayEndingBalance.toFixed(2)}</td>
            </tr>`;

            tbody.insertAdjacentHTML('beforeend', row);
            balance = endingBalance;
            currentDate.setMonth(currentDate.getMonth() + 1);
        }

        amortizationGenerated = true;
        toastr.success('Amortization schedule generated successfully!');
    };
}

$(document).ready(function() {
    __mysys_loanavailment_ent.__loanavailment_saving();
    
    // Generate button click
    $('#generateAmortization').on('click', function() {
        __mysys_loanavailment_ent.generateAmortization();
    });
});