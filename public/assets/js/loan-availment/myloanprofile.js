var __mysys_loanprofile_ent = new __mysys_loanprofile_ent();
function __mysys_loanprofile_ent() {  
	const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

	this.__loanprofile_saving = function() { 
		'use strict' 
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.myloanprofile-validation')
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
					var interest = document.getElementById("interest");
					var principal = document.getElementById("principal");
					var total_payment = document.getElementById("total_payment");
					var payment_date = document.getElementById("payment_date");
					var ammortization_id = document.getElementById("ammortization_id");

					var mparam = { 
						loan_id: loan_id.value,
						member_id: member_id.value,
						interest: interest.value,
						principal: principal.value,
						total_payment: total_payment.value,
						payment_date: payment_date.value,
						ammortization_id: ammortization_id.value,
						meaction: 'LOAN-PAYMENT-SAVE'
					}

					console.log(mparam);

					jQuery.ajax({ // default declaration of ajax parameters
						type: "POST",
						url: mesiteurl + 'myloanprofile',
						context: document.body,
						data: eval(mparam),
						global: false,
						cache: false,
						success: function(data) { //display html using divID
							jQuery('.me-myloanprofile-outp-msg').html(data);
							return false;
						},
						error: function(xhr, status, error) { // display global error on the menu function
							//__mysys_apps.mybs_simple_toast('memsgtoastcont','metoastmsglang','align-items-center text-bg-danger border-0','Hello, Error Loading Page [TRXMGT-AP-ITEM-TAXDED-ENT]' + error);
							toastr.error('[myloanprofile-ENT', "Hello, Error Loading Page..." + error, {
							closeButton: true,
							});
							return false;
						} 
					}); 

					console.log("AJAX URL:", mesiteurl + 'myloanprofile');
				} catch(err) { 
					alert(err.message)
					return false;
				} //end try 
			}, false)
		}); //end forEach		
	}; //

	

}; //end main
