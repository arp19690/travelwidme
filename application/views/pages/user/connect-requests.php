<div class="container-fluid">
    <div class="row">
        <h1><?php echo $page_title; ?></h1>
        <br/>

        <div class="col-lg-12 col-xs-12 container">
            <div class="col-lg-7 col-xs-12 margin-bottom-20">
                <div class="connect-requests-list">
                    <?php
                        if (!empty($record))
                        {
                            foreach ($record as $key => $value)
                            {
                                $logged_in_user_id = $this->session->userdata["user_id"];
                                $friend_id = $value["friend_id"];
                                $friend_message = stripslashes($value["friend_message"]);
                                $user_facebook_id = $value["user_facebook_id"];
                                $username = $value["username"];
                                $full_name = $value["first_name"] . " " . $value["last_name"];
                                $time_ago = getTimeAgo(strtotime($value["request_timestamp"]));

                                $facebookUserImage = getUserImage($value["user_id"], $user_facebook_id, NULL, 56, 56);
                                ?>
                                <div class="col-lg-12" id="connect-div-<?php echo $friend_id; ?>">
                                    <div class="col-lg-2 col-xs-4">
                                        <img src="<?php echo $facebookUserImage; ?>" alt="<?php echo $full_name; ?>" class="img-rounded lazy my-connects-img" data-original="<?php echo $facebookUserImage; ?>"/>
                                    </div>
                                    <div class="col-lg-10 col-xs-8 message-info">
                                        <div class='container-fluid'>
                                            <div class='row'>
                                                <p class="user-name pull-left"><a href="<?php echo base_url('profile/' . $username); ?>" title="View Profile" target="_blank"><?php echo $full_name; ?></a></p>
                                                <p class="time pull-right"><?php echo $time_ago; ?></p>
                                            </div>
                                        </div>

                                        <?php
                                        if (!empty($friend_message))
                                        {
                                            ?>
                                            <div class='container-fluid margin-top-20'>
                                                <div class='row'>
                                                    <p class="time"><?php echo $friend_message; ?></p>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>

                                        <div class='container-fluid margin-top-20'>
                                            <div class='row'>
                                                <p class="connect-request-action-block">
                                                    <a href="<?php echo base_url('user/connectActionAjax/accept/' . $friend_id); ?>" class="btn btn-success" id="<?php echo $friend_id; ?>">Accept</a>
                                                    <a href="<?php echo base_url('user/connectActionAjax/reject/' . $friend_id); ?>" class="btn btn-danger" id="<?php echo $friend_id; ?>">Reject</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        else
                        {
                            echo '<p class="margin-bottom-20 margin-top-20 margin-left-20 margin-right-20">No results found</p>';
                        }
                    ?>
                </div>
            </div>

            <div class="col-lg-5 text-right pull-right">
                <div class="chitika-ad">
                    <?php echo getChitikaAd(); ?>
                </div>
                <div class="chitika-ad margin-top-20">
                    <?php echo getChitikaAd(); ?>
                </div>
            </div>
        </div>
    </div>
</div>