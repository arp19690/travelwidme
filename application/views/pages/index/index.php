<div class="jumbotron text-center home-background">
    <div class="home-background-transparent">

        <h1 class="margin-bottom-20">Connect with travelers</h1>
        <p class="hidden-xs hidden-sm margin-bottom-20">
            Planning for a vacation.. But not sure about the trip? No worries, you will find loads of people here who are willing to go places and also have awesome plans for their trip. Be a part of it.
            <br/>All you have to do is just search for them over here.</p>

        <form role="form" class="form-inline homepage-destination-form validate-form" action="<?php echo base_url('trip/search'); ?>">
            <input type="hidden" id="looking_for" name="looking_for" value="travelers"/>
            <div class="col-lg-6 col-lg-offset-3">
                <h2><strong>I am looking for:</strong></h2>

                <ul class="nav nav-pills col-lg-offset-4 col-sm-offset-5 col-xs-offset-2 margin-bottom-20 margin-top-20 ">
                    <li class="active"><a href="#" id="travel"><span class="glyphicon glyphicon-plane"></span>&nbsp;&nbsp;Travelers</a></li>
                </ul>

                <div class="input-group">
                    <input type="text" class="form-control gMapLocation" placeholder="I am going to ..." name="destination" />

                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-send"></span></button>
                    </span>
                </div><!-- /input-group -->
            </div>
        </form>
    </div>
</div>

<div class="container text-center below-slider">
    <div class="row">
        <div class="col-lg-4">
            <span class="glyphicon glyphicon-globe glyphicon-font-large"></span>
            <h2 class="margin-top-20">Discover trips</h2>
            <p class="margin-top-20">Discover trips added by other travelers or famous celebrities and be a part of it. Browse through their experiences, photos, reviews and much more.</p>
        </div>
        <div class="col-lg-4">
            <span class="glyphicon glyphicon-edit glyphicon-font-large"></span>
            <h2 class="margin-top-20">Create your own trip</h2>
            <p class="margin-top-20">Are you going somewhere? Or planning to go somewhere? Simply add your travel plan and get a chance of going out with so many other people.</p>
        </div>
        <div class="col-lg-4">
            <span class="glyphicon glyphicon-link glyphicon-font-large"></span>
            <h2 class="margin-top-20">Connect with people</h2>
            <p class="margin-top-20">Browse through travelers or hosts profile, connect and meet with like-minded people from around the globe and share your travel experiences.</p>
        </div>
    </div>
</div>

<div class="container-fluid margin-top-40 text-center">
    <div class="row clearfix">
        <p>Love traveling, like being out there on roads, like meeting new people? Well, what are you waiting for? Go on, push the button to explore.</p>
        <p>No need of filling up long forms about your personal details, interests, etc. we will do it all for you.</p>
        <?php
            if (!isset($this->session->userdata["user_id"]))
            {
                ?>
                <a href="<?php echo base_url("login/facebook?next=" . current_url()); ?>" class="btn btn-primary btn-lg margin-top-20 facebook-blue-btn" title="Login with facebook"><span class="facebook-initial">f</span>&nbsp;&nbsp;Login with facebook</a>
                <?php
            }
        ?>
    </div>
</div>

