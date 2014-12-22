<?php
    if (isset($record))
    {
//        prd($record);
        extract($record);
    }
    else
    {
        redirect(base_url());
    }

    $trip_user_fullname = $record["first_name"] . " " . $record["last_name"];
    $login_class = " login-to-continue";
    if (isset($this->session->userdata["user_id"]) && $record["trip_user_id"] != $this->session->userdata["user_id"])
    {
        $login_class = "";

        if ($already_interested == FALSE)
        {
            ?>
            <!-- Modal -->
            <div class="modal fade" id="imInterestedModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">I'm interested in <strong><?php echo $pageTitle; ?></strong></h4>
                        </div>

                        <form action='<?php echo base_url('trip/interestedInTrip'); ?>' method='post' class='validate-form'>
                            <input type="hidden" name="key" value="<?php echo $record["url_key"]; ?>"/>
                            <div class="modal-body">
                                <p>
                                    <label>Message:</label>
                                    <textarea class='form-control required textarea-resize-none' name='interested_message' placeholder="Your message goes here ..."></textarea>
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="btn_submit" class="btn btn-primary">Send</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <?php
        }
        ?>

        <!-- Report Modal -->
        <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Report <strong><?php echo $pageTitle; ?></strong></h4>
                    </div>

                    <form action='<?php echo base_url('trip/reportTrip/' . $record["url_key"]); ?>' method='post' class='validate-form'>
                        <div class="modal-body">
                            <p>
                                <label>Reason:</label>
                                <select name='report_reason' class='form-control required' required='required'>
                                    <?php
                                    $report_Array = array("Fake post", "Adult post", "Scam", "Against law & order", "Hurts religious feelings");
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
        <?php
    }
?>

