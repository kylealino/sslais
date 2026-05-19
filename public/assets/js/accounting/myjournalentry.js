var __mysys_journal_ent = new __mysys_journal_ent();

function __mysys_journal_ent() {  
    const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

    this.my_add_journal_line = function () {
        try {
            var rowCount = jQuery('#journal_line_items tbody tr:visible').length;
            var mid = generateRandomID(10) + (rowCount + 1);

            // Get the hidden template row
            var templateRow = jQuery('#journal_line_items tbody tr:hidden:first').clone();

            // Clear values
            templateRow.find('input[name="account_code"]').val('');
            templateRow.find('input[name="account_name"]').val('');
            templateRow.find('input[name="debit_amount"]').val('');
            templateRow.find('input[name="credit_amount"]').val('');
            templateRow.find('textarea[name="description"]').val('');
            templateRow.find('input[name="cost_center"]').val('');

            // Add unique ID to the row
            var rowId = 'tr_rec_' + mid;
            templateRow.attr('id', rowId);
            
            // Show the row
            templateRow.css('display', '');
            
            // Append to tbody
            jQuery('#journal_line_items tbody').append(templateRow);
            
            // Initialize autocomplete for the new row
            if (typeof initAccountAutocomplete === 'function') {
                initAccountAutocomplete(templateRow.find('.account_code'));
            }
            
            // Focus the first input field
            templateRow.find('.account_code').focus();

        } catch (err) {
            alert('Error adding journal line: ' + err.message);
        }
    }

    this.my_add_journal_line_above = function (elem) {
        try {
            var rowCount = jQuery('#journal_line_items tbody tr:visible').length;
            var mid = generateRandomID(10) + (rowCount + 1);

            var templateRow = jQuery('#journal_line_items tbody tr:hidden:first').clone();

            templateRow.find('input[name="account_code"]').val('');
            templateRow.find('input[name="account_name"]').val('');
            templateRow.find('input[name="debit_amount"]').val('');
            templateRow.find('input[name="credit_amount"]').val('');
            templateRow.find('textarea[name="description"]').val('');
            templateRow.find('input[name="cost_center"]').val('');

            var rowId = 'tr_rec_' + mid;
            templateRow.attr('id', rowId);
            templateRow.css('display', '');

            var currentRow = jQuery(elem).closest('tr');
            templateRow.insertAfter(currentRow);

            if (typeof initAccountAutocomplete === 'function') {
                initAccountAutocomplete(templateRow.find('.account_code'));
            }
            templateRow.find('.account_code').focus();

        } catch (err) {
            alert('Error adding journal line above: ' + err.message);
        }
    }

    function generateRandomID(length) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }

    this.__journalentry_saving = function() { 
        'use strict';
        
        var forms = document.querySelectorAll('.myjournalentry-validation');
        
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                try {
                    event.preventDefault();
                    event.stopPropagation();

                    var journal_id = document.getElementById("journal_id");
                    var journal_no = document.getElementById("journal_no");
                    var posting_date = document.getElementById("posting_date");
                    var reference_no = document.getElementById("reference_no");
                    var journal_type = document.getElementById("journal_type");
                    var remarks = document.getElementById("remarks");
                    var status = document.getElementById("status");
                    var approved_by = document.getElementById("approved_by");

                    // Collect journal lines - ONLY from visible rows that are NOT the hidden template
                    var journaldtdata = [];
                    
                    jQuery('#journal_line_items tbody tr:visible').each(function() {
                        var $row = $(this);
                        // Skip if this is the hidden template row (it shouldn't be visible, but just in case)
                        if ($row.css('display') === 'none') return;
                        
                        var account_code = $row.find('input[name="account_code"]').val();
                        
                        // Only add if account_code has a value
                        if (account_code && account_code.trim() !== '') {
                            var account_name = $row.find('input[name="account_name"]').val() || '';
                            var debit_amount = $row.find('input[name="debit_amount"]').val() || 0;
                            var credit_amount = $row.find('input[name="credit_amount"]').val() || 0;
                            var description = $row.find('textarea[name="description"]').val() || '';
                            var cost_center = $row.find('input[name="cost_center"]').val() || '';
                            
                            var journaldata = account_code + 'x|x' + account_name + 'x|x' + debit_amount + 'x|x' + credit_amount + 'x|x' + description + 'x|x' + cost_center;
                            journaldtdata.push(journaldata);
                        }
                    });

                    console.log('Collected ' + journaldtdata.length + ' journal lines');

                    if (journaldtdata.length === 0) {
                        toastr.error('At least one journal entry line is required!');
                        return false;
                    }

                    var mparam = { 
                        journal_id: journal_id ? journal_id.value : '',
                        journal_no: journal_no ? journal_no.value : '',
                        posting_date: posting_date ? posting_date.value : '',
                        reference_no: reference_no ? reference_no.value : '',
                        journal_type: journal_type ? journal_type.value : '',
                        remarks: remarks ? remarks.value : '',
                        status: status ? status.value : '',
                        approved_by: approved_by ? approved_by.value : '',
                        journaldtdata: journaldtdata,
                        meaction: 'JOURNAL-ENTRY-SAVE'
                    }

                    jQuery.ajax({
                        type: "POST",
                        url: mesiteurl + 'myjournalentry',
                        data: mparam,
                        dataType: 'json',
                        success: function(response) {
                            if(response.status == 'success'){
                                toastr.success(response.message);
                                setTimeout(function() {
                                    window.location.href = mesiteurl + 'myjournalentry?meaction=MAIN';
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
    __mysys_journal_ent.__journalentry_saving();
    
    // Initialize autocomplete for existing rows
    $('.account_code').each(function() {
        var $this = $(this);
        if (!$this.data("ui-autocomplete")) {
            $this.autocomplete({
                source: accountList,
                minLength: 0,
                select: function (event, ui) {
                    let row = $(this).closest('tr');
                    $(this).val(ui.item.value);
                    row.find('.account_name').val(ui.item.account_name);
                    return false;
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>")
                    .append("<div class='ui-menu-item-wrapper'>" + item.label + "</div>")
                    .appendTo(ul);
            };
        }
    });
});

