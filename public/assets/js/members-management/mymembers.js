var __mysys_members_ent = new __mysys_members_ent();

function __mysys_members_ent() {  
    const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

    this.__members_saving = function() { 
        'use strict';
        
        var forms = document.querySelectorAll('.mymembers-validation');
        
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                try {
                    event.preventDefault();
                    event.stopPropagation();

                    // Basic Member Information
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
                    
                    // I. Member Information - New Fields
                    var date_of_birth = document.getElementById("date_of_birth");
                    var place_of_birth = document.getElementById("place_of_birth");
                    var age = document.getElementById("age");
                    var civil_status = document.getElementById("civil_status");
                    var gender = document.getElementById("gender");
                    var tin = document.getElementById("tin");
                    var gsis_number = document.getElementById("gsis_number");
                    
                    // II. Contact Information - Permanent Address
                    var permanent_street = document.querySelector("input[name='permanent_street']");
                    var permanent_barangay = document.querySelector("input[name='permanent_barangay']");
                    var permanent_city = document.querySelector("input[name='permanent_city']");
                    var permanent_province = document.querySelector("input[name='permanent_province']");
                    var permanent_zip = document.querySelector("input[name='permanent_zip']");
                    
                    // II. Contact Information - Present Address
                    var present_street = document.querySelector("input[name='present_street']");
                    var present_barangay = document.querySelector("input[name='present_barangay']");
                    var present_city = document.querySelector("input[name='present_city']");
                    var present_province = document.querySelector("input[name='present_province']");
                    var present_zip = document.querySelector("input[name='present_zip']");
                    
                    // II. Contact Information - Additional Phone Numbers
                    var home_phone = document.querySelector("input[name='home_phone']");
                    var office_phone = document.querySelector("input[name='office_phone']");
                    
                    // III. Employment Information
                    var department_agency = document.querySelector("select[name='department_agency']");
                    var position = document.querySelector("input[name='position']");
                    var salary_grade = document.querySelector("input[name='salary_grade']");
                    
                    // IV. Beneficiaries
                    var beneficiary1_name = document.querySelector("input[name='beneficiary1_name']");
                    var beneficiary1_address = document.querySelector("input[name='beneficiary1_address']");
                    var beneficiary1_contact = document.querySelector("input[name='beneficiary1_contact']");
                    var beneficiary1_relationship = document.querySelector("input[name='beneficiary1_relationship']");
                    
                    var beneficiary2_name = document.querySelector("input[name='beneficiary2_name']");
                    var beneficiary2_address = document.querySelector("input[name='beneficiary2_address']");
                    var beneficiary2_contact = document.querySelector("input[name='beneficiary2_contact']");
                    var beneficiary2_relationship = document.querySelector("input[name='beneficiary2_relationship']");

                    var mparam = { 
                        // Basic Information
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
                        
                        // I. Member Information
                        date_of_birth: date_of_birth ? date_of_birth.value : '',
                        place_of_birth: place_of_birth ? place_of_birth.value : '',
                        age: age ? age.value : '',
                        civil_status: civil_status ? civil_status.value : '',
                        gender: gender ? gender.value : '',
                        tin: tin ? tin.value : '',
                        gsis_number: gsis_number ? gsis_number.value : '',
                        
                        // II. Contact Information - Permanent Address
                        permanent_street: permanent_street ? permanent_street.value : '',
                        permanent_barangay: permanent_barangay ? permanent_barangay.value : '',
                        permanent_city: permanent_city ? permanent_city.value : '',
                        permanent_province: permanent_province ? permanent_province.value : '',
                        permanent_zip: permanent_zip ? permanent_zip.value : '',
                        
                        // II. Contact Information - Present Address
                        present_street: present_street ? present_street.value : '',
                        present_barangay: present_barangay ? present_barangay.value : '',
                        present_city: present_city ? present_city.value : '',
                        present_province: present_province ? present_province.value : '',
                        present_zip: present_zip ? present_zip.value : '',
                        
                        // II. Contact Information - Additional Phone Numbers
                        home_phone: home_phone ? home_phone.value : '',
                        office_phone: office_phone ? office_phone.value : '',
                        
                        // III. Employment Information
                        department_agency: department_agency ? department_agency.value : '',
                        position: position ? position.value : '',
                        salary_grade: salary_grade ? salary_grade.value : '',
                        
                        // IV. Beneficiaries
                        beneficiary1_name: beneficiary1_name ? beneficiary1_name.value : '',
                        beneficiary1_address: beneficiary1_address ? beneficiary1_address.value : '',
                        beneficiary1_contact: beneficiary1_contact ? beneficiary1_contact.value : '',
                        beneficiary1_relationship: beneficiary1_relationship ? beneficiary1_relationship.value : '',
                        
                        beneficiary2_name: beneficiary2_name ? beneficiary2_name.value : '',
                        beneficiary2_address: beneficiary2_address ? beneficiary2_address.value : '',
                        beneficiary2_contact: beneficiary2_contact ? beneficiary2_contact.value : '',
                        beneficiary2_relationship: beneficiary2_relationship ? beneficiary2_relationship.value : '',
                        
                        meaction: 'MEMBERS-SAVE'
                    };

                    jQuery.ajax({
                        type: "POST",
                        url: mesiteurl + 'mymembers',
                        data: mparam,
                        dataType: 'json',
                        success: function(response) {
                            if(response.status == 'success'){
                                toastr.success(response.message);
                                setTimeout(function() {
                                    window.location.href = mesiteurl + 'mymembers?meaction=MAIN';
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

    this.__showPdfInModal = function(pdfUrl) {
        var pdfFrame = document.getElementById("pdfFrame");
        var pdfModal = new bootstrap.Modal(document.getElementById("pdfModal"));

        document.getElementById('pdfModalLabel').innerText = 'SSLAI Membership Profile Update Form';
        pdfFrame.src = pdfUrl;
        pdfModal.show();
    };
}

$(document).ready(function() {
    __mysys_members_ent.__members_saving();
});