<?php
    $controller = $this->router->fetch_class();
    $action = $this->router->fetch_method();
    $path = $controller . "/" . $action;
?>

<!-- Static navbar -->
<div class="navbar navbar-default navbar-fixed-top menu-bar margin-bottom-40" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url(); ?>" title="<?php echo SITE_NAME; ?>"><span class="glyphicon glyphicon-send"></span>&nbsp;&nbsp;<?php echo SITE_NAME; ?></a>
        </div>

        <div class="navbar-collapse collapse">

            <?php
                if ($path != 'index/index')
                {
                    $dest_value = "";
                    if (isset($_GET["destination"]))
                        $dest_value = trim($_GET["destination"]);
                    ?>
                    <form class="navbar-form navbar-left" role="form" action="<?php echo base_url('trip/search'); ?>">
                        <div class="input-group">
                            <input class="form-control gMapLocation" type="text" name="destination" placeholder="Input destination" value="<?php echo $dest_value; ?>"/>
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-send"></span></button>
                            </span>
                        </div>
                    </form>
                    <?php
                }
            ?>

            <ul class="nav navbar-nav navbar-right">
                <li id="home"><a href="<?php echo base_url(); ?>" title="Home"><span class="glyphicon glyphicon-home"></span><span class="hidden-lg hidden-md hidden-sm">&nbsp;&nbsp;Home</span></a></li>
                <!--<li><a rel="nofollow" href="<?php echo base_url('map-view'); ?>" title="Map View"><span class="glyphicon glyphicon-map-marker"></span><span class="hidden-lg hidden-md hidden-sm">&nbsp;&nbsp;Map View</span></a></li>-->
                <?php
                    if (isset($this->session->userdata["user_id"]))
                    {
                        ?>
                                                                                                                                                                                                        <!--<li><a href="<?php echo base_url("user/myWall"); ?>" title="Wall"><span class="glyphicon glyphicon-th-large"></span></a></li>-->
                        <li><a href="<?php echo base_url("messages"); ?>" title="Messages" id='nav-messages'><span class="glyphicon glyphicon-comment"></span><span class="hidden-lg hidden-md hidden-sm">&nbsp;&nbsp;Messages</span></a></li>
                        <li><a href="<?php echo base_url("user/connectRequests"); ?>" title="Connect Requests" id='nav-connect-requests'><span class="glyphicon glyphicon-link"></span><span class="hidden-lg hidden-md hidden-sm">&nbsp;&nbsp;Connect Requests</span></a></li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle nav-user" data-toggle="dropdown">
                                <img src="<?php echo getUserImage($this->session->userdata["user_id"], $this->session->userdata["user_facebook_id"], NULL, 20, 20); ?>" alt="image" class="header-profile-img img-circle noborder"/>
                                <?php echo $this->session->userdata["first_name"] . " " . $this->session->userdata["last_name"]; ?> <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo base_url('trip/plan'); ?>"><span class="glyphicon glyphicon-briefcase"></span>&nbsp;&nbsp;Add New Trip</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo base_url('trip/myTrips'); ?>"><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;My Trips</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo base_url('my-blogs'); ?>"><span class="glyphicon glyphicon-file"></span>&nbsp;&nbsp;My Blogs</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo base_url('my-albums'); ?>"><span class="glyphicon glyphicon-picture"></span>&nbsp;&nbsp;My Albums</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo base_url('my-account'); ?>"><span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;My Account</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo base_url('change-password'); ?>"><span class="glyphicon glyphicon-hdd"></span>&nbsp;&nbsp;Change Password</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo base_url('logout'); ?>"><span class="glyphicon glyphicon-off"></span>&nbsp;&nbsp;Logout</a></li>
                            </ul>
                        </li>
                        <?php
                    }
                    else
                    {
                        $login_url = base_url('login');
                        if ($path != 'index/index')
                        {
                            $login_url = base_url("login?next=" . current_url());
                        }
                        ?>
                        <li><a href="<?php echo base_url("how-it-works"); ?>" title="How it works?">How it works?</a></li>
                        <li><a href="<?php echo base_url("register"); ?>" title="Sign Up">Sign Up</a></li>
                        <li><a href="<?php echo $login_url; ?>" title="Login">Login</a></li>
                        <?php
                    }
                ?>

            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>

<!--this is the p with height 90 to keep distance between fixed navbar and the middle container-->
<p class="top-height-90"></p>

<?php
    if (isset($this->session->userdata["user_id"]) && $path == "index/index")
    {
        ?>
        <div id="late-night" class="col-lg-12 col-md-12 col-xs-12 text-center margin-bottom-20 hide">
            <h2><span class="glyphicon glyphicon-time"></span>&nbsp;&nbsp;Hey <?php echo ucwords($this->session->userdata["first_name"]); ?>, we love these late hours too! ;)</h2>
        </div>
        <div id="early-morning" class="col-lg-12 col-md-12 col-xs-12 text-center margin-bottom-20 hide">
            <h2><span class="glyphicon glyphicon-time"></span>&nbsp;&nbsp;Good morning <?php echo ucwords($this->session->userdata["first_name"]); ?>, let's go for a jog?</h2>
        </div>
        <?php
    }
    else
    {
        ?>
        <div id='fbLoginWait' class='hide text-center margin-bottom-20'>
            <p>Please wait, while we log you in...<img src="<?php echo base_url(IMAGES_PATH . "/loading-blue.gif"); ?>" alt="loading" class="loading-blue-gif"/></p>
        </div>
        <?php
    }

    if ($this->session->flashdata("success"))
    {
        ?>
        <div class="container">
            <div class="row-fluid">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $this->session->flashdata("success"); ?>
                </div>
            </div>
        </div>
        <?php
    }

    if ($this->session->flashdata("error"))
    {
        ?>
        <div class="container">
            <div class="row-fluid">
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $this->session->flashdata("error"); ?>
                </div>
            </div>
        </div>
        <?php
    }
?>