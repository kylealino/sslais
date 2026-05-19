var __mysys_loanprofile_ent = new __mysys_loanprofile_ent();

function __mysys_loanprofile_ent() {  
    const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

    this.__loanprofile_saving = function() { 
        'use strict';
        
        var forms = document.querySelectorAll('.myloanprofile-validation');
        
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                try {
                    event.preventDefault();
                    event.stopPropagation();

                    var loan_id = document.getElementById("loan_id");
                    var member_id = document.getElementById("member_id");
                    var interest = document.getElementById("interest");
                    var principal = document.getElementById("principal");
                    var total_payment = document.getElementById("total_payment");
                    var payment_date = document.getElementById("payment_date");
                    var ammortization_id = document.getElementById("ammortization_id");

                    if (!ammortization_id || !ammortization_id.value) {
                        toastr.error('Please select an amortization schedule to pay!');
                        return false;
                    }

                    var mparam = { 
                        loan_id: loan_id ? loan_id.value : '',
                        member_id: member_id ? member_id.value : '',
                        interest: interest ? interest.value : '',
                        principal: principal ? principal.value : '',
                        total_payment: total_payment ? total_payment.value : '',
                        payment_date: payment_date ? payment_date.value : '',
                        ammortization_id: ammortization_id ? ammortization_id.value : '',
                        meaction: 'LOAN-PAYMENT-SAVE'
                    };

                    jQuery.ajax({
                        type: "POST",
                        url: mesiteurl + 'myloanprofile',
                        data: mparam,
                        dataType: 'json',
                        success: function(response) {
                            if(response.status == 'success'){
                                toastr.success(response.message);
                                setTimeout(function() {
                                    window.location.href = mesiteurl + 'myloanprofile?meaction=MAIN&loan_id=' + response.loan_id;
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
}

$(document).ready(function() {
    __mysys_loanprofile_ent.__loanprofile_saving();
});