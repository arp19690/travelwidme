<?php
    if (isset($record))
    {
        extract($record);
    }
    else
    {
        $first_name = "";
        $last_name = "";
        $username = "";
        $what_do = "";
        $user_mobile = "";
        $user_location = "";
        $user_bio = "";
        $user_gender = "";
    }
?>

<div class="container-fluid">
    <div class="row">
        <h1>My Account</h1>
        <br/>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <div class="user-acount-img-block my-account-page">
                <div class="account-hover-img">
                    <?php
                        $userImage = getUserImage($this->session->userdata["user_id"], $user_facebook_id, NULL, 200, 200);
                    ?>
                    <img src="<?php echo $userImage; ?>" alt="<?php echo $first_name . " " . $last_name; ?>" class="img-circle user-account-img lazy" data-original="<?php echo $userImage; ?>"/>
                    <a href="#changeImageModal" role="button" data-toggle="modal" class="user-account-img-hover-text btn btn-primary">Change image</a>
                </div>

                <p class="margin-top-20">View <a href="<?php echo getPublicProfileUrl($username); ?>" target="_blank" title="View Public Profile">Public Profile</a>&nbsp;&nbsp;<span class="glyphicon glyphicon-share"></span></p>

                <div id="connect-facebook">
                    <?php
                        if (empty($user_facebook_id))
                        {
                            ?>
                            <a href="<?php echo base_url('user/addFacebookConnection'); ?>" class="btn btn-primary margin-top-20 facebook-blue-btn" title="Connect with facebook"><strong>f</strong>&nbsp;|&nbsp;Connect with facebook</a>
                            <?php
                        }
                        else
                        {
                            ?>
                            <div class="margin-top-20">
                                <p>Facebook account connected&nbsp;&nbsp;<a href="<?php echo base_url('user/removeFacebookConnection'); ?>" onclick="return confirm('Are you sure you want to remove facebook connection?');" class="black"><span class="glyphicon glyphicon-remove"></span></a></p>
                            </div>
                            <?php
                        }
                    ?>
                </div>
            </div>



            <div class="margin-bottom-40 text-center">
                <?php
                    if (!empty($trips_record))
                    {
                        ?>
                        <p class="margin-top-40"><strong>Recent Posts</strong></p>
                        <ul class="profile-recent-posts">
                            <?php
                            foreach ($trips_record as $t_key => $t_value)
                            {
                                echo '<li><a href="' . base_url('trip/view/' . $t_value["url_key"]) . '" target="_blank"><span class="glyphicon glyphicon-map-marker"></span>&nbsp;&nbsp;' . $t_value["trip_title"] . '</a></li>';
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
                                $userImage = getUserImage($mcValue["user_id"], $mcValue["user_facebook_id"], NULL, 70, 70);

                                echo '<li><a href="' . getPublicProfileUrl($mcValue["username"]) . '" title="' . $friend_full_name . '"><img src="' . $userImage . '" alt="image" class="img-circle lazy" data-original="' . $userImage . '"/></a></li>';
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

            <form id="contact-form" class="col-lg-10 validate-form" action="" method="post" autocomplete="off" role="form">
                <div class="form-group">
                    <label>First Name:</label>
                    <input type="text" name="first_name" class="form-control required" placeholder="First Name" required="required" value='<?php echo $first_name; ?>'/>
                </div>
                <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" name="last_name" class="form-control required" placeholder="Last Name" required="required" value='<?php echo $last_name; ?>'/>
                </div>
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" class="form-control required" placeholder="Username" required="required" value='<?php echo $username; ?>'/>
                </div>
                <div class="form-group">
                    <label>Gender:</label>
                    <select name="user_gender" class="form-control required">
                        <?php
                            $gender_array = array(
                                "male" => "Male",
                                "female" => "Female",
                                "others" => "Others",
                            );

                            foreach ($gender_array as $key => $value)
                            {
                                $gender_selected = "";
                                if ($user_gender == $key)
                                    $gender_selected = "selected='selected'";

                                echo '<option value="' . $key . '" ' . $gender_selected . '>' . $value . '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Your Mobile:</label>
                    <input type="text" name="user_mobile" class="form-control required" placeholder="Your Mobile Number" required="required" maxlength="18" value='<?php echo $user_mobile; ?>'/>
                </div>
                <div class="form-group">
                    <label>I Belong From:</label>
                    <input type="text" name="user_location" class="form-control required gMapLocation" placeholder="Your Location" required="required" value='<?php echo $user_location; ?>'/>
                </div>
                <div class="form-group">
                    <label>Your Current Location:</label>
                    <input type="text" name="user_current_location" class="form-control required gMapLocation" placeholder="Your Current Location" required="required" value='<?php echo $user_current_location; ?>'/>
                </div>
                <div class="form-group">
                    <label>Your Bio:</label>
                    <textarea name="user_bio" class="form-control required textarea-resize-none" rows="3" minlength="100" maxlength="250" placeholder="Your Bio" required="required" ><?php echo $user_bio; ?></textarea>
                    <p class="help-block">250 characters allowed</p>
                </div>

                <div class="form-group">
                    <label>What do you do:</label>
                    <div class="input-group">
                        <span class="input-group-addon">I am</span>
                        <input type="text" name="what_do" class="form-control required" placeholder="What do you do" required="required" value='<?php echo $what_do; ?>'/>
                    </div>
                </div>

                <div class="form-group">
                    <label>Favourite Places:</label>
                    <textarea name="user_favourite_places" class="form-control required textarea-resize-none" rows="3" placeholder="Favourite Places" required="required" ><?php echo $user_favourite_places; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Languages Known:</label>
                    <textarea name="user_languages" class="form-control required textarea-resize-none" rows="3" placeholder="Languages Known" required="required" ><?php echo $user_languages; ?></textarea>
                </div>

                <input type="submit" name="btn_submit" class="btn btn-info btn-lg" value="Update"/>
            </form>
        </div>
    </div>
</div>

<!--Change Image Modal Box-->
<div class="modal fade" id="changeImageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Change Image</h4>
            </div>
            <form id="change-image-form" class="" enctype="multipart/form-data" action="<?php echo base_url('user/changeProfilePicture'); ?>" method="post">
                <div class="modal-body">
                    <div class='position-relative'>
                        <a class='btn btn-default' href='javascript:;'>
                            <span class='glyphicon glyphicon-camera'></span>&nbsp;&nbsp;Choose Image...
                            <input type="file" class="custom-input-file" name="user_img" size="40"  onchange='$(this).parent().next().html($(this).val());'>
                        </a>
                        &nbsp;
                        <span class='label label-info' id="upload-file-info"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php
                        if (is_file(USER_IMG_PATH . "/" . getEncryptedString($this->session->userdata["user_id"]) . ".jpg"))
                        {
                            ?>
                            <a href="<?php echo base_url('user/removeProfilePicture'); ?>" class="btn btn-default pull-left" onclick="return confirm('Sure you want to remove the picture you uploaded?')"><span class="glyphicon glyphicon-remove-circle"></span>&nbsp;&nbsp;Remove current image</a>
                            <?php
                        }
                    ?>
                    <input type="submit" class="btn btn-primary" value="Upload"/>
                </div>
            </form>
        </div>
    </div>
</div>