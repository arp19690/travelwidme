<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class SocialLib
    {

        public function __construct()
        {
            $this->ci = & get_instance();
            $this->ci->load->database();
            $this->ci->load->model('Common_model');
        }

        public function getFacebookLoginUrl($appId = FACEBOOK_APP_ID, $secretId = FACEBOOK_SECRET_ID, $redirect_uri = FACEBOOK_CALLBACK_URL)
        {
            require_once APPPATH . "../assets/front/social/facebook/facebook.php";

// Create our Application instance (replace this with your appId and secret).
            $facebook = new Facebook(array(
                'appId' => $appId,
                'secret' => $secretId,
            ));

            $loginUrl = $facebook->getLoginUrl(array(
                "redirect_uri" => $redirect_uri,
                "scope" => "email, user_photos, user_birthday, user_about_me",
            ));

            return $loginUrl;
        }

        public function getFacebookLogoutUrl($appId = FACEBOOK_APP_ID, $secretId = FACEBOOK_SECRET_ID)
        {
            require_once APPPATH . "../assets/front/social/facebook/facebook.php";

// Create our Application instance (replace this with your appId and secret).
            $facebook = new Facebook(array(
                'appId' => $appId,
                'secret' => $secretId,
            ));

            $logoutUrl = $facebook->getLogoutUrl();

            return $logoutUrl;
        }

        public function loginWithFacebook()
        {
            require_once APPPATH . "../assets/front/social/facebook/facebook.php";

            // Create our Application instance (replace this with your appId and secret).
            $facebook = new Facebook(array(
                'appId' => FACEBOOK_APP_ID,
                'secret' => FACEBOOK_SECRET_ID,
            ));

            $loginSuccess = FALSE;

            // Get User ID
            $user = $facebook->getUser();

            // We may or may not have this data based on whether the user is logged in.
            //
            // If we have a $user id here, it means we know the user is logged into
            // Facebook, but we don't know if the access token is valid. An access
            // token is invalid if the user logged out of Facebook.

            if ($user)
            {
                try
                {
                    // Proceed knowing you have a logged in user who's authenticated.
                    $user_profile = $facebook->api('/me');
//                    prd($user_profile);

                    $model = new Common_model();
                    if (isset($user_profile["username"]))
                    {
                        $facebook_username = $user_profile["username"];
                    }
                    else
                    {
                        $facebook_email = explode('@', $user_profile["email"]);
                        $date = date('d', time());
                        $hour = date('h', time());
                        $time_last_two = substr(time(), -3, 3);
                        $facebook_username = $facebook_email[0] . $date . $hour . $time_last_two;

                        $is_username_exists = $model->is_exists('user_id', TABLE_USERS, array("username" => $facebook_username));
                        if (empty($is_username_exists))
                        {
                            $facebook_username = $facebook_username . substr(getEncryptedString($facebook_username), -6, 3);
                        }
                    }

                    $returnArray = array();

                    $fields = "user_id,first_name,last_name,user_email,user_facebook_id,user_facebook_username";
                    $is_email_exists = $model->is_exists($fields, TABLE_USERS, array("user_email" => $user_profile["email"]));
                    if (empty($is_email_exists))
                    {
                        $is_exists = $model->is_exists($fields, TABLE_USERS, array("user_facebook_id" => $user_profile["id"]));
                        if (!empty($is_exists))
                        {
                            // login here
                            $session_data_array = array(
                                "user_id" => $is_exists[0]["user_id"],
                                "first_name" => $is_exists[0]["first_name"],
                                "last_name" => $is_exists[0]["last_name"],
                                "username" => $is_exists[0]["username"],
                                "user_email" => $is_exists[0]["user_email"],
                            );

                            $returnArray["session_array"] = $session_data_array;
                        }
                        else
                        {
                            // register here
                            $insert_data_array = array(
                                "first_name" => $user_profile["first_name"],
                                "last_name" => $user_profile["last_name"],
                                "user_gender" => $user_profile["gender"],
                                "username" => $facebook_username,
                                "what_do" => @$user_profile["work"][0]["position"]["name"],
                                "user_email" => $user_profile["email"],
                                "user_bio" => @$user_profile["bio"],
                                "user_status" => "1",
                                "user_facebook_id" => $user_profile["id"],
                                "user_facebook_username" => $facebook_username,
                                "user_facebook_array" => json_encode($user_profile),
                                "user_ipaddress" => USER_IP,
                                "user_agent" => USER_AGENT,
                            );

                            if (isset($user_profile["hometown"]["name"]))
                            {
                                $insert_data_array["user_location"] = $user_profile["hometown"]["name"];
                            }

                            if (isset($user_profile["birthday"]))
                            {
                                $insert_data_array["user_birthday"] = str_replace("/", "-", $user_profile["birthday"]);
                            }

                            $model->insertData(TABLE_USERS, $insert_data_array);
                            $user_record = $model->getMaxId('user_id', TABLE_USERS);
                            $user_id = $user_record[0]['maxid'];

                            $loginSuccess = TRUE;

                            $session_data_array = array(
                                "user_id" => $user_record[0]['maxid'],
                                "first_name" => $user_profile["first_name"],
                                "last_name" => $user_profile["last_name"],
                                "username" => $facebook_username,
                                "user_email" => $user_profile["email"],
                                "user_facebook_id" => $user_profile["id"],
                                "user_session_expire_time" => time() + USER_TIMEOUT_TIME,
                            );

                            // to insert the email address into the newsletters table
                            $model->insertData(TABLE_NEWSLETTER, array('user_email' => $user_profile["email"], "user_ipaddress" => USER_IP, "user_agent" => USER_AGENT));

                            $returnArray["flash_type"] = "success";
                            $returnArray["flash_message"] = "<strong>Welcome!</strong> Please complete all your account details.";
                            $returnArray["return_url"] = "myAccount";
                            $returnArray["session_array"] = $session_data_array;
                        }
                    }
                    else
                    {
                        // update here    
                        $has_facebook_id = $is_email_exists[0]["user_facebook_id"];

                        $update_data_array = array(
                            "user_location" => @$user_profile["hometown"]["name"],
                            "username" => $facebook_username,
                            "user_status" => "1",
                            "user_facebook_id" => $user_profile["id"],
                            "user_facebook_username" => $facebook_username,
                        );
                        $model->updateData(TABLE_USERS, $update_data_array, array("user_email" => $is_email_exists[0]["user_email"]));
                        $user_id = $is_email_exists[0]['user_id'];

                        $loginSuccess = TRUE;

                        $session_data_array = array(
                            "user_id" => $is_email_exists[0]["user_id"],
                            "first_name" => $is_email_exists[0]["first_name"],
                            "last_name" => $is_email_exists[0]["last_name"],
                            "username" => $facebook_username,
                            "user_email" => $is_email_exists[0]["user_email"],
                            "user_facebook_id" => $user_profile["id"],
                        );

                        if (empty($has_facebook_id))
                        {
                            $returnArray["flash_type"] = "success";
                            $returnArray["flash_message"] = "<strong>Success!</strong>";
                        }

                        $returnArray["session_array"] = $session_data_array;
                    }
                }
                catch (FacebookApiException $e)
                {
                    error_log($e);
                    $user = null;
                }
            }
            else
            {
                $returnArray["flash_type"] = "error";
                $returnArray["flash_message"] = "<strong>Sorry!</strong> An error occured while authenticating. Please try again.";
                $returnArray["session_array"] = array();
            }

            // to update the user photos from facebook
            if ($loginSuccess == true)
            {
                $albumjson = $facebook->api('/me?fields=photos');
//                prd($albumjson);

                if (isset($albumjson["photos"]) && !empty($albumjson["photos"]))
                {
                    $output = array();
                    foreach ($albumjson["photos"]["data"] as $key => $value)
                    {
                        $output[] = array("large" => $value["source"], "small" => $value["images"][6]["source"]);
                    }
                    $model->updateData(TABLE_USERS, array("user_facebook_photos" => json_encode($output)), array("user_id" => $user_id));
                }
            }

            $redirect_url = base_url();
            if (isset($this->ci->session->userdata["next_url"]))
            {
                $redirect_url = $this->ci->session->userdata["next_url"];
                @$this->ci->session->unset_userdata('next_url');
            }

            if (isset($returnArray["flash_type"]) && isset($returnArray["flash_message"]))
            {
                @$this->ci->session->set_flashdata($returnArray["flash_type"], $returnArray["flash_message"]);
            }

            if (isset($returnArray["session_array"]) && !empty($returnArray["session_array"]))
            {
                $returnArray["session_array"]["user_session_expire_time"] = time() + USER_TIMEOUT_TIME;

                // insert and entry into the user_log table
                $data_array = array(
                    "ul_user_id" => $returnArray["session_array"]["user_id"],
                    "ul_login_time" => time(),
                    "ul_login_type" => 'facebook',
                    "ul_useragent" => $this->ci->session->userdata["user_agent"],
                    "ul_login_ipaddress" => $this->ci->session->userdata["ip_address"],
                );
                $model->insertData(TABLE_USER_LOG, $data_array);

                // setting up session here
                foreach ($returnArray["session_array"] as $sKey => $sValue)
                {
                    $this->ci->session->set_userdata($sKey, $sValue);
                }

//                prd($returnArray["session_array"]["user_id"]);
            }

            if (isset($returnArray["return_url"]) && !empty($returnArray["return_url"]))
            {
                $redirect_url = $returnArray["return_url"];
            }

            redirect($redirect_url);
        }

        public function connectWithFacebook($user_id = NULL)
        {
            require_once APPPATH . "../assets/front/social/facebook/facebook.php";

            // Create our Application instance (replace this with your appId and secret).
            $facebook = new Facebook(array(
                'appId' => FACEBOOK_APP_ID,
                'secret' => FACEBOOK_SECRET_ID,
            ));

            // Get User ID
            $user = $facebook->getUser();

            // We may or may not have this data based on whether the user is logged in.
            //
            // If we have a $user id here, it means we know the user is logged into
            // Facebook, but we don't know if the access token is valid. An access
            // token is invalid if the user logged out of Facebook.

            if ($user)
            {
                try
                {
                    // Proceed knowing you have a logged in user who's authenticated.
                    $user_profile = $facebook->api('/me');
//                    prd($user_profile);

                    $model = new Common_model();
                    if ($user_id == NULL)
                        $user_id = $this->ci->session->userdata['user_id'];

                    $is_facebook_id_exists = $model->is_exists('user_id', TABLE_USERS, array('user_facebook_id' => $user_profile["id"]));
                    if (empty($is_facebook_id_exists))
                    {
                        // update here    

                        $update_data_array = array(
                            "user_facebook_id" => $user_profile["id"],
                            "user_facebook_username" => $facebook_username,
                            "user_facebook_array" => json_encode($user_profile),
                        );

                        // to update the user photos from facebook
                        $albumjson = $facebook->api('/me?fields=photos');
//                prd($albumjson);

                        $output = array();
                        foreach ($albumjson["photos"]["data"] as $key => $value)
                        {
                            $output[] = array("large" => $value["source"], "small" => $value["images"][6]["source"]);
                        }
                        $update_data_array["user_facebook_photos"] = json_encode($output);

                        // update all the data
                        $model->updateData(TABLE_USERS, $update_data_array, array("user_id" => $user_id));
                        $this->ci->session->set_flashdata('success', '<strong>Success!</strong> Your facebook account has been connected.');
                    }
                    else
                    {
                        // facebook id already exists
                        $this->ci->session->set_flashdata('error', '<strong>Sorry!</strong> This facebook account is already connected to another account.');
                    }
                }
                catch (FacebookApiException $e)
                {
                    error_log($e);
                    $user = null;
                }
            }
            else
            {
                $this->ci->session->set_flashdata('error', '<strong>Sorry!</strong> An error occured while authenticating. Please try again.');
            }
            return TRUE;
        }

    }