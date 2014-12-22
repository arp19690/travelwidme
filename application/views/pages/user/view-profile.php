<?php
//    prd($record);
    extract($record);
    $full_name = ucwords($first_name . " " . $last_name);
?>

<div class="container-fluid">
    <div class="row">
        <h1><?php echo $full_name; ?></h1>
    </div>
</div>

<div class="container-fluid margin-bottom-20">
    <div class="row pull-right">        
        <!-- AddThis Button BEGIN -->
        <?php
            echo getAddThis();
        ?>
        <!-- AddThis Button END -->
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="text-right">
            <?php
                $login_to_continue_class = "login-to-continue";
                if (isset($this->session->userdata["user_id"]))
                {
                    $login_to_continue_class = "";
                    if ($this->session->userdata["user_id"] != $user_id)
                    {
                        ?>
                        <div class="btn-group">
                            <?php
                            if (!$is_friend)
                            {
                                ?>
                                <a href="<?php echo base_url('connect/' . $username . '?next=' . current_url()); ?>" class="btn btn-success <?php echo $login_to_continue_class; ?>" title="Connect with <?php echo $full_name; ?>"><span class="glyphicon glyphicon-link"></span>&nbsp;Connect</a>
                                <?php
                            }
                            else
                            {
                                if ($is_accepted == "1")
                                {
                                    ?>
                                    <a href="<?php echo base_url('removeConnect/' . $username . '?next=' . current_url()); ?>" onclick="return confirm('Are you sure to remove?');" class="btn btn-default" title="Remove <?php echo $full_name; ?>"><span class="glyphicon glyphicon-link"></span>&nbsp;<span class='hover-remove-connect' rel='Connected'>Connected</span></a>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <a href="<?php echo base_url('removeConnect/' . $username . '?next=' . current_url()); ?>" onclick="return confirm('Are you sure to remove?');" class="btn btn-default" title="Remove <?php echo $full_name; ?>"><span class="glyphicon glyphicon-link"></span>&nbsp;<span class='hover-remove-connect' rel='Request Sent'>Request Sent</span></a>
                                    <?php
                                }
                            }
                            ?>
                            <a href="<?php echo base_url('messages/thread/' . $username); ?>" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Send message</a>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-cog"></span>&nbsp;<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu user-profile-report-drop-btn" role="menu">
                                <li><a href='#report' data-toggle="modal" data-target="#reportModal">Report abuse&nbsp;<span class="glyphicon glyphicon-ban-circle"></span></a></li>
                            </ul>
                        </div>
                        <?php
                    }
                }
            ?>
        </div>
        <br/>

        <div class="col-lg-12">
            <div class="col-lg-4">
                <div class="user-acount-img-block">
                    <?php
                        $facebookUserImage = getUserImage($user_id, $user_facebook_id, NULL, 200, 200);
                    ?>
                    <img src="<?php echo $facebookUserImage; ?>" alt="<?php echo $first_name . " " . $last_name; ?>" class="img-circle user-account-img lazy" data-original="<?php echo $facebookUserImage; ?>"/>
                    <p class="help-block margin-top-20">Member since: <strong><?php echo date("F d, Y", strtotime($user_timestamp)); ?></strong></p>

                    <?php
                        if (!empty($trips_record))
                        {
                            ?>
                            <p class="margin-top-40"><strong>Recent Posts</strong></p>
                            <ul class="profile-recent-posts">
                                <?php
                                foreach ($trips_record as $t_key => $t_value)
                                {
                                    echo '<li><a href="' . base_url('trip/view/' . $t_value["url_key"]) . '"><span class="glyphicon glyphicon-map-marker"></span>&nbsp;&nbsp;' . $t_value["trip_title"] . '</a></li>';
                                }
                                ?>
                            </ul>
                            <?php
                        }

                        $photos_array = (array) json_decode($user_facebook_photos);
