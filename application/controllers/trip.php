<?php

    class Trip extends CI_Controller
    {

        public function __construct()
        {
            parent::__construct();

            $exclude_array = array("view", "search");

            if (!isset($this->session->userdata["user_id"]) && !in_array($this->router->fetch_method(), $exclude_array))
            {
                redirect(base_url());
            }
        }

        public function index()
        {
            $this->plan();
        }

        public function myTrips()
        {
            $data = array();
            $user_id = $this->session->userdata["user_id"];
            $model = new Common_model();
            $records = $model->fetchSelectedData("*", TABLE_TRIPS, array("trip_user_id" => $user_id), "trip_id", "DESC");

            $data["record"] = $records;

            $data["page_title"] = "My Trips";
            $data['meta_title'] = 'My Trips | ' . SITE_NAME;
            $this->template->write_view("content", "pages/trip/list", $data);
            $this->template->render();
        }

        public function remove($url_key)
        {
            $model = new Common_model();
            $record = $model->fetchSelectedData("*", TABLE_TRIPS, array("url_key" => $url_key));
            $explode_images = explode(ARRAY_SEPARATOR, $record[0]["trip_images"]);
            foreach ($explode_images as $key => $value)
            {
                @unlink(TRIP_IMG_PATH . "/" . $value);
            }
            @unlink(TRIP_HEADER_IMG_PATH . "/" . $record[0]["trip_header_image"]);

            $model->deleteData(TABLE_TRIPS, array("url_key" => $url_key));
            $model->deleteData(TABLE_INTERESTED, array("trip_id" => $record[0]["trip_id"]));

            $this->session->set_flashdata("success", "<strong>Success!</strong> Your trip listing has been successfully removed");
            redirect(base_url('trip/myTrips'));
        }

        public function plan($edit = NULL, $url_key = NULL)
        {
            $data = array();
            $model = new Common_model();
            $user_id = $this->session->userdata["user_id"];

            if ($edit == "edit" && $url_key != NULL)
            {
                $record = $model->fetchSelectedData("*", TABLE_TRIPS, array("url_key" => $url_key));
                if (empty($record))
                {
                    $this->session->set_flashdata("error", "<strong>Sorry!</strong> No such record found");
                    redirect(base_url('trip/myTrips'));
                }
                else
                {
                    $data["record"] = $record[0];
                    $data["meta_title"] = "Edit Trip | " . SITE_NAME;

                    $this->template->write_view("content", "pages/trip/plan", $data);
                    $this->template->render();
                }
            }
            elseif ($this->input->post())
            {
                $arr = $this->input->post();
                if (isset($arr["btn_submit"]))
                {

                    $url_key = $arr["key"];
                    unset($arr['btn_submit'], $arr['key']);
//                    prd($arr);

                    $this->load->library("SimpleImage");
                    $simpleImage = new SimpleImage();

//                    prd($_FILES);
                    if (!empty($_FILES["trip_img"]))
                    {
                        $img_name_array = array();

                        // to remove older images
                        if (!empty($url_key) && !empty($_FILES["trip_img"]["name"][0]))
                        {
                            $image_records = $model->fetchSelectedData("trip_images", TABLE_TRIPS, array("url_key" => $url_key));
                            $explode_images = explode(ARRAY_SEPARATOR, $image_records[0]["trip_images"]);
                            foreach ($explode_images as $key => $value)
                            {
                                @unlink(TRIP_IMG_PATH . "/" . $value);
                            }
                        }

                        foreach ($_FILES["trip_img"]['size'] as $imageSizeKey => $imageSizeValue)
                        {
                            if ($imageSizeValue > 0 && $_FILES["trip_img"]['error'][$imageSizeKey] == 0)
                            {
                                $image_name = getEncryptedString($_FILES["trip_img"]["name"][$imageSizeKey] . time()) . ".jpg";
                                $img_name_array[] = $image_name;

                                $source = $_FILES["trip_img"]["tmp_name"][$imageSizeKey];
                                $destination = TRIP_IMG_PATH . "/" . $image_name;
                                $simpleImage->uploadImage($source, $destination, TRIP_IMG_WIDTH, TRIP_IMG_HEIGHT);
                            }
                        }

                        if (!empty($img_name_array))
                            $arr["trip_images"] = implode(ARRAY_SEPARATOR, $img_name_array);
                    }

                    // to upload trip header image
                    if (!empty($_FILES["trip_header_img"]) && isset($_FILES["trip_header_img"]))
                    {
                        if ($_FILES["trip_header_img"]["size"] > 0 && $_FILES["trip_header_img"]["error"] == 0)
                        {

                            // to remove older header image
                            $trip_header_image_record = $model->fetchSelectedData("trip_header_image", TABLE_TRIPS, array("url_key" => $url_key));
                            @unlink(TRIP_HEADER_IMG_PATH . "/" . $trip_header_image_record[0]["trip_header_image"]);

                            // to upload the new image
                            $header_image_name = getEncryptedString($_FILES["trip_header_img"]["name"] . time()) . ".jpg";
                            $source = $_FILES["trip_header_img"]["tmp_name"];
                            $destination = TRIP_HEADER_IMG_PATH . "/" . $header_image_name;
                            $simpleImage->uploadImage($source, $destination, TRIP_HEADER_IMG_WIDTH, TRIP_HEADER_IMG_HEIGHT);
                            $arr["trip_header_image"] = $header_image_name;
                        }
                    }

                    $arr["avg_budget"] = number_format(str_replace(",", "", $arr["avg_budget"]));
                    $arr["from_date"] = str_replace("/", "-", $arr["from_date"]);
                    $arr["to_date"] = str_replace("/", "-", $arr["to_date"]);

                    $arr["trip_user_id"] = $user_id;
                    $arr["trip_ipaddress"] = USER_IP;
                    $arr["user_agent"] = USER_AGENT;

                    $meta_keywords = $arr["trip_destination"] . "," . $arr["trip_detail"];
                    $meta_keywords = str_replace(" ", ",", $meta_keywords);
                    $meta_keywords = getNWordsFromString($meta_keywords, 22);

                    $arr["meta_keywords"] = $meta_keywords;
                    $arr["meta_description"] = getNWordsFromString($arr["trip_detail"], 40);

                    if (empty($url_key))
                    {
                        $model->insertData(TABLE_TRIPS, $arr);
                        $trip_id = $this->db->insert_id();

                        $url_key = str_replace(" ", "-", $arr["trip_title"] . "-" . $trip_id);
                        $model->updateData(TABLE_TRIPS, array("url_key" => $url_key), array("trip_id" => $trip_id));
                    }
                    else
                    {
                        $model->updateData(TABLE_TRIPS, $arr, array("url_key" => $url_key));
                        $trip_record = $model->fetchSelectedData("trip_id", TABLE_TRIPS, array("url_key" => $url_key));
                        $trip_id = $trip_record[0]["trip_id"];
                    }

                    // to update the latitude and longitude of the trip destination
                    // $this->updateLatLon($trip_id, $arr["trip_destination"]);

                    $this->session->set_flashdata("success", "<strong>Success!</strong> Your plan has been posted successfully. Share with your friends.");
                    redirect('trip/view/' . $url_key);
                }
                redirect('my-account');
            }
            else
            {
                $data["meta_title"] = "Add New Post | " . SITE_NAME;

                $this->template->write_view("content", "pages/trip/plan", $data);
                $this->template->render();
            }
        }

        public function view($url_key)
        {
            $data = array();
            $model = new Common_model();

//            $record = $model->fetchSelectedData("*", TABLE_TRIPS, array("url_key" => $url_key, "trip_status" => "1"));
            $join_cond = "t.trip_id = tl.trip_id";
            if (isset($this->session->userdata["user_id"]))
                $join_cond = "t.trip_id = tl.trip_id AND tl.user_id = " . $this->session->userdata["user_id"];
            $record = $model->getAllDataFromJoin("*, t.trip_id as trip_id, tl.user_id as like_user_id", TABLE_TRIPS . " as t", array(TABLE_TRIP_LIKES . " as tl" => $join_cond), "LEFT", array("url_key" => $url_key, "trip_status" => "1"));
//            prd($record);

            if (!empty($record))
            {
                $record = $record[0];

                $user_record = $model->fetchSelectedData("first_name, last_name, username, user_bio, user_facebook_id", TABLE_USERS, array("user_id" => $record["trip_user_id"]));
                foreach ($user_record[0] as $key => $value)
                {
                    $record[$key] = $value;
                }

                $already_interested = FALSE;
                if (isset($this->session->userdata["user_id"]))
                {
                    $user_id = $this->session->userdata["user_id"];
                    $already_interested_record = $model->is_exists("interested_id", TABLE_INTERESTED, array("sent_from" => $user_id, "trip_id" => $record["trip_id"]));
                    if (!empty($already_interested_record))
                    {
                        $already_interested = TRUE;
                    }
                }

                $interested_records = $model->getAllDataFromJoin("first_name, last_name, user_facebook_id, username, user_id", TABLE_INTERESTED . " as i", array(TABLE_USERS . " as u" => "u.user_id = i.sent_from"), "INNER", array("i.trip_id" => $record["trip_id"]));
//                prd($interested_records);
                // increase the trip view count by one
                if (USER_IP != '127.0.0.1')
                {
                    $model->incrementByCertainNumber("trip_views", TABLE_TRIPS, array("url_key" => $url_key), "1");
                }

                $data["record"] = $record;
                $data["already_interested"] = $already_interested;
                $data["interested_records"] = $interested_records;
                $data["pageTitle"] = $record["trip_title"];
                $data["meta_title"] = $record["trip_title"] . " | " . SITE_NAME;
                $data["meta_keywords"] = $record["meta_keywords"];
                $data["meta_description"] = $record["meta_description"];

                $this->template->write_view("content", "pages/trip/view", $data);
                $this->template->render();
            }
            else
            {
                redirect(base_url());
            }
        }

        public function search()
        {
            $data = array();
//            if ($this->input->get('destination') && $this->input->get())
            if ($this->input->get())
            {
//                prd($this->input->get());

                $looking_for = $this->input->get('looking_for');

                $destination = NULL;
                if ($this->input->get('destination'))
                    $destination = $this->input->get('destination');

                $view_looking_for = "Travelers";
                $search_string = "travel";
                if ($looking_for == "host")
                {
                    $view_looking_for = "Hosts";
                    $search_string = "host";
                }

                $custom_model = new Custom_model();

                $max_records = 20;
                $page = 1;
                if ($this->input->get('page'))
                {
                    $page = $this->input->get('page');
                }
                $paginationLimit = getPaginationLimit($page, $max_records);

                $fields = "url_key, trip_user_id, trip_title, trip_header_image, trip_images, first_name, last_name, username, user_facebook_id";
                $record = $custom_model->getSearchResults($search_string, $destination, $paginationLimit, $fields);
                $total_records = $custom_model->getSearchResultsTotalCount($search_string, $destination);
//                prd($total_records);

                $pagination = getPaginationLinks(current_url(), $total_records, $page, $max_records);
//                prd($pagination);

                $page_title = "Search Results for <strong>" . ucwords($view_looking_for) . "</strong>";
                if (!empty($destination))
                {
                    $page_title = "Search Results for <strong>" . ucwords($view_looking_for) . "</strong> - <strong>" . $destination . "</strong>";
                }

                $data["record"] = $record;
                $data["pagination"] = $pagination;
                $data["page_title"] = $page_title;
                $data["meta_title"] = "Search Results | " . SITE_NAME;

                $this->template->write_view("content", "pages/trip/search", $data);
                $this->template->render();
            }
            else
            {
                $this->session->set_flashdata("error", "<strong>Sorry!</strong> Please input a destination");
                redirect(base_url());
            }
        }

        public function interestedInTrip()
        {
            if ($this->input->post() && $this->session->userdata["user_id"])
            {
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();
                $arr = $this->input->post();
//                prd($arr);
                if (isset($arr["btn_submit"]))
                {
                    unset($arr["btn_submit"]);
                    $url_key = $arr["key"];
                    $post_record = $model->fetchSelectedData("trip_id, trip_user_id", TABLE_TRIPS, array("url_key" => $url_key));
//                    prd($post_record);
                    if (empty($post_record))
                    {
                        $this->session->set_flashdata("error", "<strong>Sorry!</strong> No such record found");
                        redirect(base_url());
                        die;
                    }
                    else
                    {
                        $trip_id = $post_record[0]["trip_id"];
                        $other_user_id = $post_record[0]["trip_user_id"];

                        $is_exists = $model->is_exists("interested_id", TABLE_INTERESTED, array("sent_from" => $user_id, "sent_to" => $other_user_id, "trip_id" => $trip_id));
                        if (empty($is_exists))
                        {
                            $data_array = array(
                                "sent_from" => $user_id,
                                "sent_to" => $other_user_id,
                                "trip_id" => $trip_id,
                                "interested_message" => addslashes($arr["interested_message"]),
                                "interested_ipaddress" => USER_IP,
                                "user_agent" => USER_AGENT,
                            );
                            $model->insertData(TABLE_INTERESTED, $data_array);

                            // send a notification email to the user, who's trip has someone interested in
                            if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1')
                            {
                                $to_user_record = $model->fetchSelectedData("first_name, last_name, user_id, user_email, send_emails", TABLE_USERS, array('user_id' => $other_user_id));
                                $to_email = $to_user_record[0]["user_email"];
                                $to_send_emails = $to_user_record[0]["send_emails"];
                                $to_full_name = ucwords($to_user_record[0]["first_name"] . " " . $to_user_record[0]["last_name"]);
                                $from_full_name = ucwords($this->session->userdata['first_name'] . " " . $this->session->userdata['last_name']);

                                if ($to_send_emails == '1')
                                {
                                    $this->load->library('EmailTemplates');
                                    $emailTemplate = new EmailTemplates();
                                    $messageContent = $emailTemplate->newPersonInterestedInMyTripEmail($to_full_name, $from_full_name);
                                    $email_model = new Email_model();
                                    $email_model->sendMail($to_email, 'New Person Interested in your Trip | ' . SITE_NAME, $messageContent);
                                }
                            }

                            $this->session->set_flashdata("success", "<strong>Success!</strong> Your interest has been sent to the user.");
                            redirect(getTripUrl($url_key));
                            die;
                        }
                        else
                        {
                            $this->session->set_flashdata("error", "<strong>Sorry!</strong> Your interest has already been sent to the user.");
                            redirect(getTripUrl($url_key));
                            die;
                        }
                    }
                }
                else
                {
                    redirect(base_url());
                }
            }
            else
            {
                redirect(base_url());
            }
        }

        public function reportTrip($url_key)
        {
            if ($this->input->post() && $this->session->userdata["user_id"] && $url_key)
            {
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();
                $arr = $this->input->post();

                $report_reason = addslashes($arr["report_reason"]);
                $report_message = addslashes($arr["report_message"]);

                $trip_records = $model->fetchSelectedData("trip_id", TABLE_TRIPS, array("url_key" => $url_key));
                if (!empty($trip_records))
                {
                    $is_exists = $model->is_exists("report_id", TABLE_REPORT, array("from_user_id" => $user_id, "trip_id" => $trip_records[0]["trip_id"]));
                    if (empty($is_exists))
                    {
                        $data_array = array(
                            "from_user_id" => $user_id,
                            "trip_id" => $trip_records[0]["trip_id"],
                            "report_reason" => $report_reason,
                            "report_message" => $report_message,
                            "report_ipaddress" => USER_IP,
                            "user_agent" => USER_AGENT,
                        );
                        $model->insertData(TABLE_REPORT, $data_array);
                        $this->session->set_flashdata("success", "<strong>Thank you!</strong> Your report has been received and we will look into this matter ASAP.");
                    }
                    else
                    {
                        $this->session->set_flashdata("error", "<strong>Sorry!</strong> You have already reported this post");
                    }
                    redirect(getTripUrl($url_key));
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

        public function updateLatLon($trip_id, $trip_destination)
        {
            $model = new Common_model();
            $arr = array();

            $geoAddress = trim($trip_destination);
            $getLatLong = getLatLonByAddress($geoAddress);
//            prd($getLatLong);
            $arr["latitude"] = $getLatLong["latitude"];
            $arr["longitude"] = $getLatLong["longitude"];
            $model->updateData(TABLE_USERS, $arr, array("trip_id" => $trip_id));
        }

        public function tripToggleLikeAjax($action, $url_key)
        {
            if ($url_key && $action && isset($this->session->userdata["user_id"]))
            {
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();
                $is_my_event = $model->is_exists("trip_id", TABLE_TRIPS, array("url_key" => $url_key, "trip_user_id" => $user_id));
//                prd($is_my_event);
                if (empty($is_my_event))
                {
                    $getTripIdRecord = $model->fetchSelectedData("trip_id", TABLE_TRIPS, array("url_key" => $url_key));
                    $trip_id = $getTripIdRecord[0]["trip_id"];
                    $is_exists = $model->is_exists("like_id", TABLE_TRIP_LIKES, array("trip_id" => $trip_id, "user_id" => $user_id));
                    if ($action == "add")
                    {
                        if (empty($is_exists))
                        {
                            $model->incrementByCertainNumber("trip_likes", TABLE_TRIPS, array("url_key" => $url_key, "trip_user_id !=" => $user_id), "1", "+");

                            $data_array = array(
                                "trip_id" => $trip_id,
                                "user_id" => $user_id,
                                "like_ipaddress" => USER_IP,
                                "user_agent" => USER_AGENT,
                            );
                            $model->insertData(TABLE_TRIP_LIKES, $data_array);

                            echo 'ok';
                        }
                    }
                    elseif ($action == "remove")
                    {
                        if (!empty($is_exists))
                        {
                            $model->incrementByCertainNumber("trip_likes", TABLE_TRIPS, array("url_key" => $url_key, "trip_user_id !=" => $user_id), "1", "-");
                            $model->deleteData(TABLE_TRIP_LIKES, array("trip_id" => $trip_id, "user_id" => $user_id));
                            echo 'ok';
                        }
                    }
                }
            }
        }

        public function interestedPeople($url_key)
        {
            if ($this->session->userdata["user_id"] && $url_key)
            {
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();
                $is_valid_trip = $model->fetchSelectedData('trip_id, trip_title', TABLE_TRIPS, array('url_key' => $url_key, 'trip_user_id' => $user_id));
                if (!empty($is_valid_trip))
                {
                    // valid
                    $trip_id = $is_valid_trip[0]['trip_id'];

                    $max_records = 15;
                    $page = 1;
                    if ($this->input->get('page'))
                    {
                        $page = $this->input->get('page');
                    }
                    $paginationLimit = getPaginationLimit($page, $max_records);

                    $records = $model->getAllDataFromJoin("user_id, first_name, last_name, user_facebook_id, username, interested_message", TABLE_INTERESTED . " as i", array(TABLE_USERS . " as u" => "u.user_id = i.sent_from"), 'INNER', array("i.trip_id" => $trip_id), 'interested_id', 'DESC', $paginationLimit);
                    $total_records = $model->getTotalCount('interested_id', TABLE_INTERESTED, array("trip_id" => $trip_id));

                    $pagination = getPaginationLinks(current_url(), $total_records[0]['totalcount'], $page, $max_records);

                    $data["record"] = $records;
                    $data["pagination"] = $pagination;
                    $data["page_title"] = "Interested People in <strong>" . $is_valid_trip[0]['trip_title'] . "</strong>";
                    $data["meta_title"] = "Interested People | " . SITE_NAME;

                    $this->template->write_view("content", "pages/trip/interested-people", $data);
                    $this->template->render();
                }
                else
                {
                    // not valid
                    $this->session->set_flashdata('error', '<strong>Sorry!</strong> No valid records found');
                    redirect(base_url('trip/myTrips'));
                }
            }
            else
            {
                redirect(base_url());
            }
        }

    }
    