<div class="container-fluid">
    <div class="row">
        <h1><?php echo $page_title; ?></h1>
        <p class='text-right'><a href='javascript:history.go(-1)' title="Go back" class="btn btn-default"><span class='glyphicon glyphicon-arrow-left'></span>&nbsp;Back</a></p>
        <br/>

        <div class="col-lg-12 col-xs-12 container nopadding">
            <div class="col-lg-12 col-xs-12 nopadding">
                <div class="message-conversation">

                    <div class="message-chats">
                        <ul class="col-lg-12 col-xs-12" id='chat-list'>
                            <?php
                                if (!empty($record))
                                {
                                    foreach ($record as $key => $value)
                                    {
                                        $facebookUserImage = getUserImage($value["user_id"], $value["user_facebook_id"], NULL, 60, 60);
                                        ?>
                                        <li class="">
                                            <div class="col-lg-1 col-xs-4 nopadding-left">
                                                <img src="<?php echo $facebookUserImage; ?>" alt="image" class="img-rounded lazy message-thread-img" data-original="<?php echo $facebookUserImage; ?>"/>
                                            </div>
                                            <div class="col-lg-11 col-xs-8 message-info nopadding-left">
                                                <p class="user-name"><?php echo $value["first_name"] . " " . $value["last_name"]; ?></p>
                                                <p class="time text-right"><?php echo getTimeAgo(strtotime($value["message_timestamp"])); ?></p>

                                                <p class="clearfix"><?php echo stripslashes($value["message_content"]); ?></p>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                }
                                else
                                {
                                    echo '<p class="margin-bottom-20 margin-top-20 no-messages-found" >No messages found</p>';
                                }
                            ?>

                            <span id='new-messages-here'></span>
                        </ul>
                    </div>

                    <div class="input-group input-group-lg">
                        <input type='hidden' name='message_to' id='message_to' value='<?php echo $message_to; ?>'/>
                        <textarea onfocus="removeNotificationText();" class="form-control textarea-resize-none" rows="2" placeholder="Your message here ..." name="message_content" id="message_content"></textarea>
                        <span class="input-group-addon" id="sendMessage" onclick="sendMessage('<?php echo base_url('messages/sendMessageAjax'); ?>');">Send</span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<audio id="chatAudio"><source src="<?php echo base_url('assets/front/notify.wav'); ?>" type="audio/wav"></audio>

<script>
                            $(document).ready(function() {
                                // to scroll down the message list automatically to the last message
                                scrollTOBottom("chat-list");

                                // for real time messaging
                                var url = "<?php echo base_url('messages/getUnreadChatsAjax/' . $message_to); ?>";
                                setInterval(function() {
                                    getUnreadChatsAjax(url);
                                }, "5000");    // refreshing every 5 seconds
                            });
</script>