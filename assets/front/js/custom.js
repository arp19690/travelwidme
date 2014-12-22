var validateMessages = {"author_required": "Please enter a name.", "author_minlength": "Your name must consist of at least 4 characters.", "email_required": "Please enter a valid email address.", "url_required": "Please enter a valid url.", "comment_required": "Please enter a comment.", "comment_minlength": "Your comment must consist of at least 10 characters."};

// Google places API to generate dropdown of places
var num_of_inputs = jQuery(".gMapLocation").length;
var i;
var loop = num_of_inputs - 1;
for (i = "0"; i <= loop; i++)
{
    var options = {
        types: ['(cities)'],
    };
    var input = jQuery(".gMapLocation")[i];
    var autocomplete = new google.maps.places.Autocomplete(input, options);
}





// document ready


$(function () {

//    to disable the button once clicked
//    $('.disable-btn').click(function() {
//        $(this).attr('disabled', 'disabled');
//        $(this).html('Please wait...');
//        $(this).val('Please wait...');
//    })

    // to show user a amessage that their images are bieng saved
    $('.after-image-upload-form .save-btn').click(function (event) {
        event.preventDefault();
        var form_ser = $('.after-image-upload-form').serialize();
        var org_save_btn = $(this).html();
        $(this).html('Saving...');
        $.ajax({
            type: 'POST',
            url: $('.after-image-upload-form').attr('action'),
            data: form_ser,
            success: function (response) {
                if (response == 'ok')
                {
                    $('.after-image-upload-form .save-btn').html('Saved');
                    setTimeout(function () {
                        $('.after-image-upload-form .save-btn').html(org_save_btn);
                    }, '1000');
                }
            }
        });
    });

// to remove a particular comment via ajax
    $(document).on('click', '.remove-comment-link', function (event) {
        event.preventDefault();
        var cnfm = confirm('Sure you want to remove this comment?');
        if (cnfm)
        {
            var url = $(this).attr('rel');
            var li_id = $(this).attr('id');
            deleteCommentAjax(url, li_id);
        }
    });

// to remove a particular photo via ajax
    $('.remove-photo-ajax').click(function (event) {
        event.preventDefault();
        var cnfm = confirm('Sure you want to remove this picture?');
        if (cnfm)
        {
            var url = $(this).attr('rel');
            var li_id = $(this).attr('id');
            deletePhotoAjax(url, li_id);
        }
    });

// when BROWSE link is click open sect file box
    $('form.upload-multi-photos a.select-multi-photos').click(function (event) {
        event.preventDefault();
        $('input.multi-photo-input').click();
    });

// trigger multi upload
    $('input.multi-photo-input').change(function () {
        $('.after-image-upload-form').removeClass('hide');
        $('.after-image-upload-form ul').html('<li><p>Please wait while we are processing your photos. It might take a while...</p></li>');
        $.ajax({
            type: 'POST',
            url: $('form.upload-multi-photos').attr('action'),
            dataType: 'json',
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            data: new FormData($('form.upload-multi-photos')[0]),
            success: function (response) {
                if (response != '')
                {
                    $('.after-image-upload-form ul').html(response);
                    $('.after-image-upload-form .save-btn').removeClass('hide');
                }
            }
        });
    });

    $('.select-privacy-btns button').click(function (event) {
        event.preventDefault();
        var privacy_value = $(this).attr('id');
        $('.input-album-privacy').val(privacy_value);
        $('.select-privacy-btns button').removeClass('active');
        $(this).addClass('active');
    });

    // to load lazyload on images
    $(".lazy").lazyload({
        effect: "fadeIn"
    });

    // like any post via ajax
    $(".like-status-block a").click(function (event) {
        event.preventDefault();
        var url = $(this).attr('rel');
        var test_a = $(this).parent().find('a');
        var test_me = $(this);
        $.ajax({
            url: url,
            success: function (response) {
                if (response === "ok")
                {
                    test_a.removeClass('hide');
                    test_me.addClass('hide');
                }
            }
        });
    });

// to generate alert box when user not logged in
    $(".login-to-continue").click(function (event) {
        event.preventDefault();
        alert('Please login to continue');
    });

// to slide up particular div when any connect request is accepted or rejected
    $(".connect-request-action-block a").click(function (event) {
        event.preventDefault();
        var friend_id = $(this).attr("id");
        $(this).parent().html("Please wait...");
        $.ajax({
            url: $(this).attr('href'),
            success: function (response) {
                if (response == "ok")
                {
                    $("#connect-div-" + friend_id).slideUp();
                }
            }
        });
    });

    // to automatically add number_format when numeric digits added
    $(".add-comma-digits").keyup(function (event) {
        // skip for arrow keys
        if (event.which >= 37 && event.which <= 40) {
            event.preventDefault();
        }
        $(this).val(function (index, value) {
            return value
                    .replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    });


    // to call colorbox on image
    $('.colorbox-img').colorbox({
        rel: "gallery",
        maxWidth: '95%',
        maxHeight: '95%',
    });

    // to show Please wait text when the user clicks on Login with Facebook
    $("#fbLoginButton").click(function () {
        $("#fbLoginWait").removeClass('hide');
    });

// to send message on enter press
    $('#message_content').keypress(function (event) {
        if (event.keyCode === 13)
        {
            event.preventDefault();
            $(this).next().click();
        }
    });

// to interact with user when the user logged in late at night and early in the morning
    var clientLocalTime = parseFloat(getClientLocalTime());
//    console.log(clientLocalTime);
    if (clientLocalTime >= 0 && clientLocalTime <= 4.30)
    {
        $('#late-night').removeClass('hide');
    }
    if (clientLocalTime >= 5.00 && clientLocalTime <= 7.30)
    {
        $('#early-morning').removeClass('hide');
    }

    // to validate forms
    $('.validate-form').validate();

    // to check if the entered value is numeric
    $('.only-numeric').keyup(function () {
        var this_val = $(this).val();
        var output = $.isNumeric(this_val);
        if (!output)
        {
            $(this).val('');
        }
    });

    // to toggle between button options on the homepage destination form
    $('.homepage-destination-form ul.nav li a').click(function (event) {
        event.preventDefault();
        $('.homepage-destination-form ul.nav li').removeClass('active');
        $(this).parent().addClass('active');

        $('.homepage-destination-form #looking_for').val($(this).attr('id'));
    });

    $(".hover-remove-connect").hover(function () {
        $(this).html('Remove?');
    });

    $(".hover-remove-connect").mouseout(function () {
        $(this).html($(this).attr('rel'));
    });

// to automatically disperse the alert message after 5 seconds
    setTimeout(function () {
        $('.alert').parent().parent().slideUp()
    }, '5000');

});