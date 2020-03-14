jQuery(document).ready(function ($) {

        $('#season_type_chooser_form').on('submit', function () {
            var season_input = $('#season_type').val();
            console.log(season_input);

            // Here is the ajax petition.
            $.ajax({
                url : settings.ajaxURL, // Here goes our WordPress AJAX endpoint.
                type : 'post',
                data : {
                    season_input,
                    _ajax_nonce: settings.nonce,
                   action: 'season_form'
                },
                success: function (response) {
                    console.log(response);
                    var respond = response;
                    console.log(respond.data['season_type']);
                },
                fail : function( err ) {
                    // You can craft something here to handle an error if something goes wrong when doing the AJAX request.
                    alert( "There was an error: " + err );
                }
            });

            // This return prevents the submit event to refresh the page.
            return false;
        })

});