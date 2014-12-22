function scrollTOBottom(id) {
    var objDiv = document.getElementById(id);
    objDiv.scrollTop = objDiv.scrollHeight;
}

// to blink the message icon in the navbar
function notificationBlink(id)
{
    setInterval(function() {
        $(id).toggleClass('black')
    }, 800);
}

// to get client's local machine time or local time
function getClientLocalTime()
{
    var currentTime = new Date()
    var hours = currentTime.getHours()
    var minutes = currentTime.getMinutes()

    return hours + "." + minutes;
}

// to check if unread messages, so that the message icon in the navbar could blink
function checkNavbarBlinkNotification(url, id)
{
    if (url != "" && id != "")
    {
        $.ajax({
            url: url,
            async: true,
            success: function(response) {
                if (response == "ok")
                {
                    notificationBlink(id);
                }
            }
        });
    }
}

// to write comment on a photo via ajax when send button is clicked
function writeCommentOnPhoto(current_url, image_name, comment_text)
{
    $("p.no-comments-found").remove();
    $('.write-comment-block textarea').val('');
    if (comment_text != "")
    {
        $.ajax({
            type: "POST",
            url: current_url,
            data: {"comment_text": comment_text, "image_name": image_name},
            success: function(response) {
                $('span#new-comments-here').append(response);
            }
        });
    }
}

// to send message via ajax when send button is clicked
function sendMessage(url)
{
    var message_to = $('#message_to').val();
    var message_content = $('#message_content').val();
    $("p.no-messages-found").remove();
    $('#message_content').val('');
    if (message_content != "")
    {
        $.ajax({
            type: "POST",
            url: url,
            data: {"message_to": message_to, "message_content": message_content},
            success: function(response) {
                $('span#new-messages-here').append(response);
                scrollTOBottom("chat-list");
            }
        });
    }
}

// to get ajax chat messages while you have opened any thread for real time messaging experience
function getUnreadChatsAjax(url)
{
    if (url != "")
    {
        $.ajax({
            url: url,
            async: true,
            success: function(response) {
                if (response != "")
                {
                    $("span#new-messages-here").append(response);
                    // to scroll down the message list automatically to the last message
                    scrollTOBottom("chat-list");
                    // to ring a notification when the textarea is not selected
                    if (!$("#message_content").is(":focus"))
                    {
                        $('#chatAudio')[0].play();
                        var title_original_text = $('title').html();
                        $('title').html("(1) " + title_original_text);
//                        document.title = "(1) " + title_original_text;
                    }
                }
            }
        });
    }
}

// to clear the notification text i.e (1) from the title tag
function removeNotificationText()
{
    var title_text = $('title').html();
    var new_text = title_text.replace("(1) ", "");
    $('title').html(new_text);
}

// to trigger like or dislike when the button is click for a photo
function likeDislikePhotoAjax(currenturl, image_name, pld_code, current_text)
{
//    var current_text = $(this).children('span.pld-text').html();
    var new_like_text = 'Like';
    var new_dislike_text = 'Dislike';
    var new_text;
    var url = currenturl + "/" + image_name + "/" + pld_code;

    if (current_text == 'Like')
    {
        new_like_text = 'Liked';
        new_text = new_like_text;
    }
    if (current_text == 'Dislike')
    {
        new_dislike_text = 'Disliked';
        new_text = new_dislike_text;
    }

    $.ajax({
        url: url,
        success: function(response) {
            if (response == 'ok')
            {
                $('.pld a#' + pld_code).children('span.pld-text').html(new_text);
                $('.pld a#' + pld_code).attr('disabled', 'disabled');
            }
            else
            {
                alert(response);
            }
        }
    });
}

// to remove a particular photo via ajax
function deletePhotoAjax(url, remove_li_id)
{
    $.ajax({
        url: url,
        success: function(response) {
            if (response == 'ok')
            {
                $('ul.all-photos li#' + remove_li_id).hide('slow');
//                $('ul.all-photos li#' + remove_li_id).remove();
            }
        }
    });
}

// to remove a particular comment via ajax
function deleteCommentAjax(url, remove_li_id)
{
    $.ajax({
        url: url,
        success: function(response) {
            if (response == 'ok')
            {
                $('ul.photo-comment-list li#' + remove_li_id).hide('slow');
//                $('ul.all-photos li#' + remove_li_id).remove();
            }
        }
    });
}