var __mysys_members_ent = new __mysys_members_ent();
function __mysys_members_ent() {  
	const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

	this.__members_saving = function() { 
		'use strict' 
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.mymembers-validation')
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

					var member_id = document.getElementById("member_id");
					var member_no = document.getElementById("member_no");
					var last_name = document.getElementById("last_name");
					var first_name = document.getElementById("first_name");
					var middle_name = document.getElementById("middle_name");
					var contact_number = document.getElementById("contact_number");
					var address = document.getElementById("address");
					var email = document.getElementById("email");

					var mparam = { 
						member_id: member_id.value,
						member_no: member_no.value,
						last_name: last_name.value,
						first_name: first_name.value,
						middle_name: middle_name.value,
						contact_number: contact_number.value,
						address: address.value,
						email: email.value,
						meaction: 'MEMBERS-SAVE'
					}

					jQuery.ajax({ // default declaration of ajax parameters
						type: "POST",
						url: mesiteurl + 'mymembers',
						context: document.body,
						data: eval(mparam),
						global: false,
						cache: false,
						success: function(data) { //display html using divID
							jQuery('.me-mymembers-outp-msg').html(data);
							return false;
						},
						error: function(xhr, status, error) { // display global error on the menu function
							//__mysys_apps.mybs_simple_toast('memsgtoastcont','metoastmsglang','align-items-center text-bg-danger border-0','Hello, Error Loading Page [TRXMGT-AP-ITEM-TAXDED-ENT]' + error);
							toastr.error('[mymembers-ENT', "Hello, Error Loading Page..." + error, {
							closeButton: true,
							});
							return false;
						} 
					}); 

					console.log("AJAX URL:", mesiteurl + 'mymembers');
				} catch(err) { 
					alert(err.message)
					return false;
				} //end try 
			}, false)
		}); //end forEach		
	}; //

	this.__delete_members = function() {
		const deleteBtn = document.getElementById('btn_delete');
		const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
	
		let member_id = null; // store member_id for use after confirmation
	
		deleteBtn.addEventListener('click', function () {
			member_id = document.getElementById("member_id").value;
	
			// Show the modal
			const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
			deleteModal.show();
		});
	
		confirmDeleteBtn.addEventListener('click', function () {
			if (!member_id) return;
	
			const mparam = {
				member_id: member_id,
				meaction: 'MAIN-DELETE'
			};
	
			jQuery.ajax({
				type: "POST",
				url: mesiteurl + 'mymembers',
				context: document.body,
				data: mparam,
				global: false,
				cache: false,
				success: function(data) {
					jQuery('.me-mymembers-outp-msg').html(data);
				},
				error: function(xhr, status, error) {
					toastr.error('[mymembers-ENT', "Hello, Error Loading Page..." + error, {
						closeButton: true,
					});
				}
			});
	
			// Close the modal
			const deleteModal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
			deleteModal.hide();
		});
	};
	

}; //end main

__mysys_members_ent.__delete_members();