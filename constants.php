<?php

    if ($_SERVER["REMOTE_ADDR"] == "127.0.0.1")
    {
        define("SITE_BASE_URL", "http://localhost/work/svn/travelwidme/");    // When running locally

        define("FACEBOOK_APP_ID", "716914558360050");
        define("FACEBOOK_SECRET_ID", "21646c23a6a58b0190db4118aedcde18");
        define("FACEBOOK_CALLBACK_URL", "http://localhost/work/svn/travel/index/facebookAuth");
    }
    else
    {
        define("SITE_BASE_URL", "http://travelwid.me");    // When running on server  

        define("FACEBOOK_APP_ID", "240094809514032");
        define("FACEBOOK_SECRET_ID", "5842fb478504500dced4a6d16c142b72");
        define("FACEBOOK_CALLBACK_URL", "http://travelwid.me/index/facebookAuth");
    }

    define("SITE_NAME", "TravelWid.Me");
    define("SITE_TAGLINE", "Exploring the world together");
    define("SITE_TITLE", SITE_NAME . " | " . SITE_TAGLINE);
    define("SITE_EMAIL", "support@travelwid.me");
    define("SITE_EMAIL_GMAIL", "travelwiddotme@gmail.com");
    define("SITE_CONTACT_NUMBER", "+91-987654321");
    define("SITE_URL", "http://travelwid.me");
//    define("SITE_BASE_URL", dirname($_SERVER['PHP_SELF']));
    define("SITE_HOST_URL", "http://" . $_SERVER['HTTP_HOST']);
    define("SITE_HTTP_URL", "http://" . $_SERVER['HTTP_HOST'] . SITE_BASE_URL);

    define("SEO_KEYWORDS", "travel, companion, hosts, travellers, tour, places, friends, strangers, hotels, rooms to stay, verified, explore, world, home, travel wid me");
    define("SEO_DESCRIPTION", "Share your travel experiences. Find a traveler for your dream vacation land. Be an amazing host to tourists in your town. List empty rooms you would want to rent it out to the travelers in your town.");

    define("USER_TIMEOUT_TIME", "1800");
    define("USER_IP", $_SERVER["REMOTE_ADDR"]);
    define("USER_AGENT", $_SERVER["HTTP_USER_AGENT"]);

    define("USE_SALT", "mySaltKey");
    define("ARRAY_SEPARATOR", "|||");

    define('BOOTSTRAP_PATH', 'assets/front/bootstrap');
    define('FONTS_PATH', 'assets/front/fonts');
    define('CSS_PATH', 'assets/front/css');
    define('JS_PATH', 'assets/front/js');
    define('IMAGES_PATH', 'assets/front/images');

    define('NO_IMAGE_PATH', 'assets/front/images/no-image-small.jpg');

    define('USER_IMG_PATH', 'resources/user-images');
    define('USER_IMG_WIDTH', 600);
    define('USER_IMG_HEIGHT', NULL);

    define('ALBUM_IMG_PATH', 'resources/album-images');
    define('ALBUM_IMG_WIDTH', 600);
    define('ALBUM_IMG_HEIGHT', NULL);

    define('TRIP_IMG_PATH', 'resources/trip-images');
    define('TRIP_IMG_WIDTH', 600);
    define('TRIP_IMG_HEIGHT', NULL);

    define('BLOG_IMG_PATH', 'resources/blog-images');
    define('BLOG_IMG_WIDTH', 600);
    define('BLOG_IMG_HEIGHT', NULL);

    define('TRIP_HEADER_IMG_PATH', 'resources/trip-images/header-images');
    define('TRIP_HEADER_IMG_WIDTH', 400);
    define('TRIP_HEADER_IMG_HEIGHT', NULL);

    define("MAX_FACEBOOK_PHOTOS_DISPLAY", 10);

    define("DISQUS_SHORTNAME", "travelwiddotme");
    define("TWITTER_SOCIAL_LINK", "https://twitter.com/TravelWidDotMe");
    define("FACEBOOK_SOCIAL_LINK", "https://www.facebook.com/TravelWid.Me");
    define("PINTEREST_SOCIAL_LINK", "http://www.pinterest.com/TravelWidMe");
    define("GPLUS_SOCIAL_LINK", "https://plus.google.com/114537493148896247812/posts");