//                        prd($photos_array);
                        if (isset($this->session->userdata["user_id"]) && !empty($photos_array))
                        {
                            ?>
                            <p class="margin-top-40"><strong>Photos</strong></p>
                            <ul class="profile-more-photos">
                                <?php
                                $photos_i = 0;
                                foreach ($photos_array as $key => $value)
                                {
                                    echo '<li><a href="' . $value->large . '" target="_blank" class="colorbox-img"><img src="' . $value->small . '" alt="image" class="lazy" data-original="' . $value->small . '"/></a></li>';
                                    $photos_i++;
                                    if ($photos_i == MAX_FACEBOOK_PHOTOS_DISPLAY)
                                    {
                                        break;
                                    }
                                }
                                ?>
                            </ul>
                            <?php
                        }

                        if (!empty($my_connects_record))
                        {
                            ?>
                            <p class="margin-top-40"><strong>My Connects (<?php echo number_format($my_connects_totalcount); ?>)</strong></p>
                            <ul class="profile-more-photos">
                                <?php
                                foreach ($my_connects_record as $mcKey => $mcValue)
                                {
                                    $friend_full_name = $mcValue["first_name"] . " " . $mcValue["last_name"];
                                    $facebookUserImage = getUserImage($mcValue["user_id"], $mcValue["user_facebook_id"], NULL, 70, 70);

                                    echo '<li><a href="' . getPublicProfileUrl($mcValue["username"]) . '" title="' . $friend_full_name . '"><img src="' . $facebookUserImage . '" alt="image" class="img-circle lazy my-connects-img" data-original="' . $facebookUserImage . '"/></a></li>';
                                }
                                ?>
                            </ul>
                            <?php
                        }
                    ?>
                </div>
            </div>

            <div class="col-lg-8">

                <div class="margin-bottom-20">
                    <div class="btn-group">
                        <a href="<?php echo current_url(); ?>" class="btn btn-default active"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;General</a>
                        <a href="<?php echo base_url('albums/' . $username); ?>" class="btn btn-default"><span class="glyphicon glyphicon-picture"></span>&nbsp;&nbsp;Albums</a>
                    </div>
                </div>

                <?php
                    if (!empty($user_bio))
                    {
                        ?>
                        <h3 class="margin-bottom-20">About me :-</h3>
                        <div class="jumbotron">
                            <p><?php echo $user_bio; ?></p>
                        </div>
                        <?php
                    }
                ?>

                <div class="user-public-details">
                    <p><label>Profession:</label><span><?php echo stripslashes($what_do); ?></span></p>
                    <p><label>Gender:</label><span><?php echo ucwords($user_gender); ?></span></p>
                    <p><label>Age:</label><span><?php echo getAge($user_birthday); ?> years old</span></p>
                    <p><label>Belongs From:</label><span><span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo $user_location; ?></span></p>
                    <p><label>Current Location:</label><span><span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo $user_current_location; ?></span></p>
                    <p><label>Languages:</label><span><?php echo $user_languages; ?></span></p>
                    <p><label>Favourite Places:</label><span><?php echo $user_favourite_places; ?></span></p>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Report <strong><?php echo $full_name; ?></strong></h4>
            </div>

            <form action='<?php echo base_url('user/reportUser/' . $username); ?>' method='post' class='validate-form'>
                <div class="modal-body">
                    <p>
                        <label>Reason:</label>
                        <select name='report_reason' class='form-control required' required='required'>
                            <?php
                                $report_Array = array("Fake User", "Offensive content", "Other");
                                ksort($report_Array);

//                                    echo '<option value="">Please select</option>';
                                foreach ($report_Array as $key => $value)
                                {
                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                }
                            ?>
                        </select>
                    </p><br/>

                    <p>
                        <label>Message:</label>
                        <textarea class='form-control textarea-resize-none' name='report_message' placeholder="Your message goes here ..."></textarea>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Report</button>
                </div>
            </form>

        </div>
    </div>
</div>