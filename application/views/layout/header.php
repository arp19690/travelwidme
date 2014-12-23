<?php
    if (!isset($meta_title))
        $meta_title = SITE_TITLE;

    if (!isset($meta_keywords))
        $meta_keywords = SEO_KEYWORDS;

    if (!isset($meta_description))
        $meta_description = SEO_DESCRIPTION;

    if (!isset($meta_logo_image))
        $meta_logo_image = IMAGES_PATH . "/logo.jpg";

    $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
    $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
    $this->output->set_header('Pragma: no-cache');

//    prd($meta_logo_image);
?> 

<!DOCTYPE html>
<!--[if lt IE 8]>      <html class="no-js lt-ie10 lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie10 lt-ie9"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

    <head>
        <title><?php echo $meta_title; ?></title>

        <meta charset="utf-8">
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="<?php echo $meta_keywords ?>">
        <meta name="description" content="<?php echo $meta_description ?>">
        <meta name="author" content="<?php echo SITE_NAME; ?>">
        <meta name="robots" content="index, follow">

        <meta property="og:url" content="<?php echo current_url(); ?>"/>
        <meta property="og:title" content="<?php echo $meta_title; ?>"/>
        <meta property="og:description" content="<?php echo $meta_description; ?>"/>
        <meta property="og:image" content="<?php echo $meta_logo_image; ?>"/>

        <script type="text/javascript">
            if (window.location.hash && window.location.hash == '#_=_') {
                window.location.hash = '';
            }
        </script>
        <link rel="shortcut icon" href="<?php echo base_url(IMAGES_PATH . "/favicon.ico"); ?>">
        <link href="<?php echo base_url(CSS_PATH . "/combined.css"); ?>" rel="stylesheet"> 

        <script src="<?php echo base_url(JS_PATH . "/jquery.js"); ?>"></script>
        <script src="<?php echo base_url(JS_PATH . "/functions.js"); ?>"></script>
    </head>

    <body>
        <div id="fb-root"></div>
        <script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=716914558360050";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
        <?php
            echo $this->load->view('layout/navigation.php');
        ?>
        <div class="container outer-wrapper">