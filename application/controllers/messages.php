<?php

    class Messages extends CI_Controller
    {

        public function __construct()
        {
            parent::__construct();

            if (!isset($this->session->userdata["user_id"]))
            {
                redirect(base_url());
            }
        }

        public function index()
        {
            $this->inbox();
        }

        public function inbox()
        {
            $data = array();
            $user_id = $this->session->userdata["user_id"];
            $model = new Common_model();
            $custom_model = new Custom_model();
            $data["record"] = $custom_model->getInboxList($user_id);

            $data["page_title"] = "Messages";
            $data["active_class"] = "inbox";
            $this->template->write_view("content", "pages/messages/list", $data);
            $this->template->render();
        }

        public function outbox()
        {
            redirect(base_url('messages/inbox'));

            $data = array();
            $user_id = $this->session->userdata["user_id"];
            $model = new Common_model();
            $custom_model = new Custom_model();
            $data["record"] = $custom_model->getOutboxList($user_id);

            $data["page_title"] = "Outbox";
            $data["active_class"] = "outbox";
            $this->template->write_view("content", "pages/messages/list", $data);
            $this->template->render();
        }

        public function thread($username)
        {
            if ($username)
            {
                $data = array();
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();
                $custom_model = new Custom_model();

                $is_username_valid = $model->is_exists('user_id', TABLE_USERS, array('username' => $username));
                if (!empty($is_username_valid))
                {
                    $message_from=$is_username_valid[0]['user_id'];
                    $model->updateData(TABLE_MESSAGES, array("message_read" => "1"), array("message_from" => $message_from, "message_to" => $user_id));

                    $getUserNameRecord = $model->fetchSelectedData("first_name,last_name,username", TABLE_USERS, array("user_id" => $message_from));
                    $full_name = $getUserNameRecord[0]["first_name"] . " " . $getUserNameRecord[0]["last_name"];

                    $allThreadMessages = $custom_model->getThreadMessages($user_id, $message_from);
//                prd($allThreadMessages);

                    $data["meta_title"] = "Messages | " . $full_name . " | " . SITE_NAME;
                    $data["page_title"] = "Chat with <a href='" . $getUserNameRecord[0]["username"] . "'>" . $full_name . "</a>";
                    $data["message_to"] = $message_from;
                    $data["record"] = $allThreadMessages;
                    $this->template->write_view("content", "pages/messages/thread", $data);
                    $this->template->render();
                }
                else
                {
                    redirect(base_url('my-account'));
                }
            }
            else
            {
                redirect(base_url('messages'));
            }
        }

        public function sendMessageAjax()
        {
            if (isset($this->session->userdata["user_id"]) && $this->input->post())
            {
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();
                $custom_model = new Custom_model();
                $arr = $this->input->post();

                $areFriends = $custom_model->areFriends($user_id, $arr["message_to"]);
                if ($areFriends == TRUE)
                {
                    $data_array = array(
                        "message_from" => $user_id,
                        "message_to" => $arr["message_to"],
                        "message_content" => addslashes($arr["message_content"]),
                        "from_ipaddress" => USER_IP,
                    );
                    $model->insertData(TABLE_MESSAGES, $data_array);

                    $str = '<li class="">
                                <div class="col-lg-1 col-xs-4 nopadding-left">
                                    <img src="' . getUserImage($this->session->userdata["user_id"], $this->session->userdata["user_facebook_id"], NULL, 60, 60) . '" alt="image" class="img-rounded lazy message-thread-img"/>
                                </div>
                                <div class="col-lg-11 col-xs-8 message-info nopadding-left">
                                    <p class="user-name">' . $this->session->userdata["first_name"] . " " . $this->session->userdata["last_name"] . '</p>
                                    <p class="time text-right">Just now</p>

                                    <p class="clearfix">' . $arr["message_content"] . '</p>
                                </div>
                            </li>';
                    echo $str;
                }
            }
        }

        public function getUnreadChatsAjax($other_user_id)
        {
            if (isset($this->session->userdata["user_id"]) && $other_user_id)
            {
                $user_id = $this->session->userdata["user_id"];
                $custom_model = new Custom_model();
                $chat_records = $custom_model->getUnreadChatsAjax($user_id, $other_user_id);

                $str = "";
                if (!empty($chat_records))
                {
                    foreach ($chat_records as $key => $value)
                    {
                        $str .= '<li class="">
                                <div class="col-lg-1 col-xs-4 nopadding-left">
                                    <img src="' . getUserImage($value["user_id"], $value["user_facebook_id"], NULL, 60, 60) . '" alt="image" class="img-rounded lazy message-thread-img"/>
                                </div>
                                <div class="col-lg-11 col-xs-8 message-info nopadding-left">
                                    <p class="user-name">' . $value["first_name"] . " " . $value["last_name"] . '</p>
                                    <p class="time text-right">' . getTimeAgo(strtotime($value["message_timestamp"])) . '</p>

                                    <p class="clearfix">' . $value["message_content"] . '</p>
                                </div>
                            </li>';
                    }
                }
                echo $str;
            }
        }

    }