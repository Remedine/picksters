jQuery(document).ready(function ($) {

    function season_submit() {
        $('#season_type_chooser_form').change(function () {
            var season_input = $('#season_type option:selected').val();
            console.log(season_input);
            $('div.week_chooser').show();

            if (season_input == 'PRE') {
                $("#reg_week, #post_week").hide();
                $("#reg_week, #post_week").attr('disabled', true);
                $("#pre_week").show();
                $('#pre_week').removeAttr('disabled');
            }
            else if (season_input == 'REG') {
                $("#pre_week, #post_week").hide();
                $("#pre_week, #post_week").attr('disabled', true);
                $("#reg_week").show();
                $('#reg_week').removeAttr('disabled');
            }
            else if (season_input == 'POST') {
                $("#pre_week, #reg_week").hide();
                $("#pre_week, #reg_week").attr('disabled', true);
                $("#post_week").show();
                $('#post_week').removeAttr('disabled');
            }
            else {
                console.log('something is up');
            }
            week_submit(season_input);
        })
    }


    function week_submit(season_input) {
        var view_data;
        $('.ajax_choose_week').on('submit', function () {

            var pre_week = $('#pre_week').find(":selected").val();
            var reg_week = $('#reg_week').find(":selected").val();
            var post_week = $('#post_week').find(":selected").val();

            console.log(reg_week, pre_week, post_week);

            // Here is the ajax petition.
            $.ajax({
                url: settings.ajaxURL, // Here goes our WordPress AJAX endpoint.
                type: 'post',
                data: {
                    season_input,
                    pre_week,
                    reg_week,
                    post_week,
                    _ajax_nonce: settings.nonce,
                    action: 'week_form'
                },
                success: function (response) {
                    view_data = response.view_data;
                    console.log(view_data);
                    $("#result").html(response)
                },
                fail: function (err) {
                    // You can craft something here to handle an error if something goes wrong when doing the AJAX request.
                    alert("There was an error: " + err);
                }
            });

            // This return prevents the submit event to refresh the page.
            return false;
        })
    }
    season_submit();
})


//console.log(view_data); //Shows the correct piece of information
//doWork(view_data); // Pass data to a function
//}
//});
//
//function doWork(data)
//{
//    //perform work here
//}