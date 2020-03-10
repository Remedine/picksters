jQuery(document).ready(function ($) {

        $('#season_type_chooser_form').on('submit', function () {
            var user_input = $('#season_type').val();
            console.log(user_input);
            console.log('hi mom');

            // Here we add our nonce (The one we created on our functions.php. WordPress needs this code to verify if the request comes from a valid source.
            //form_data.push( { "name" : "security", "value" : ajax_nonce } );

            // Here is the ajax petition.
            jQuery.ajax({
                url : settings.ajax_url, // Here goes our WordPress AJAX endpoint.
                type : 'post',
                data : user_input,
                success : function( response ) {
                    // You can craft something here to handle the message return
                    //alert( response );
                    console.log('form sended!?');
                },
                fail : function( err ) {
                    // You can craft something here to handle an error if something goes wrong when doing the AJAX request.
                    alert( "There was an error: "  );
                }
            });

            // This return prevents the submit event to refresh the page.
            return false;
        })

});