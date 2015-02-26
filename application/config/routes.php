<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');
    /*
      | -------------------------------------------------------------------------
      | URI ROUTING
      | -------------------------------------------------------------------------
      | This file lets you re-map URI requests to specific controller functions.
      |
      | Typically there is a one-to-one relationship between a URL string
      | and its corresponding controller class/method. The segments in a
      | URL normally follow this pattern:
      |
      |	example.com/class/method/id/
      |
      | In some instances, however, you may want to remap this relationship
      | so that a different class/function is called than the one
      | corresponding to the URL.
      |
      | Please see the user guide for complete details:
      |
      |	http://codeigniter.com/user_guide/general/routing.html
      |
      | -------------------------------------------------------------------------
      | RESERVED ROUTES
      | -------------------------------------------------------------------------
      |
      | There area two reserved routes:
      |
      |	$route['default_controller'] = 'welcome';
      |
      | This route indicates which controller class should be loaded if the
      | URI contains no data. In the above example, the "welcome" class
      | would be loaded.
      |
      |	$route['404_override'] = 'errors/page_missing';
      |
      | This route will tell the Router what URI segments to use if those provided
      | in the URL cannot be matched to a valid route.
      |
     */
//$route['module_name'] = 'admin';
    $route['default_controller'] = "index";
    $route['404_override'] = 'index/pageNotFound';

    $route['home'] = 'index/index';
    $route['contact-us'] = 'staticpage/contact';
    $route['about-us'] = 'staticpage/index/about';
    $route['how-it-works'] = 'staticpage/index/how-it-works';
    $route['privacy-policy'] = 'staticpage/index/privacy';
    $route['terms'] = 'staticpage/index/terms';
    $route['my-blogs'] = 'blog/myBlogs';
    $route['login'] = 'index/login';
    $route['register'] = 'index/signup';
    $route['signup'] = 'index/signup';
    $route['forgot-password'] = 'index/forgotPassword';
    $route['change-password'] = 'user/changePassword';
    $route['activate'] = 'index/activate';

    $route['my-albums'] = 'user/myAlbums';
    $route['albums/(:any)'] = 'user/myAlbums/$1';
    $route['view/album/(:any)'] = 'user/viewAlbum/$1';
    $route['delete/album/(:any)'] = 'user/deleteAlbum/$1';
    $route['view/photo/(:any)'] = 'user/viewPhoto/$1';
    $route['delete/photo/(:any)/(:any)'] = 'user/deletePhotoAjax/$1/$2';
    $route['delete/photo-noax/(:any)'] = 'user/deletePhoto/$1';
    $route['delete/comment/(:any)'] = 'user/deleteCommentAjax/$1';
    $route['upload/album/(:any)'] = 'user/uploadPhotos/$1';
    $route['my-account'] = 'user/myAccount';
    $route['profile/(:any)'] = 'user/viewProfile/$1';
    $route['connect/(:any)'] = 'user/connectWith/$1';

    $route['map-view'] = 'index/map';
    $route['login/facebook'] = 'index/loginwithfacebook';
    $route['logout'] = 'index/logout';

    /* End of file routes.php */
/* Location: ./application/config/routes.php */