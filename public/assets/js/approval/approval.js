var __mysys_approval_ent = new __mysys_approval_ent();

function __mysys_approval_ent() {  
    const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

    this.__handle_approval_action = function(formId, action) {
        $(formId).on('submit', function(e) {
            e.preventDefault();
            
            var formData = $(this).serializeArray();
            formData.push({name: 'meaction', value: action});
            
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

$(document).ready(function() {
    // Submit for approval
    __mysys_approval_ent.__handle_approval_action('#submitApprovalForm', 'SUBMIT-APPROVAL');
    
    // Review loan
    __mysys_approval_ent.__handle_approval_action('#reviewApprovalForm', 'REVIEW-LOAN');
    
    // Approve loan
    __mysys_approval_ent.__handle_approval_action('#approveLoanForm', 'APPROVE-LOAN');
    
    // Decline loan
    __mysys_approval_ent.__handle_approval_action('#declineLoanForm', 'DECLINE-LOAN');
    
    // Request revision
    __mysys_approval_ent.__handle_approval_action('#reviseLoanForm', 'REVISE-LOAN');
});