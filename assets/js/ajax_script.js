jQuery(document).ready(function ($) {

    function season_submit() {
        $('#season_type_chooser_form').on('submit', function () {
            var season_input = $('#season_type').val();
            console.log(season_input);

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
   ////        $.ajax({
   //            url: settings.ajaxURL, // Here goes our WordPress AJAX endpoint.
   //            type: 'post',
   //            data: {
   //                season_input,
   //                _ajax_nonce: settings.nonce,
   //                action: 'season_form'
   //            },
   //            success: function (response) {
   //                console.log(response);
   //                $('div.week_chooser').show();
   //                week_submit();

   //            },
   //            fail: function (err) {
   //                // You can craft something here to handle an error if something goes wrong when doing the AJAX request.
   //                alert("There was an error: " + err);
   //            }
   //        });

   //        $season_input = undefined;
   //        console.log($season_input);
   //        // This return prevents the submit event to refresh the page.
   //        return false;
            week_submit(season_input);
      })
    }

    function week_submit(season_input) {
        $('.ajax_choose_week').on('submit', function () {

                var pre_week = $('#pre_week').find(":selected").val();
                var reg_week = $('#reg_week').find(":selected").val();
                var post_week = $('#post_week').find(":selected").val();



            console.log( reg_week, pre_week, post_week);

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
                    console.log(response);
                   // document.getElementById("week_chooser_form").reset();
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