<div class="container-fluid">
    <div class="row">
        <h1>
            <?php
                $tripHeaderImage = getTripHeaderImage($record["trip_header_image"], $record["trip_images"]);
            ?>
            <img src="<?php echo $tripHeaderImage; ?>" alt="Header image" class="img-rounded header-img-icon lazy" data-original="<?php echo $tripHeaderImage; ?>"/>
            &nbsp;&nbsp;<?php echo $pageTitle; ?>
        </h1>

        <div class="jumbotron view-trip col-lg-12 margin-top-40">

            <div class="container-fluid margin-bottom-20">
                <div class="row pull-right">
                    <?php echo getAddThis(); ?>
                </div>
            </div>

            <?php
                if ($record["trip_user_id"] != @$this->session->userdata["user_id"] && isset($this->session->userdata["user_id"]))
                {
                    $liked = "hide";
                    $to_like = "";
                    if ($record["like_user_id"] == $this->session->userdata["user_id"])
                    {
                        $liked = "";
                        $to_like = "hide";
                    }
                    ?>
                    <div class="container-fluid margin-bottom-40">
                        <div class="row">

                            <div class="col-lg-3 like-status-block">
                                <a href="#" class="btn btn-default <?php echo $to_like; ?>" id="add" rel="<?php echo base_url('trip/tripToggleLikeAjax/add/' . $record["url_key"]); ?>"><span class="glyphicon glyphicon-heart-empty"></span>&nbsp;&nbsp;Like</a>
                                <a href="#" onclick="return confirm('Are you sure to unlike?');" class="btn btn-success <?php echo $liked; ?>" id="remove" rel="<?php echo base_url('trip/tripToggleLikeAjax/remove/' . $record["url_key"]); ?>"><span class="glyphicon glyphicon-heart"></span>&nbsp;&nbsp;Liked</a>
                            </div>

                            <div class='interested-block text-right col-lg-9'>
                                <?php
                                if ($already_interested == FALSE)
                                {
                                    ?>
                                    <a href="#imInterested" data-toggle="modal" data-target="#imInterestedModal" class='btn btn-success  <?php echo $login_class; ?>' title="I'm interested"><span class='glyphicon glyphicon-thumbs-up'></span>&nbsp;I'm interested to join</a>
                                    <?php
                                }
                                ?>
                                <a href='#report' data-toggle="modal" data-target="#reportModal" class='btn btn-danger <?php echo $login_class; ?>' title="Report listing"><span class='glyphicon glyphicon-exclamation-sign'></span></a>
                            </div>
                        </div>
                    </div>
                    <?php
                }

                if (!empty($record["trip_images"]))
                {
                    ?>
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-lg-8">
                                <div class="trip-img-block">
                                    <section class="slider">
                                        <div class="flexslider">
                                            <ul class="slides">
                                                <?php
                                                $explode_images = explode("|||", $record["trip_images"]);
                                                foreach ($explode_images as $key => $value)
                                                {
                                                    $img_src = getTripImages($value);
                                                    ?>
                                                    <li data-thumb="<?php echo $img_src; ?>">
                                                        <img src="<?php echo $img_src; ?>" alt="image" data-original="<?php echo $img_src; ?>"/>
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </section>
                                </div>
                            </div>

                            <div class="col-lg-4 text-center">
                                <p>Posted by:</p>
                                <?php
                                $userImage = getUserImage($record["trip_user_id"], $record["user_facebook_id"], NULL, 180, 180);
                                ?>
                                <img src="<?php echo $userImage; ?>" alt="<?php echo $trip_user_fullname; ?>" class="img-circle margin-bottom-20 lazy trip-view-user-img" data-original="<?php echo $userImage; ?>"/>
                                <a href="<?php echo getPublicProfileUrl($record["username"]); ?>" title="View Profile"><h2 class="margin-bottom-20"><?php echo $trip_user_fullname; ?></h2></a>
                                <p class="font-16"><?php echo $record["user_bio"]; ?></p>
                            </div>

                        </div>
                    </div>
                    <?php
                }
            ?>
            <div class="container-fluid margin-top-40">
                <div class="row">
                    <?php
                        if (!empty($interested_records))
                        {
                            ?>
                            <div class="col-lg-9">
                                <h3>Other people who are interested to join:</h3>
                                <div class="trip-img-block text-center margin-top-20">
                                    <?php
                                    foreach ($interested_records as $ikey => $ivalue)
                                    {
                                        $user_img_src = getUserImage($ivalue["user_id"], $ivalue["user_facebook_id"], NULL, 100, 100);
                                        $user_full_name = $ivalue["first_name"] . " " . $ivalue["last_name"];

                                        echo '<a href="' . getPublicProfileUrl($ivalue["username"]) . '" title="' . $user_full_name . '" target="_blank" class="interested-people"><img src="' . $user_img_src . '" alt="' . $user_full_name . '" class="img-circle lazy" data-original="' . $user_img_src . '"/></a>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    ?>

                    <div class="col-lg-3 margin-top-40">
                        <h3><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;&nbsp;<?php echo number_format($record["trip_likes"]); ?>&nbsp;&nbsp;Likes</h3>
                        <h3 class="margin-top-20"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;&nbsp;<?php echo number_format($record["trip_views"]); ?>&nbsp;&nbsp;Views</h3>
                    </div>
                </div>
            </div>

            <div class="container-fluid margin-top-40">
                <div class="row">

                    <h2>Trip overview:</h2>
                    <p class="trip-description"><?php echo $record["trip_detail"]; ?></p>
                </div>
            </div>

            <!--<h4>Some more details:</h4>-->

            <div class="container-fluid margin-top-40">
                <div class="row">
                    <div class="">
                        <p><strong>Purpose:</strong>&nbsp;<?php echo ucwords($record["trip_purpose"]); ?></p>
                        <p><strong>People:</strong>&nbsp;<?php echo number_format($record["num_members"]); ?></p>
                        <p><strong>Destination:</strong>&nbsp;<?php echo $record["trip_destination"]; ?></p>
                        <p><strong>Duration:</strong>&nbsp;<?php echo date("F d, Y", strtotime($record["from_date"])); ?> <span class="font-12">to</span> <?php echo date("F d, Y", strtotime($record["to_date"])); ?></p>
                        <p><strong>Budget:</strong>&nbsp;$<?php echo $record["avg_budget"]; ?> per person</p>
                        <p><strong>Expectations:</strong>&nbsp;<?php echo $record["trip_expectations"]; ?></p>
                        <p><strong>My hobbies:</strong>&nbsp;<?php echo $record["user_hobbies"]; ?></p>
                        <p><strong>I have been to:</strong>&nbsp;<?php echo $record["previous_places"]; ?></p>
                        <p><strong>Languages I know:</strong>&nbsp;<?php echo $record["user_languages"]; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid margin-top-40">
            <div class="row">
                <!--  ==========  -->
                <!--  = Comments =  -->
                <!--  ==========  -->

                <section id="comments" class="comments-container">
                    <div id="disqus_thread"></div>
                    <script type="text/javascript">
                                    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                                    var disqus_shortname = '<?php echo DISQUS_SHORTNAME; ?>'; // required: replace example with your forum shortname

                                    /* * * DON'T EDIT BELOW THIS LINE * * */
                                    (function() {
                                        var dsq = document.createElement('script');
                                        dsq.type = 'text/javascript';
                                        dsq.async = true;
                                        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                                        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                                    })();
                    </script>
                    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>

                </section>
            </div>
        </div>
    </div>
</div>

<link href="<?php echo base_url(CSS_PATH . "/flexslider.css"); ?>" rel="stylesheet">
<script type='text/javascript' src="<?php echo base_url(JS_PATH . "/jquery.flexslider-min.js"); ?>"></script>
<script>
    $(document).ready(function() {
        $('.flexslider').flexslider({
            animation: "slide",
            directionNav: false,
            start: function(slider) {
                $('section.slider').removeClass('Loading...');
            }
        });
    });
</script>