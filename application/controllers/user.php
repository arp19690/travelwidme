<?php

    class User extends CI_Controller
    {

        public function __construct()
        {
            parent::__construct();

            $exclude_array = array("viewProfile", "myAlbums", "viewAlbum", "viewPhoto");

            if (!isset($this->session->userdata["user_id"]) && !in_array($this->router->fetch_method(), $exclude_array))
            {
                redirect(base_url());
            }
        }

        public function index()
        {
            $this->myAccount();
        }

        public function myAccount()
        {
            $data = array();
            $model = new Common_model();
            $custom_model = new Custom_model();
            $user_id = $this->session->userdata["user_id"];

            if ($this->input->post())
            {
                $arr = $this->input->post();
//                prd($arr);
                $username = trim($arr["username"]);
                if (isset($arr["btn_submit"]))
                {
                    unset($arr['btn_submit'], $arr['user_email']);

                    $checkUsername = $model->is_exists("user_id", TABLE_USERS, array("username" => $username, "user_id !=" => $user_id));

                    if (empty($checkUsername))
                    {
                        $this->session->set_flashdata("success", "<strong>Success!</strong> Account details updated");
                    }
                    else
                    {
                        unset($arr["username"]);
                        $this->session->set_flashdata("error", "<strong>Oops!</strong> That username is already taken. Please choose another.");
                    }

                    $model->updateData(TABLE_USERS, $arr, array("user_id" => $user_id));
                    @$this->session->set_userdata("first_name", trim($arr["first_name"]));
                    @$this->session->set_userdata("last_name", trim($arr["last_name"]));
                    @$this->session->set_userdata("username", trim($arr["username"]));
                }
                redirect(base_url('my-account'));
            }
            else
            {
                $record = $model->fetchSelectedData("*", TABLE_USERS, array("user_id" => $user_id));

                $data["meta_title"] = ucwords($this->session->userdata["first_name"] . " " . $this->session->userdata["last_name"]) . " | " . SITE_NAME;
                $user_bio = $record[0]["user_bio"];
                if (!empty($user_bio))
                    $data["meta_description"] = getNWordsFromString($user_bio, 22);

                $trips_record = $model->fetchSelectedData("trip_title, url_key", TABLE_TRIPS, array("trip_user_id" => $user_id, "trip_status" => "1"), "trip_id", "DESC");

                $my_connects_record = $custom_model->getMyFriends($user_id, "first_name, last_name, user_facebook_id, username, user_id", "0,8");

                $data["record"] = $record[0];
                $data["trips_record"] = $trips_record;
                $data["my_connects_record"] = $my_connects_record;
                $data["my_connects_totalcount"] = $custom_model->getMyFriendsCount($user_id);

                $this->template->write_view("content", "pages/user/my-account", $data);
                $this->template->render();
            }
        }

        public function myWall()
        {
            $data = array();
            $model = new Common_model();
            $user_id = $this->session->userdata["user_id"];

            $record = $model->fetchSelectedData("*", TABLE_USERS, array("user_id" => $user_id));
            $data["meta_title"] = ucwords($this->session->userdata["first_name"] . " " . $this->session->userdata["last_name"]) . " | " . SITE_NAME;
            $user_bio = $record[0]["user_bio"];
            if (!empty($user_bio))
                $data["meta_description"] = getNWordsFromString($user_bio, 30);

            $data["record"] = $record[0];

            $this->template->write_view("content", "pages/user/my-wall", $data);
            $this->template->render();
        }

        public function viewProfile($username)
        {
            $data = array();
            $model = new Common_model();
            $custom_model = new Custom_model();
            $pageNotFound = FALSE;
            if ($username)
            {
                $record = $model->is_exists("*", TABLE_USERS, array("username" => $username));
                if (!empty($record))
                {
                    $record = $record[0];
                    $pageNotFound = TRUE;

                    $is_friend = FALSE;
                    $is_accepted = "0";
                    if (isset($this->session->userdata["user_id"]))
                    {
//                        $is_friend_record = $model->is_exists("friend_id, is_accepted", TABLE_FRIENDS, array("sent_from" => $this->session->userdata["user_id"], "sent_to" => $record["user_id"]));
                        $is_friend_record = $custom_model->isFriend($this->session->userdata["user_id"], $record["user_id"], "friend_id, is_accepted");
                        if (!empty($is_friend_record))
                        {
                            $is_friend = TRUE;
                            $is_accepted = $is_friend_record[0]["is_accepted"];
                        }
                    }

                    $trips_record = $model->fetchSelectedData("trip_title, url_key", TABLE_TRIPS, array("trip_user_id" => $record["user_id"], "trip_status" => "1"), "trip_id", "DESC", "0,5");
//                    prd($trips_record);

                    $my_connects_record = $custom_model->getMyFriends($record["user_id"], "first_name, last_name, user_facebook_id, username, user_id", "0,8");

                    $data["meta_title"] = ucwords($record["first_name"] . " " . $record["last_name"]) . " | " . SITE_NAME;
                    $data["meta_description"] = getNWordsFromString($record["user_bio"], 30);
                    $data["record"] = $record;
                    $data["is_friend"] = $is_friend;
                    $data["is_accepted"] = $is_accepted;
                    $data["trips_record"] = $trips_record;
                    $data["my_connects_record"] = $my_connects_record;
                    $data["my_connects_totalcount"] = $custom_model->getMyFriendsCount($record["user_id"]);

                    $this->template->write_view("content", "pages/user/view-profile", $data);
                    $this->template->render();
                }
            }

            if ($pageNotFound == FALSE)
            {
                $this->template->write_view("content", "pages/index/page-not-found", $data);
                $this->template->render();
            }
        }

        public function connectWith($user_name)
        {
            if ($user_name && $this->input->get('next'))
            {
                $next_url = $this->input->get('next');
                $model = new Common_model();
                $logged_in_user_id = $this->session->userdata["user_id"];

                $to_user_record = $model->fetchSelectedData("first_name, last_name, user_id, user_email, send_emails", TABLE_USERS, array("username" => $user_name));

                $to_user_id = $to_user_record[0]["user_id"];
                $to_send_emails = $to_user_record[0]["send_emails"];

                if ($logged_in_user_id != $to_user_id)
                {
                    $is_exists = $model->is_exists("friend_id", TABLE_FRIENDS, array("sent_from" => $logged_in_user_id, "sent_to" => $to_user_id));
                    if (empty($is_exists))
                    {
                        $data_array = array(
                            "sent_from" => $logged_in_user_id,
                            "sent_to" => $to_user_id,
                            "from_ipaddress" => USER_IP,
                            "user_agent" => USER_AGENT,
                        );
                        $model->insertData(TABLE_FRIENDS, $data_array);

                        // send notification email to the user who has got this request
                        if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1' && $to_send_emails == '1')
                        {
                            $to_email = $to_user_record[0]["user_email"];
                            $to_full_name = ucwords($to_user_record[0]["first_name"] . " " . $to_user_record[0]["last_name"]);
                            $from_full_name = ucwords($this->session->userdata['first_name'] . " " . $this->session->userdata['last_name']);

                            $this->load->library('EmailTemplates');
                            $emailTemplate = new EmailTemplates();
                            $messageContent = $emailTemplate->newConnectRequestEmail($to_full_name, $from_full_name);
                            $email_model = new Email_model();
                            $email_model->sendMail($to_email, 'New Connect Request | ' . SITE_NAME, $messageContent);
                        }

                        $this->session->set_flashdata("success", "<strong>Success!</strong> Connect request sent successfully");
                    }
                    else
                    {
                        $this->session->set_flashdata("error", "<strong>Oops!</strong> Looks like you have already sent a request earlier");
                    }
                }
                else
                {
                    $this->session->set_flashdata("error", "<strong>Sorry!</strong> You cannot send connect request to yourself");
                }

                if (empty($next_url) || $next_url == NULL)
                {
                    $next_url = base_url('mtAccount');
                }
                redirect($next_url);
            }
            else
            {
                redirect(base_url());
            }
        }

        public function connectRequests()
        {
            $model = new Common_model();
            $user_id = $this->session->userdata["user_id"];
            $model->updateData(TABLE_FRIENDS, array("is_viewed" => "1"), array("sent_to" => $user_id));

            $record = $model->getAllDataFromJoin("*", TABLE_FRIENDS . " as f", array(TABLE_USERS . " as u" => "u.user_id = f.sent_from"), "LEFT", array("sent_from !=" => $user_id, "sent_to" => $user_id, "is_accepted" => "0"), "friend_id", "DESC");
//            prd($record);

            $data["meta_title"] = "Connect Requests | " . SITE_NAME;
            $data["page_title"] = "Connect Requests";
            $data["record"] = $record;

            $this->template->write_view("content", "pages/user/connect-requests", $data);
            $this->template->render();
        }

        public function connectActionAjax($action, $friend_id)
        {
            if ($action && $friend_id)
            {
                if ($action == "accept" || $action == "reject")
                {
                    $model = new Common_model();
                    $sent_to = $this->session->userdata["user_id"];
                    $whereCondArray = array("friend_id" => $friend_id, "sent_to" => $sent_to);

                    if ($action == "reject")
                    {
                        $model->deleteData(TABLE_FRIENDS, $whereCondArray);
                    }
                    elseif ($action == "accept")
                    {
                        $model->updateData(TABLE_FRIENDS, array("is_accepted" => "1"), array("friend_id" => $friend_id, "sent_to" => $sent_to));
                        $friend_message_record = $model->fetchSelectedData("friend_message, request_timestamp, sent_from", TABLE_FRIENDS, array("friend_id" => $friend_id, "sent_to" => $sent_to, "is_accepted" => "1"));
                        $friend_message = $friend_message_record[0]["friend_message"];
                        if (!empty($friend_message))
                        {
                            $request_timestamp = $friend_message_record[0]["request_timestamp"];

                            $insert_message_data_Array = array(
                                "message_content" => addslashes($friend_message),
                                "message_from" => $friend_message_record[0]["sent_from"],
                                "message_to" => $sent_to,
                                "message_read" => "1",
                                "message_timestamp" => $request_timestamp
                            );
                            $model->insertData(TABLE_MESSAGES, $insert_message_data_Array);
                        }
                    }
                    echo 'ok';
                }
            }
        }

        public function checkMessageNotificationAjax()
        {
            $user_id = $this->session->userdata["user_id"];
            $model = new Common_model();
            $record = $model->fetchSelectedData("message_id", TABLE_MESSAGES, array("message_read" => "0", "message_to" => $user_id));
//                prd($record);
            if (!empty($record))
            {
                echo 'ok';
            }
        }

        public function checkConnectRequestAjax()
        {
            $user_id = $this->session->userdata["user_id"];
            $model = new Common_model();
            $record = $model->fetchSelectedData("friend_id", TABLE_FRIENDS, array("is_viewed" => "0", "sent_to" => $user_id));
//                prd($record);
            if (!empty($record))
            {
                echo 'ok';
            }
        }

        public function sendEmails($change_status = NULL)
        {
            if (isset($this->session->userdata["user_id"]))
            {
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();

                if ($change_status == NULL)
                {
                    $record = $model->fetchSelectedData("send_emails", TABLE_USERS, array("user_id" => $user_id));

                    $data["meta_title"] = "Email Preference | " . SITE_NAME;
                    $data["page_title"] = "Email Preference";
                    $data["send_emails"] = $record[0]['send_emails'];

                    $this->template->write_view("content", "pages/user/send-emails", $data);
                    $this->template->render();
                }
                else
                {
                    $model->updateData(TABLE_USERS, array('send_emails' => $change_status), array("user_id" => $user_id));
                    $this->session->set_flashdata("success", "<strong>Success!</strong> Your email preference has been changed successfully");
                    redirect(base_url('user/sendEmails'));
                }
            }
            else
            {
                redirect(base_url());
            }
        }

        public function reportUser($username)
        {
            if ($this->input->post() && $this->session->userdata["user_id"] && $username)
            {
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();
                $arr = $this->input->post();

                $report_reason = addslashes($arr["report_reason"]);
                $report_message = addslashes($arr["report_message"]);

                $getUserRecord = $model->fetchSelectedData('user_id', TABLE_USERS, array('username' => $username));
                if (!empty($getUserRecord))
                {
                    $is_exists = $model->is_exists("ru_id", TABLE_REPORT_USERS, array("from_user_id" => $user_id, "to_user_id" => $getUserRecord[0]["user_id"]));
                    if (empty($is_exists))
                    {
                        $data_array = array(
                            "from_user_id" => $user_id,
                            "to_user_id" => $getUserRecord[0]["user_id"],
                            "report_reason" => $report_reason,
                            "report_message" => $report_message,
                            "report_ipaddress" => USER_IP,
                            "user_agent" => USER_AGENT,
                        );
//                        prd($data_array);
                        $model->insertData(TABLE_REPORT_USERS, $data_array);
                        $this->session->set_flashdata("success", "<strong>Thank you!</strong> Your report has been received and we will look into this matter ASAP.");
                    }
                    else
                    {
                        $this->session->set_flashdata("error", "<strong>Sorry!</strong> You have already reported this user");
                    }
                    redirect(getPublicProfileUrl($username));
                    die;
                }
                else
                {
                    $this->session->set_flashdata("error", "<strong>Sorry!</strong> No such record found");
                    redirect(base_url());
                    die;
                }
            }
            else
            {
                redirect(base_url());
            }
        }

        public function changePassword()
        {
            if (isset($this->session->userdata['user_id']))
            {
                $data = array();
                $model = new Common_model();
                $user_id = $this->session->userdata['user_id'];

                if ($this->input->post())
                {
                    $arr = $this->input->post();
                    $new_password = $arr['new_password'];
                    $confirm_password = $arr['confirm_password'];

                    if (strcmp($new_password, $confirm_password) == 0)
                    {
                        // passwords match
                        $model->updateData(TABLE_USERS, array('user_password' => md5($confirm_password)), array('user_id' => $user_id));
                        $this->session->set_flashdata('success', '<strong>Success!</strong> Your password has been changed');
                        redirect(base_url('change-password'));
                    }
                    else
                    {
                        // passwords do not match
                        $this->session->set_flashdata('error', '<strong>Sorry!</strong> Passwords you have entered does not match');
                        redirect(base_url('change-password'));
                    }
                }

                $data['meta_title'] = 'Change Password | ' . SITE_NAME;
                $this->template->write_view("content", "pages/user/change-password", $data);
                $this->template->render();
            }
            else
            {
                redirect(base_url('forgotPassword'));
            }
        }

        public function changeProfilePicture()
        {
            if (isset($this->session->userdata['user_id']) && !empty($_FILES) && $_FILES['user_img']['size'] > 0 && $_FILES['user_img']['error'] == 0)
            {
                $user_id = $this->session->userdata['user_id'];

                $source = $_FILES['user_img']['tmp_name'];
                $destination = USER_IMG_PATH . "/" . getEncryptedString($user_id) . ".jpg";

                @unlink($destination);

                $this->load->library('SimpleImage');
                $simpleImage = new SimpleImage();
                $simpleImage->uploadImage($source, $destination, USER_IMG_WIDTH, USER_IMG_HEIGHT);

                $this->session->set_flashdata('success', '<strong>Success!</strong> Your profile picture has been changed');
                redirect(base_url('my-account'));
            }
            else
            {
                redirect(base_url('login'));
            }
        }

        public function removeProfilePicture()
        {
            if (isset($this->session->userdata['user_id']))
            {
                $user_id = $this->session->userdata['user_id'];
                $destination = USER_IMG_PATH . "/" . getEncryptedString($user_id) . ".jpg";
                @unlink($destination);
                $this->session->set_flashdata('success', '<strong>Success!</strong> The profile picture you uplaoded has been removed');
                redirect(base_url('my-account'));
            }
            else
            {
                redirect(base_url('login'));
            }
        }

        public function addFacebookConnection()
        {
            if (isset($this->session->userdata['user_id']))
            {
                $user_id = $this->session->userdata['user_id'];
                $this->load->library('SocialLib');
                $socialLib = new SocialLib();
                $socialLib->connectWithFacebook($user_id);
                redirect(base_url('my-account'));
            }
            else
            {
                redirect(base_url('login'));
            }
        }

        public function removeFacebookConnection()
        {
            if (isset($this->session->userdata['user_id']))
            {
                $user_id = $this->session->userdata['user_id'];
                $model = new Common_model();

                $model->updateData(TABLE_USERS, array('user_facebook_id' => '', 'user_facebook_username' => '', 'user_facebook_photos' => ''), array('user_id' => $user_id));

                $this->session->set_flashdata('success', '<strong>Success!</strong> Your facebook connection has been removed');
                redirect(base_url('my-account'));
            }
            else
            {
                redirect(base_url('login'));
            }
        }

        public function myAlbums($username = NULL)
        {
            $data = array();
            $model = new Common_model();
            $custom_model = new Custom_model();

            if ($username == NULL)
            {
                $user_id = $this->session->userdata['user_id'];
                $page_title = "My Albums";
            }
            else
            {
                $user_name_records = $model->fetchSelectedData("user_id, first_name, last_name", TABLE_USERS, array('username' => $username));
                if (empty($user_name_records))
                {
                    $data['meta_title'] = 'Page Not Found | ' . SITE_NAME;
                    $this->template->write_view("content", "pages/index/page-not-found", $data);
                    $this->template->render();
                }
                else
                {
                    $user_id = $user_name_records[0]['user_id'];
                    $page_title = ucwords($user_name_records[0]['first_name'] . " " . $user_name_records[0]["last_name"]) . "'s Albums";
                }
            }

            if (!empty($user_id))
            {
                $max_records = 8;
                $page = 1;
                if ($this->input->get('page'))
                {
                    $page = $this->input->get('page');
                }
                $paginationLimit = getPaginationLimit($page, $max_records);

                $record = $custom_model->getAllAlbums($user_id, $paginationLimit);
//                prd($record);

                $total_records = $model->getTotalCount('album_id', TABLE_ALBUMS, array('user_id' => $user_id));
//                prd($total_records);

                $pagination = getPaginationLinks(current_url(), $total_records[0]['totalcount'], $page, $max_records);

                $data['user_id'] = $user_id;
                $data['record'] = $record;
                $data['pagination'] = $pagination;
                $data['page_title'] = $page_title;
                $data['meta_title'] = $page_title . ' | ' . SITE_NAME;

                $this->template->write_view("content", "pages/user/my-albums", $data);
                $this->template->render();
            }
        }

        public function createAlbum()
        {
            if (isset($this->session->userdata['user_id']) && $this->input->post())
            {
                $user_id = $this->session->userdata['user_id'];
                $model = new Common_model();
                $arr = $this->input->post();

                $is_album_name_exists = $model->is_exists('album_id', TABLE_ALBUMS, array('album_name' => addslashes($arr['album_name']), 'user_id' => $user_id));
                if (empty($is_album_name_exists))
                {
                    // valid
                    $album_key = getUniqueAlbumURLKey();
                    $data_array = array(
                        'user_id' => $user_id,
                        'username' => $this->session->userdata['username'],
                        'album_name' => substr(addslashes($arr['album_name']), 0, 200),
                        'album_description' => substr(addslashes($arr['album_description']), 0, 200),
                        'album_privacy' => $arr['album_privacy'],
                        'album_key' => $album_key,
                        'album_ipaddress' => USER_IP,
                        'user_agent' => USER_AGENT,
                    );
                    $model->insertData(TABLE_ALBUMS, $data_array);
                    $this->session->set_flashdata('success', '<strong>Success!</strong> New album created.');
                    redirect(base_url('view/album/' . $album_key));
                }
                else
                {
                    // invalid
                    $this->session->set_flashdata('error', '<strong>Oops!</strong> Album name already exists.');
                }
                redirect(base_url('my-albums'));
            }
            else
            {
                redirect(base_url('login'));
            }
        }

        public function uploadPhotos($album_key)
        {
            if (isset($this->session->userdata['user_id']) && $album_key)
            {
                $data = array();
                $user_id = $this->session->userdata['user_id'];
                $model = new Common_model();

                if (isset($_FILES['album_images']['size']) && !empty($_FILES['album_images']['size']))
                {
//                    prd($_FILES);
                    $album_record = $model->fetchSelectedData('album_id', TABLE_ALBUMS, array('album_key' => $album_key));
                    $this->load->library('SimpleImage');
                    $simpleImage = new SimpleImage();

                    $str = "";
                    $i = 1;
                    foreach ($_FILES['album_images']['size'] as $iKey => $iValue)
                    {
                        $fileExt = getFileExtension($_FILES['album_images']['name'][$iKey]);
                        if ($fileExt == 'png' || $fileExt == 'jpg' || $fileExt == 'jpeg' || $fileExt == 'gif')
                        {
                            $random_key = $i . "-" . substr(time(), -2, 2) . "-" . substr($i . getEncryptedString((time() + $i) . USER_IP . $_FILES['album_images']['name'][$iKey]), -8, 8) . "-" . $album_key;
                            $fileName = $random_key . ".jpg";
                            $source = $_FILES['album_images']['tmp_name'][$iKey];
                            $destination = ALBUM_IMG_PATH . "/" . $fileName;

                            // deleting if any such records in db
                            $model->deleteData(TABLE_PHOTOS, array('user_id' => $user_id, 'image_name' => $fileName));
                            @unlink($destination);

                            // uploading image here
                            $simpleImage->uploadImage($source, $destination, ALBUM_IMG_WIDTH, ALBUM_IMG_HEIGHT);    // uploading images over here                            
                            $simpleImage->textWatermark('www.travelwid.me', $destination);      // watermarking the image over here
                            $i++;

                            // updating the database with new details
                            $data_array = array(
                                'photo_name' => $_FILES['album_images']['name'][$iKey],
                                'album_id' => $album_record[0]['album_id'],
                                'album_key' => $album_key,
                                'user_id' => $user_id,
                                'username' => $this->session->userdata['username'],
                                'image_name' => $random_key,
                                'photo_ipaddress' => USER_IP,
                                'user_agent' => USER_AGENT,
                            );
                            $model->insertData(TABLE_PHOTOS, $data_array);

                            $str .= '<li class="row-fluid">
                                            <div class="col-lg-3 col-sm-3">
                                                <img src="' . getAlbumImageUrl($random_key) . '" alt="image" class="img-rounded width-100"/>
                                            </div>
                                            <div class="col-lg-9 col-sm-9">
                                                <input type="hidden" name="image_name[]" value="' . $fileName . '"/>
                                                <p><input type="text" maxlength="200" name="photo_name[]" placeholder="Photo Name" class="form-control" value="' . $_FILES['album_images']['name'][$iKey] . '"/></p>
                                                <p><textarea rows="2" maxlength="200" name="photo_description[]" class="form-control textarea-resize-none" placeholder="Photo Description"></textarea></p>
                                            </div>
                                        </li>';
                        }
                        else
                        {
                            $str = '<li class="row-fluid"><div class="col-lg-12 text-center"><p>Please select a valid file.</p></div></li>';
                        }
                    }
                    echo json_encode($str);
                }
                else
                {
                    $data['album_key'] = $album_key;
                    $data['page_title'] = "Upload Photos";
                    $data['meta_title'] = 'Upload Photos | ' . SITE_NAME;
                    $this->template->write_view("content", "pages/user/upload-photos", $data);
                    $this->template->render();
                }
            }
            else
            {
                redirect(base_url('login'));
            }
        }

        public function afterUploadSaveAllAjax()
        {
            if ($this->input->post())
            {
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();
                $arr = $this->input->post();
//                prd($arr);

                foreach ($arr['image_name'] as $key => $value)
                {
                    if (!empty($value))
                    {
                        $image_name = str_replace('.jpg', '', $value);
                        $photo_name = addslashes($arr['photo_name'][$key]);
                        $photo_description = addslashes($arr['photo_description'][$key]);

                        $data_array = array(
                            'photo_name' => $photo_name,
                            'photo_description' => $photo_description,
                        );
//                        prd($data_array);
//                        prd(array('user_id' => $user_id, 'image_name' => $image_name));

                        $model->updateData(TABLE_PHOTOS, $data_array, array('user_id' => $user_id, 'image_name' => $image_name));
                    }
                }
                echo 'ok';
            }
        }

        public function viewAlbum($album_key)
        {
            if ($album_key)
            {
                $data = array();
                $model = new Common_model();
                $custom_model = new Custom_model();

                $max_records = 12;
                $page = 1;
                if ($this->input->get('page'))
                {
                    $page = $this->input->get('page');
                }
                $paginationLimit = getPaginationLimit($page, $max_records);

                $record = $custom_model->getAllPhotosOnAlbum($album_key, $paginationLimit);
                if (empty($record))
                {
                    $album_record = $model->fetchSelectedData('user_id, album_name, album_description, album_key', TABLE_ALBUMS, array('album_key' => $album_key));
                    $owner_id = $album_record[0]['user_id'];
                    $album_name = $album_record[0]['album_name'];
                    $album_description = $album_record[0]['album_description'];
                }
                else
                {
                    $owner_id = $record[0]['user_id'];
                    $album_name = $record[0]['album_name'];
                    $album_description = $record[0]['album_description'];
                }
//                prd($record);
                $total_records = $model->getTotalCount('photo_id', TABLE_PHOTOS, array('album_key' => $album_key));
//                prd($total_records);

                $pagination = getPaginationLinks(current_url(), $total_records[0]['totalcount'], $page, $max_records);

                $data['record'] = $record;
                $data['pagination'] = $pagination;
                $data['user_id'] = $owner_id;
                $data['page_title'] = stripslashes($album_name);
                $data['meta_title'] = stripslashes($album_name) . ' | ' . SITE_NAME;
                $data['album_key'] = stripslashes($album_key);
                $data['album_description'] = stripslashes($album_description);
                $this->template->write_view("content", "pages/user/view-album", $data);
                $this->template->render();
            }
        }

        public function viewPhoto($album_key, $photo_id)
        {
            if ($album_key && $photo_id)
            {
                $data = array();
                $model = new Common_model();
                $custom_model = new Custom_model();

                $fields = 'CONCAT_WS(" ", first_name, last_name ) as comment_user_fullname, pc_id, pc_timestamp, u.username, user_comment, user_facebook_id, u.user_id, ph.image_name, photo_name, photo_description, ph.album_key, pld.user_id as pld_user_id, like_dislike';
                $record = $custom_model->getPhotosAndComments($album_key, $photo_id, $fields);
//                prd($record);
                if (!empty($record))
                {
                    $getTotalLikesAndDislikes = $custom_model->getTotalLikesAndDislikes($record[0]['image_name']);
                    $user_record = $model->fetchSelectedData('CONCAT_WS(" ", first_name, last_name ) as full_name, username', TABLE_USERS, array('user_id' => $record[0]['user_id']));
                    $all_album_images_record = $model->fetchSelectedData('photo_id, album_key', TABLE_PHOTOS, array('album_key' => $album_key));
//                    prd($all_album_images_record);

                    $photo_pagination_links = getPhotoPaginationLinks($all_album_images_record, $photo_id);

                    $data['record'] = $record;
                    $data['totalLikesAndDislikes'] = $getTotalLikesAndDislikes;
                    $data['photo_pagination'] = $photo_pagination_links;
                    $data['page_title'] = stripslashes($user_record[0]['full_name']) . "'s Photos";
                    $data['meta_title'] = $data['page_title'] . ' | ' . SITE_NAME;
                    $data['owner_username'] = $user_record[0]['username'];
                    $data['photo_description'] = stripslashes($record[0]['photo_description']);
                    $this->template->write_view("content", "pages/user/view-photo", $data);
                    $this->template->render();
                }
                else
                {
                    $this->template->write_view("content", "pages/index/page-not-found", $data);
                    $this->template->render();
                }
            }
        }

        public function addCommentAjax()
        {
            if ($this->input->post())
            {
                $model = new Common_model();
                $arr = $this->input->post();
                $image_name = $arr["image_name"];

                $is_valid = $model->is_exists('photo_id, album_key', TABLE_PHOTOS, array('image_name' => $image_name));
                if (!empty($is_valid))
                {
                    $photo_id = $is_valid[0]['photo_id'];
                    $album_key = $is_valid[0]['album_key'];
                    $user_id = $this->session->userdata["user_id"];
                    $user_comment = addslashes($arr['comment_text']);

                    $data_array = array(
                        'photo_id' => $photo_id,
                        'album_key' => $album_key,
                        'user_id' => $user_id,
                        'image_name' => $image_name,
                        'user_comment' => $user_comment,
                        'pc_ipaddress' => USER_IP,
                        'user_agent' => USER_AGENT,
                    );
                    $model->insertData(TABLE_PHOTO_COMMENTS, $data_array);
                    $pc_id = $this->db->insert_id();

                    $random_id = $pc_id . substr(time(), -3, 3) . "-" . substr(time(), -3, 3);
                    $str = '<li id="li_' . $random_id . '">
                                <div class="user-image">
                                    <img src="' . getUserImage($user_id, $this->session->userdata['user_facebook_id'], NULL, 100, 100) . '" alt="You" class="width-100 img-rounded"/>
                                </div>
                                <div class="comment-details">
                                    <div class="user-name-and-time">
                                        <a href="#" class="user-name">You</a>

                                        <a href="#" title="Remove" class="font-12 black remove-comment-link" rel="' . base_url('delete/comment/' . $pc_id) . '" id="li_' . $random_id . '"><span class="glyphicon glyphicon-remove"></span></a>

                                        <p class="time-ago">Just now</p>
                                    </div>

                                    <div class="user-comment">
                                        <p class="comment-text">' . nl2br(stripslashes($user_comment)) . '</p>
                                    </div>
                                </div>
                            </li>';

                    echo $str;
                }
            }
        }

        public function likeDislikePhotoAjax($image_name, $like_dislike)
        {
            if ($image_name && ($like_dislike == '1' || $like_dislike == '0'))
            {
                $model = new Common_model();
                $user_id = $this->session->userdata["user_id"];
                $is_exists = $model->is_exists('pld_id, image_name', TABLE_PHOTO_LIKES_DISLIKES, array('user_id' => $user_id, 'image_name' => $image_name, 'like_dislike' => $like_dislike));
//                prd($is_exists);
                if (empty($is_exists))
                {
                    $album_key_records = $model->fetchSelectedData('album_key', TABLE_PHOTOS, array('image_name' => $is_exists[0]['image_name']));
                    $album_key = $album_key_records[0]['album_key'];
                    $data_array = array(
                        'user_id' => $user_id,
                        'image_name' => $image_name,
                        'album_key' => $album_key,
                        'like_dislike' => $like_dislike,
                        'pld_ipaddress' => USER_IP,
                        'user_agent' => USER_AGENT
                    );
                    $model->insertData(TABLE_PHOTO_LIKES_DISLIKES, $data_array);
                    echo 'ok';
                }
                else
                {
                    if ($like_dislike == '1')
                        $text = "liked";
                    if ($like_dislike == '0')
                        $text = "disliked";
                    echo 'You have already ' . $text . ' this picture';
                }
                die;
            }
        }

        public function deleteAlbum($album_key)
        {
            if ($album_key)
            {
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();
                $whereCondArr = array('a.album_key' => $album_key, 'a.user_id' => $user_id);
                $record = $model->getAllDataFromJoin('a.album_id, image_name', TABLE_ALBUMS . " as a", array(TABLE_PHOTOS . " as ph" => "ph.album_key = a.album_key"), 'INNER', $whereCondArr);
//                prd($record);
                if (!empty($record))
                {
                    $album_id = $record[0]['album_id'];
                    $model->deleteData(TABLE_ALBUMS, array('album_key' => $album_key, 'user_id' => $user_id, 'album_id' => $album_id));
                    $model->deleteData(TABLE_PHOTOS, array('album_key' => $album_key));
                    $model->deleteData(TABLE_PHOTO_COMMENTS, array('album_key' => $album_key));
                    $model->deleteData(TABLE_PHOTO_LIKES_DISLIKES, array('album_key' => $album_key));

                    foreach ($record as $key => $value)
                    {
                        $filename = ALBUM_IMG_PATH . "/" . $value['image_name'] . ".jpg";
                        @unlink($filename);
                    }

                    $this->session->set_flashdata('success', '<strong>Success!</strong> Your album has been removed successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', '<strong>Sorry!</strong> You are not authorized to perform this action');
                }
                redirect(base_url('my-albums'));
            }
        }

        public function deletePhoto($album_key)
        {
            if ($album_key)
            {
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();

                if ($this->input->get('image'))
                {
                    $image_name = $this->input->get('image');
                }

                $whereCondArr = array('album_key' => $album_key, 'user_id' => $user_id, 'image_name' => $image_name);
                $model->deleteData(TABLE_PHOTOS, $whereCondArr);
                $model->deleteData(TABLE_PHOTO_COMMENTS, $whereCondArr);
                $model->deleteData(TABLE_PHOTO_LIKES_DISLIKES, array('album_key' => $album_key, 'image_name' => $image_name));

                $file_name = ALBUM_IMG_PATH . "/" . $image_name . ".jpg";
                @unlink($file_name);

                $this->session->set_flashdata('success', '<strong>Success!</strong> Your photo has been removed');
                redirect(base_url('view/album/' . $album_key));
            }
        }

        public function deletePhotoAjax($album_key, $image_name)
        {
            if ($image_name && $album_key)
            {
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();

                $whereCondArr = array('album_key' => $album_key, 'user_id' => $user_id, 'image_name' => $image_name);
                $model->deleteData(TABLE_PHOTOS, $whereCondArr);
                $model->deleteData(TABLE_PHOTO_COMMENTS, $whereCondArr);
                $model->deleteData(TABLE_PHOTO_LIKES_DISLIKES, array('album_key' => $album_key, 'image_name' => $image_name));

                $file_name = ALBUM_IMG_PATH . "/" . $image_name . ".jpg";
                @unlink($file_name);
                echo 'ok';
            }
        }

        public function deleteCommentAjax($comment_id)
        {
            if ($comment_id)
            {
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();
                $model->deleteData(TABLE_PHOTO_COMMENTS, array('user_id' => $user_id, 'pc_id' => $comment_id));
                echo 'ok';
            }
        }

        public function editImageDetails()
        {
            if ($this->input->post())
            {
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();
                $arr = $this->input->post();

                $next_url = $arr['next'];
                $image_name = $arr['image_name'];
                $photo_name = addslashes($arr['photo_name']);
                $photo_description = addslashes($arr['photo_description']);

                $data_array = array(
                    'photo_name' => $photo_name,
                    'photo_description' => $photo_description,
                );

                $model->updateData(TABLE_PHOTOS, $data_array, array('image_name' => $image_name, 'user_id' => $user_id));
                $this->session->set_flashdata('success', '<strong>Success!</strong> Your photo details have been updated.');
                redirect($next_url);
            }
        }

    }
    