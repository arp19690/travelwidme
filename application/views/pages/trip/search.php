<div class="container-fluid">
    <div class="row">
        <h1><?php echo $page_title; ?></h1>

        <div class="container-fluid margin-top-20 clearfix">
            <div class="row">
                <form role="form" class="validate-form search-page-form" action="<?php echo base_url('trip/search'); ?>">
                    <div class='col-lg-3'>
                        <select class='form-control' name='looking_for'>
                            <?php
                                $destination = "";
                                if (isset($_GET["destination"]))
                                    $destination = $_GET["destination"];

                                $looking_for = "travelers";
                                if (isset($_GET["looking_for"]))
                                    $looking_for = $_GET["looking_for"];

                                $host_selected = "";
                                $travel_selected = "";
                                if ($looking_for == "travelers")
                                {
                                    $host_selected = "";
                                    $travel_selected = "selected='selected'";
                                }
                                else
                                {
                                    $host_selected = "selected='selected'";
                                    $travel_selected = "";
                                }
                            ?>
                            <option value='host' <?php echo $host_selected; ?>>Hosts</option>
                            <option value='travel' <?php echo $travel_selected; ?>>Travelers</option>
                        </select>
                    </div>

                    <div class='col-lg-6'>
                        <input type='text' class='form-control gMapLocation required' required="required" name='destination' placeholder="Input your destination" value='<?php echo $destination; ?>'/>
                    </div>

                    <div class='col-lg-1'>
                        <button type='submit' class='btn btn-primary btn-block'><span class='glyphicon glyphicon-send'></span></button>
                    </div>
                </form>
            </div>
        </div>

        <?php
            if (!empty($record))
            {
                ?>
                <div class="container-fluid margin-top-40">
                    <div class="row clearfix">
                        <h1 class="margin-bottom-20">Travellers:</h1>

                        <?php
                        $i = 1;
                        foreach ($record as $key => $value)
                        {
                            $user_full_name = $value["first_name"] . " " . $value["last_name"];
                            $trip_title = $value["trip_title"];

                            $tripHeaderImage = getTripHeaderImage($value["trip_header_image"], $value["trip_images"]);

                            if ($i == 1)
                                echo '<div class="width-100 margin-bottom-20">';
                            ?>
                            <div class="trip-masonry-blocks">
                                <div class="img-like-container">
                                    <img src="<?php echo $tripHeaderImage; ?>" alt="<?php echo $trip_title; ?>" class="img-rounded margin-bottom-20 width-100 trip-header-img lazy" data-original="<?php echo $tripHeaderImage; ?>"/>

                                    <?php
                                    if (isset($this->session->userdata["user_id"]) && $value["trip_user_id"] != @$this->session->userdata["user_id"])
                                    {
                                        $liked = "hide";
                                        $to_like = "";
                                        if ($value["like_user_id"] == $this->session->userdata["user_id"])
                                        {
                                            $liked = "";
                                            $to_like = "hide";
                                        }
                                        ?>
                                        <div class="hover-like-block like-status-block">                            
                                            <a href="#" class="btn btn-default <?php echo $to_like; ?>" id="add" rel="<?php echo base_url('trip/tripToggleLikeAjax/add/' . $value["url_key"]); ?>"><span class="glyphicon glyphicon-heart-empty"></span>&nbsp;&nbsp;Like</a>
                                            <a href="#" onclick="return confirm('Are you sure to unlike?');" class="btn btn-success <?php echo $liked; ?>" id="remove" rel="<?php echo base_url('trip/tripToggleLikeAjax/remove/' . $value["url_key"]); ?>"><span class="glyphicon glyphicon-heart"></span>&nbsp;&nbsp;Liked</a>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>

                                <a href="<?php echo getTripUrl($value["url_key"]); ?>"><h4 class="margin-bottom-20"><?php echo $trip_title; ?></h4></a>
                                <p>By: &nbsp;&nbsp;
                                    <?php
                                    $facebookUserImage = getUserImage($value["trip_user_id"], $value["user_facebook_id"], NULL, 70, 70);
                                    ?>
                                    <span><img src="<?php echo $facebookUserImage; ?>" alt="<?php echo $user_full_name; ?>" class="img-circle trip-bottom-user-img lazy" data-original="<?php echo $facebookUserImage; ?>"/></span>
                                    &nbsp;&nbsp;<a href="<?php echo getPublicProfileUrl($value["username"]); ?>"><span><?php echo $user_full_name; ?></span></a>
                                </p>
                            </div>
                            <?php
                            $i++;
                            if ($i == 5)
                            {
                                $i = 1;
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            else
            {
                echo '<p class="margin-top-20">No results found</p>';
            }
        ?>
    </div>
</div>

<?php
    if (!empty($pagination))
    {
        ?>
        <div class="row margin-top-20">
            <div class="col-lg-12">
                <?php
                echo $pagination;
                ?>
            </div>
        </div>
        <?php
    }
?>
</div>