<?php
    if (!empty($travel_records))
    {
        ?>
        <div class="container-fluid margin-top-40">
            <div class="row clearfix">
                <h1 class="margin-bottom-20">Trips by Travelers:</h1>

                <?php
                $travel_i = 1;
                foreach ($travel_records as $tKey => $tValue)
                {
                    $user_full_name = $tValue["first_name"] . " " . $tValue["last_name"];
                    $trip_title = $tValue["trip_title"];
                    $link = getTripUrl($tValue["url_key"]);

                    if ($travel_i == 1)
                        echo '<div class="width-100 margin-bottom-20">';
                    ?>
                    <div class="trip-masonry-blocks">
                        <div class="img-like-container">
                            <?php
                            $tripHeaderImage = getTripHeaderImage($tValue["trip_header_image"], $tValue["trip_images"]);
                            ?>
                            <a href="<?php echo $link; ?>"><img src="<?php echo $tripHeaderImage; ?>" alt="<?php echo $trip_title; ?>" class="img-rounded margin-bottom-20 width-100 trip-header-img lazy" data-original="<?php echo $tripHeaderImage; ?>"/></a>

                            <?php
                            if (isset($this->session->userdata["user_id"]) && $tValue["trip_user_id"] != @$this->session->userdata["user_id"])
                            {
                                $liked = "hide";
                                $to_like = "";
                                if ($tValue["like_user_id"] == $this->session->userdata["user_id"])
                                {
                                    $liked = "";
                                    $to_like = "hide";
                                }
                                ?>
                                <div class="hover-like-block like-status-block">                            
                                    <a href="#" class="btn btn-default <?php echo $to_like; ?>" id="add" rel="<?php echo base_url('trip/tripToggleLikeAjax/add/' . $tValue["url_key"]); ?>"><span class="glyphicon glyphicon-heart-empty"></span>&nbsp;&nbsp;Like</a>
                                    <a href="#" onclick="return confirm('Are you sure to unlike?');" class="btn btn-success <?php echo $liked; ?>" id="remove" rel="<?php echo base_url('trip/tripToggleLikeAjax/remove/' . $tValue["url_key"]); ?>"><span class="glyphicon glyphicon-heart"></span>&nbsp;&nbsp;Liked</a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                        <a href="<?php echo $link; ?>"><h4 class="margin-bottom-20"><?php echo $trip_title; ?></h4></a>
                        <p>By: &nbsp;&nbsp;
                            <?php
                            $facebookUserImage = getUserImage($tValue["trip_user_id"], $tValue["user_facebook_id"], NULL, 70, 70);
                            ?>
                            <span><img src="<?php echo $facebookUserImage; ?>" alt="<?php echo $user_full_name; ?>" class="img-circle trip-bottom-user-img lazy" data-original="<?php echo $facebookUserImage; ?>"/></span>
                            &nbsp;&nbsp;<a href="<?php echo getPublicProfileUrl($tValue["username"]); ?>"><span><?php echo $user_full_name; ?></span></a>
                        </p>
                    </div>
                    <?php
                    $travel_i++;
                    if ($travel_i == 4 || count($travel_records) == $tKey + 1)
                    {
                        $travel_i = 1;
                        echo '</div>';
                    }
                }
                ?>
            </div>

            <?php
            if (count($travel_records) > 8)
            {
                ?>
                <div class="text-center clearfix margin-top-40">
                    <a href="<?php echo base_url('trip/search?looking_for=travel'); ?>" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-th"></span>&nbsp;&nbsp;View More</a>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }

    if (!empty($host_records))
    {
        ?>

        <hr class="margin-top-40"/>

        <div class="container margin-top-40">
            <div class="row-fluid masonry clearfix">
                <h1 class="margin-bottom-20">People willing to be your Hosts:</h1>

                <?php
                foreach ($host_records as $hKey => $hValue)
                {
                    $user_full_name = $hValue["first_name"] . " " . $hValue["last_name"];
                    $trip_title = $hValue["trip_title"];
                    $link = getTripUrl($hValue["url_key"]);

                    $tripHeaderImage = getTripHeaderImage($hValue["trip_header_image"], $hValue["trip_images"]);
                    ;

                    if ($travel_i == 1)
                        echo '<div class="width-100 margin-bottom-20">';
                    ?>
                    <div class="trip-masonry-blocks">
                        <div class="img-like-container">
                            <a href="<?php echo $link; ?>"><img src="<?php echo $tripHeaderImage; ?>" alt="<?php echo $trip_title; ?>" class="img-rounded margin-bottom-20 width-100 trip-header-img lazy" data-original="<?php echo $tripHeaderImage; ?>"/></a>

                            <?php
                            if (isset($this->session->userdata["user_id"]) && $hValue["trip_user_id"] != @$this->session->userdata["user_id"])
                            {
                                $liked = "hide";
                                $to_like = "";
                                if ($hValue["like_user_id"] == $this->session->userdata["user_id"])
                                {
                                    $liked = "";
                                    $to_like = "hide";
                                }
                                ?>
                                <div class="hover-like-block like-status-block">                            
                                    <a href="#" class="btn btn-default <?php echo $to_like; ?>" id="add" rel="<?php echo base_url('trip/tripToggleLikeAjax/add/' . $hValue["url_key"]); ?>"><span class="glyphicon glyphicon-heart-empty"></span>&nbsp;&nbsp;Like</a>
                                    <a href="#" onclick="return confirm('Are you sure to unlike?');" class="btn btn-success <?php echo $liked; ?>" id="remove" rel="<?php echo base_url('trip/tripToggleLikeAjax/remove/' . $hValue["url_key"]); ?>"><span class="glyphicon glyphicon-heart"></span>&nbsp;&nbsp;Liked</a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                        <a href="<?php echo $link; ?>"><h4 class="margin-bottom-20"><?php echo $trip_title; ?></h4></a>
                        <p>By: &nbsp;&nbsp;
                            <?php
                            $facebookUserImage = getUserImage($hValue["trip_user_id"], $hValue["user_facebook_id"], NULL, 70, 70);
                            ?>
                            <span><img src="<?php echo $facebookUserImage; ?>" alt="<?php echo $user_full_name; ?>" class="img-circle trip-bottom-user-img lazy" data-original="<?php echo $facebookUserImage; ?>"/></span>
                            &nbsp;&nbsp;<a href="<?php echo getPublicProfileUrl($hValue["username"]); ?>"><span><?php echo $user_full_name; ?></span></a>
                        </p>
                    </div>
                    <?php
                    $travel_i++;
                    if ($travel_i == 4 || count($travel_records) == $tKey + 1)
                    {
                        $travel_i = 1;
                        echo '</div>';
                    }
                }
                ?>
            </div>

            <?php
            if (count($host_records) > 8)
            {
                ?>
                <div class="text-center clearfix margin-top-40">
                    <a href="<?php echo base_url('trip/search?looking_for=host'); ?>" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-th"></span>&nbsp;&nbsp;View More</a>
                </div>
                <?php
            }
            ?>
        </div>

        <?php
    }
?>

<div class="jumbotron margin-top-40 text-center">
    <h2>Connect with us: </h2>
    <ul class="connect-with-us-ul margin-top-20">
        <li><a href="<?php echo FACEBOOK_SOCIAL_LINK; ?>" title="Facebook" target="_blank"><img src="<?php echo base_url(IMAGES_PATH . "/social/facebook.png"); ?>" alt="Facebook" class="lazy" data-original="<?php echo base_url(IMAGES_PATH . "/social/facebook.png"); ?>"/></a></li>
        <li><a href="<?php echo TWITTER_SOCIAL_LINK; ?>" title="Twitter" target="_blank"><img src="<?php echo base_url(IMAGES_PATH . "/social/twitter.png"); ?>" alt="Twitter" class="lazy" data-original="<?php echo base_url(IMAGES_PATH . "/social/twitter.png"); ?>"/></a></li>
        <li><a href="<?php echo PINTEREST_SOCIAL_LINK; ?>" title="Pinterest" target="_blank"><img src="<?php echo base_url(IMAGES_PATH . "/social/pinterest.png"); ?>" alt="Pinterest" class="lazy" data-original="<?php echo base_url(IMAGES_PATH . "/social/pinterest.png"); ?>"/></a></li>
        <li><a href="<?php echo GPLUS_SOCIAL_LINK; ?>" title="Google Plus" target="_blank"><img src="<?php echo base_url(IMAGES_PATH . "/social/gplus.png"); ?>" alt="Google Plus" class="lazy" data-original="<?php echo base_url(IMAGES_PATH . "/social/gplus.png"); ?>"/></a></li>
    </ul>
</div>

<div class="container text-center">
    <p><a href="<?php echo SITE_URL; ?>" target="_blank"><?php echo SITE_NAME; ?></a> is platform for all the travelers and hosts out there 
        who would either want to go out on a vacation but do not have enough travelers within the group or for the people who would love to serve 
        tourists in their city by becoming superb hosts to them. People can also list out their empty guest-house or rooms on <?php echo SITE_NAME; ?> 
        which they would want to be rented out to travelers in their city/town. Either way it is super fun way to meet like-minded people from around the world who 
        are looking for some amazing travel destinations to be out with some fun-loving, friendly or adventurous people. Come, Be an explorer of the world.</p>
</div>