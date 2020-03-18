jQuery(document).ready(function ($) {

    $('#season_type_chooser_form').on('submit', function () {
        var season_input = $('#season_type').val();
        console.log(season_input);

        if (season_input == 'PRE') {
            $('div.week_chooser').hide();
            $('div#pre_week').show();
        }
        else if (season_input == 'REG') {
            $('div.week_chooser').hide();
            $('div#reg_week').show();
        }
        else if (season_input == 'POST') {
            $('div.week_chooser').hide();
            $('div#post_week').show();
        }
        else {
            console.log('something is up');
        }
        $.ajax({
            url: settings.ajaxURL, // Here goes our WordPress AJAX endpoint.
            type: 'post',
            data: {
                season_input,
                _ajax_nonce: settings.nonce,
                action: 'season_form'
            },
            success: function (response) {
                console.log(response);

            },
            fail: function (err) {
                // You can craft something here to handle an error if something goes wrong when doing the AJAX request.
                alert("There was an error: " + err);
            }
        });

        // This return prevents the submit event to refresh the page.
        return false;
    })


    $('.week_chooser').on('submit', function () {
        var week_input = $('#reg_week').val();

        console.log(week_input);
        // Here is the ajax petition.
        $.ajax({
            url: settings.ajaxURL, // Here goes our WordPress AJAX endpoint.
            type: 'post',
            data: {
                week_input,
                _ajax_nonce: settings.nonce,
                action: 'week_form'
            },
            success: function (response) {
                console.log(response);

            },
            fail: function (err) {
                // You can craft something here to handle an error if something goes wrong when doing the AJAX request.
                alert("There was an error: " + err);
            }
        });

        // This return prevents the submit event to refresh the page.
        return false;
    })

})