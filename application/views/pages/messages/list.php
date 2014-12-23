<div class="container-fluid">
    <div class="row">
        <h1><?php echo $page_title; ?></h1>
        <div class='text-right'>
            <?php
//                $inbox_active_class = "btn-default";
//                $outbox_active_class = "btn-default";
//                if ($active_class == "inbox")
//                {
//                    $inbox_active_class = "btn-info";
//                }
//                if ($active_class == "outbox")
//                {
//                    $outbox_active_class = "btn-info";
//                }
            ?>
<!--            <a href='<?php echo base_url('messages/inbox'); ?>' class='btn <?php echo $inbox_active_class; ?>'><span class='glyphicon glyphicon glyphicon-save'></span>&nbsp;Inbox</a>
            <a href='<?php echo base_url('messages/outbox'); ?>' class='btn <?php echo $outbox_active_class; ?>'><span class='glyphicon glyphicon glyphicon-open'></span>&nbsp;Outbox</a>-->
        </div>
        <br/>

        <div class="col-lg-12 col-xs-12 container">
            <div class="col-lg-7 col-xs-12 margin-bottom-20 ">
                <div class="messages-list">
                    <ul class="col-lg-12 col-xs-12">
                        <?php
                            if (!empty($record))
                            {
                                foreach ($record as $key => $value)
                                {
//                                    prd($record);
                                    $logged_in_user_id = $this->session->userdata["user_id"];
                                    $user_facebook_id = $value["user_facebook_id"];
                                    $full_name = $value["first_name"] . " " . $value["last_name"];
                                    $time_ago = getTimeAgo(strtotime($value["message_timestamp"]));
                                    $message_content = substr($value["message_content"], 0, 50);

                                    $facebookUserImage = getUserImage($value["user_id"],$user_facebook_id, NULL, 56, 56);

                                    $unread_class = "";
                                    if ($value["message_from"] != $logged_in_user_id && $value["message_read"] == 0)
                                    {
                                        $unread_class = "unread";
                                    }
                                    ?>
                                    <li class="">
                                        <a href="<?php echo base_url('messages/thread/' . $value["username"]); ?>" class='<?php echo $unread_class; ?>'>
                                            <div class="col-lg-2 col-xs-4">
                                                <img src="<?php echo $facebookUserImage; ?>" alt="<?php echo $full_name; ?>" class="img-rounded lazy message-list-img" data-original="<?php echo $facebookUserImage; ?>"/>
                                            </div>
                                            <div class="col-lg-10 col-xs-8 message-info row">
                                                <p class="user-name"><?php echo $full_name; ?></p>
                                                <p class="text-right time"><?php echo $time_ago; ?></p>

                                                <p class="message-text"><?php echo $message_content; ?></p>
                                            </div>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            else
                            {
                                echo '<p class="margin-bottom-20 margin-top-20">No results found</p>';
                            }
                        ?>
                    </ul>
                </div>
            </div>

            <div class="col-lg-5 text-right pull-right">
                
            </div>
        </div>
    </div>
</div>