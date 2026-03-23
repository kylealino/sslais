var __mysys_loanavailment_ent = new __mysys_loanavailment_ent();
function __mysys_loanavailment_ent() {  
	const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

	this.__loanavailment_saving = function() { 
		'use strict' 
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.myloanavailment-validation')
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

					for (var aa = 0; aa < rowcount; aa++) { // start from 0 to include all rows
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

					console.log(ammortizationdata);

					var mparam = { 
						loan_id: loan_id.value,
						member_id: member_id.value,
						loan_type: loan_type.value,
						loan_amount: loan_amount.value,
						interest_rate: interest_rate.value,
						term_months: term_months.value,
						start_date: start_date.value,
						maturity_date: maturity_date.value,
						loan_comakers: loan_comakers.value,
						status: status.value,
						ammortizationdata :ammortizationdata,
						meaction: 'LOAN-AVAILMENT-SAVE'
					}

					console.log(mparam);

					jQuery.ajax({ // default declaration of ajax parameters
						type: "POST",
						url: mesiteurl + 'myloanavailment',
						context: document.body,
						data: eval(mparam),
						global: false,
						cache: false,
						success: function(data) { //display html using divID
							jQuery('.me-myloanavailment-outp-msg').html(data);
							return false;
						},
						error: function(xhr, status, error) { // display global error on the menu function
							//__mysys_apps.mybs_simple_toast('memsgtoastcont','metoastmsglang','align-items-center text-bg-danger border-0','Hello, Error Loading Page [TRXMGT-AP-ITEM-TAXDED-ENT]' + error);
							toastr.error('[myloanavailment-ENT', "Hello, Error Loading Page..." + error, {
							closeButton: true,
							});
							return false;
						} 
					}); 

					console.log("AJAX URL:", mesiteurl + 'myloanavailment');
				} catch(err) { 
					alert(err.message)
					return false;
				} //end try 
			}, false)
		}); //end forEach		
	}; //

	

}; //end main
