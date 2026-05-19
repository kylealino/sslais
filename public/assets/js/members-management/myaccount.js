var __mysys_account_ent = new __mysys_account_ent();

function __mysys_account_ent() {  
    const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

    this.__account_saving = function() { 
        'use strict';
        
        var forms = document.querySelectorAll('.myaccount-validation');
        
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    return;
                }
                
                try {
                    event.preventDefault();
                    event.stopPropagation();

                    var member_id = document.getElementById("member_id");
                    var member_no = document.getElementById("member_no");
                    var last_name = document.getElementById("last_name");
                    var first_name = document.getElementById("first_name");
                    var middle_name = document.getElementById("middle_name");
                    var contact_number = document.getElementById("contact_number");
                    var address = document.getElementById("address");
                    var email = document.getElementById("email");
                    var username = document.getElementById("username");
                    var password = document.getElementById("password");
                    var newpassword = document.getElementById("newpassword");

                    var mparam = { 
                        member_id: member_id ? member_id.value : '',
                        member_no: member_no ? member_no.value : '',
                        last_name: last_name ? last_name.value : '',
                        first_name: first_name ? first_name.value : '',
                        middle_name: middle_name ? middle_name.value : '',
                        contact_number: contact_number ? contact_number.value : '',
                        address: address ? address.value : '',
                        email: email ? email.value : '',
                        username: username ? username.value : '',
                        password: password ? password.value : '',
                        newpassword: newpassword ? newpassword.value : '',
                        meaction: 'ACCOUNT-SAVE'
                    };

                    jQuery.ajax({
                        type: "POST",
                        url: mesiteurl + 'myaccount',
                        data: mparam,
                        dataType: 'json',
                        success: function(response) {
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
    __mysys_account_ent.__account_saving();
});