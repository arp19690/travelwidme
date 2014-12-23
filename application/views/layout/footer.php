</div>

<div id="footer-upper" class="container-fluid margin-top-40">
    <div class="row">
        <div class="col-lg-4">
            <div class="inline-block vertical-align-top">
                <img src="<?php echo base_url(IMAGES_PATH . "/logo-plane.jpg"); ?>" alt="logo" class="footer-logo-plane"/>
            </div>
            <div class="inline-block">
                <h2><?php echo SITE_NAME; ?></h2>
                <ul class="footer-upper-links margin-top-20">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="<?php echo base_url('login'); ?>">Login</a></li>
                    <li><a href="<?php echo base_url('register'); ?>">Sign Up</a></li>
                    <li><a href="<?php echo base_url('blog'); ?>">Blog</a></li>
                    <li><a href="<?php echo base_url('contact-us'); ?>">Contact us</a></li>
                    <li><a href="<?php echo base_url('how-it-works'); ?>">How it works</a></li>
                    <li><a href="<?php echo base_url('privacy-policy'); ?>">Privacy policy</a></li>
                    <li><a href="<?php echo base_url('terms'); ?>">Terms &amp; conditions</a></li>
                </ul>
            </div>
        </div>

        <div class="col-lg-4">
            <h2>Twitter Feed:</h2>
            <div>
                <a class="twitter-timeline"  href="<?php echo TWITTER_SOCIAL_LINK; ?>"  data-widget-id="459759619611455489">Tweets by @TravelWid.Me</a>
                <script>!function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                        if (!d.getElementById(id)) {
                            js = d.createElement(s);
                            js.id = id;
                            js.src = p + "://platform.twitter.com/widgets.js";
                            fjs.parentNode.insertBefore(js, fjs);
                        }
                    }(document, "script", "twitter-wjs");</script>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="col-lg-1">
                <h2>Facebook:</h2>
                <div>
                    <?php echo getFacebookLikeBox("300", "dark"); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container-fluid">
        <div class="col-lg-12">
            <p class="col-lg-6 col-xs-12 footer-copyright">&copy; Copyright 2014 &middot; All Rights Reserved</p>
            <ul class="col-lg-6 col-xs-12 text-right footer-links">
                <li><a href="<?php echo base_url('blog'); ?>">Blog</a></li>
                <li>|</li>
                <li><a href="<?php echo base_url('contactUs'); ?>">Contact us</a></li>
                <li>|</li>
                <li><a href="<?php echo base_url('how-it-works'); ?>">How it works</a></li>
                <li>|</li>
                <li><a href="<?php echo base_url('privacy-policy'); ?>">Privacy policy</a></li>
                <li>|</li>
                <li><a href="<?php echo base_url('terms'); ?>">Terms &amp; conditions</a></li>
            </ul>
        </div>
    </div>
</footer>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>
<script type='text/javascript' src='<?php echo base_url(JS_PATH . "/custom.js"); ?>'></script>
<script>
                    // to check if unread messages, new connect requests, so that the message icon in the navbar could blink
                    checkNavbarBlinkNotification('<?php echo base_url('user/checkMessageNotificationAjax'); ?>', '#nav-messages');
                    checkNavbarBlinkNotification('<?php echo base_url('user/checkConnectRequestAjax'); ?>', '#nav-connect-requests');
</script>

<!--Google Analytics START-->
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-51020854-1', 'travelwid.me');
    ga('send', 'pageview');

</script>
<!--Google Analytics END-->

</body>
</html>