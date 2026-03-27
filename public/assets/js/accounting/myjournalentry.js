var __mysys_journal_ent = new __mysys_journal_ent();
function __mysys_journal_ent() {  
	const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

	this.my_add_journal_line = function () {
		try {
			var rowCount = jQuery('#journal_line_items tbody tr').length;
			var mid = generateRandomID(10) + (rowCount + 1);

			// Clone the last data row
			var clonedRow = jQuery('#journal_line_items tbody tr:eq(' + (rowCount - 1) + ')').clone();

			// Assign unique IDs and clear values
			jQuery(clonedRow).find('input[name="account_code"]').attr('id', 'account_code_' + mid).val('');
			jQuery(clonedRow).find('input[name="account_name"]').attr('id', 'account_name_' + mid).val('');
			jQuery(clonedRow).find('input[name="debit_amount"]').attr('id', 'debit_amount_' + mid).val('');
			jQuery(clonedRow).find('input[name="credit_amount"]').attr('id', 'credit_amount_' + mid).val('');
			jQuery(clonedRow).find('textarea[name="description"]').attr('id', 'description_' + mid).val('');
			jQuery(clonedRow).find('input[name="cost_center"]').attr('id', 'cost_center_' + mid).val('');

			// Insert the cloned row at the end
			jQuery('#journal_line_items tbody').append(clonedRow);
			clonedRow.css({ 'display': '' }).attr('id', 'tr_rec_' + mid);

			// Focus the first input field
			jQuery('#account_code_' + mid).focus();

		} catch (err) {
			alert('Error adding journal line: ' + err.message);
		}
	}

	this.my_add_journal_line_above = function (elem) {
		try {
			var rowCount = jQuery('#journal_line_items tbody tr').length;
			var mid = generateRandomID(10) + (rowCount + 1);

			// Clone the hidden template row
			var templateRow = jQuery('#journal_line_items tbody tr:hidden:first').clone();

			// Assign unique IDs and clear values
			jQuery(templateRow).find('input[name="account_code"]').attr('id', 'account_code_' + mid).val('');
			jQuery(templateRow).find('input[name="account_name"]').attr('id', 'account_name_' + mid).val('');
			jQuery(templateRow).find('input[name="debit_amount"]').attr('id', 'debit_amount_' + mid).val('');
			jQuery(templateRow).find('input[name="credit_amount"]').attr('id', 'credit_amount_' + mid).val('');
			jQuery(templateRow).find('textarea[name="description"]').attr('id', 'description_' + mid).val('');
			jQuery(templateRow).find('input[name="cost_center"]').attr('id', 'cost_center_' + mid).val('');

			// Insert above the clicked row
			var currentRow = jQuery(elem).closest('tr');
			templateRow.css('display', '').attr('id', 'tr_rec_' + mid);
			templateRow.insertBefore(currentRow);

			jQuery('#account_code_' + mid).focus();

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
		'use strict' 
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.myjournalentry-validation')
		// Loop over them and prevent submission
		Array.prototype.slice.call(forms)
		.forEach(function (form) {
			form.addEventListener('submit', function (event) {
				if (!form.checkValidity()) {
					event.preventDefault()
					event.stopPropagation()
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

					var rowcount1 = jQuery('.journaldata-list tr').length;
					var journaldtdata = [];
					var journaldata = '';

					for (var aa = 2; aa < rowcount1; aa++) {
						var clonedRow = jQuery('.journaldata-list tr:eq(' + aa + ')'); 
						var account_code = clonedRow.find('input.account_code').val();
						var account_name = clonedRow.find('input.account_name').val();
						var debit_amount = clonedRow.find('input.debit_amount').val();
						var credit_amount = clonedRow.find('input.credit_amount').val();
						var description = clonedRow.find('textarea.description').val();
						var cost_center = clonedRow.find('input.cost_center').val();
						
						journaldata = account_code + 'x|x' + account_name + 'x|x' + debit_amount + 'x|x' + credit_amount + 'x|x' + description + 'x|x' + cost_center;
						journaldtdata.push(journaldata);
					}

					var mparam = { 
						journal_id: journal_id.value,
						journal_no: journal_no.value,
						posting_date: posting_date.value,
						reference_no: reference_no.value,
						journal_type: journal_type.value,
						remarks: remarks.value,
						status: status.value,
						approved_by: approved_by.value,
						journaldtdata:journaldtdata,
						meaction: 'JOURNAL-ENTRY-SAVE'
					}

					jQuery.ajax({ // default declaration of ajax parameters
						type: "POST",
						url: mesiteurl + 'myjournalentry',
						context: document.body,
						data: eval(mparam),
						global: false,
						cache: false,
						success: function(data) { //display html using divID
							jQuery('.me-myjournalentry-outp-msg').html(data);
							return false;
						},
						error: function(xhr, status, error) { // display global error on the menu function
							//__mysys_apps.mybs_simple_toast('memsgtoastcont','metoastmsglang','align-items-center text-bg-danger border-0','Hello, Error Loading Page [TRXMGT-AP-ITEM-TAXDED-ENT]' + error);
							toastr.error('[myjournalentry-ENT', "Hello, Error Loading Page..." + error, {
							closeButton: true,
							});
							return false;
						} 
					}); 

					console.log("AJAX URL:", mesiteurl + 'myjournalentry');
				} catch(err) { 
					alert(err.message)
					return false;
				} //end try 
			}, false)
		}); //end forEach		
	}; //

	

}; //end main
