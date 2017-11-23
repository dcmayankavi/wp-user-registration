(function( $ ) {

	var UserRegistration = {

		/**
		 * Initializes the services logic.
		 *
		 * @return void
		 * @since 0.0.1
		 */

		init: function() {

			$(document).on('submit', '.wp-user-registration-form form', function(e){
				e.preventDefault();

				var form = $(this),
					data = form.serialize();

				$.ajax({
					url: UserRegistrationLocalized.ajaxurl,
					data: {
						'action': 'save_new_user_data',
						'data': data,
						'nonce': UserRegistrationLocalized.nonce
					},
					type: 'POST',
					dataType: 'json',
					success: function( response ) {
						alert( response['message'] );
					},
					error: function (response ) {
						console.log(response);
					}
				});
				
			});
		},
	};

	$( function() {
		
		UserRegistration.init();
	});

})( jQuery );