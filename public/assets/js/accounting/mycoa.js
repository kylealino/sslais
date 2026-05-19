var __mysys_coa_ent = new __mysys_coa_ent();

function __mysys_coa_ent() {  
    const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

    this.__coa_saving = function() { 
        'use strict';
        
        var forms = document.querySelectorAll('.mycoa-validation');
        
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                try {
                    event.preventDefault();
                    event.stopPropagation();

                    var account_id = document.getElementById("account_id");
                    var account_code = document.getElementById("account_code");
                    var account_name = document.getElementById("account_name");
                    var account_type = document.getElementById("account_type");
                    var parent_code = document.getElementById("parent_code");
                    var is_active = document.getElementById("is_active");

                    var mparam = { 
                        account_id: account_id ? account_id.value : '',
                        account_code: account_code ? account_code.value : '',
                        account_name: account_name ? account_name.value : '',
                        account_type: account_type ? account_type.value : '',
                        parent_code: parent_code ? parent_code.value : '',
                        is_active: is_active ? is_active.value : '',
                        meaction: 'COA-SAVE'
                    }

                    jQuery.ajax({
                        type: "POST",
                        url: mesiteurl + 'mycoa',
                        data: mparam,
                        dataType: 'json',
                        success: function(response) {
                            if(response.status == 'success'){
                                toastr.success(response.message);
                                setTimeout(function() {
                                    window.location.href = mesiteurl + 'mycoa?meaction=MAIN';
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
    __mysys_coa_ent.__coa_saving();
});