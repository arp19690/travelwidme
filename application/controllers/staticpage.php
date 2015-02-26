<?php

    class Staticpage extends CI_Controller
    {

        public function __construct()
        {
            parent::__construct();
        }

        public function index($pageName = 'about')
        {
            $data = array();

            $viewFile = "about";

            switch ($pageName)
            {
                case "about":
                    {
                        $viewFile = "about";
                        $data["meta_title"] = "About Us | " . SITE_NAME;
                        break;
                    }
                case "how-it-works":
                    {
                        $viewFile = "how-it-works";
                        $data["meta_title"] = "How it works | " . SITE_NAME;
                        break;
                    }
                case "privacy":
                    {
                        $viewFile = "privacy";
                        $data["meta_title"] = "Privacy Policy | " . SITE_NAME;
                        break;
                    }
                case "terms":
                    {
                        $viewFile = "terms";
                        $data["meta_title"] = "Terms &amp; Conditions | " . SITE_NAME;
                        break;
                    }
            }

            $this->template->write_view("content", "pages/staticpage/" . $viewFile, $data);
            $this->template->render();
        }

        public function contact()
        {
            if ($this->input->post())
            {
                $arr = $this->input->post();
//                prd($arr);

                if (isset($arr["btn_submit"]))
                {
                    $data = array();
                    $captcha_answer_system = $this->session->userdata["captcha_contact_answer_system"];
                    $captcha_answer_user = $arr["captcha_answer_user"];
                    unset($arr["btn_submit"], $arr["captcha_answer_user"], $this->session->userdata["captcha_contact_answer_system"]);

                    if ($captcha_answer_system != $captcha_answer_user)
                    {
                        // wrong captcha
                        $this->session->set_flashdata('error', 'Please input correct answer');
                        $this->session->set_flashdata('post', $arr);
                        redirect(base_url('contact-us'));
                    }
                    else
                    {
                        $model = new Common_model();
                        $request_id = strtoupper(substr(getEncryptedString($arr['user_email'] . USER_IP . time()), 0, 8));

                        $is_request_id_exists = $model->is_exists('wc_id, wc_ipaddress', TABLE_WEBSITE_CONTACT, array('wc_request_id' => $request_id));
                        if (!empty($is_request_id_exists))
                        {
                            $request_id = substr(getEncryptedString($is_request_id_exists[0]['wc_id'] . $is_request_id_exists[0]['wc_ipaddress'] . $arr['user_email'] . USER_IP . time()), 0, 8);
                        }

                        $arr["wc_request_id"] = strtoupper($request_id);
                        $arr["wc_ipaddress"] = $this->session->userdata["ip_address"];
                        $arr["user_agent"] = $this->session->userdata["user_agent"];
//                        prd($arr);

                        $model->insertData(TABLE_WEBSITE_CONTACT, $arr);

                        if ($_SERVER["REMOTE_ADDR"] != '127.0.0.1')
                        {
                            require_once APPPATH . '/models/email_model.php';
                            $email_model = new Email_model();

                            // message to the us
                            $message = '
                                                <strong>Full Name: </strong>' . ucwords($arr["full_name"]) . '<br/>
                                                <strong>Email: </strong>' . $arr["user_email"] . '<br/>
                                                <strong>Contact: </strong>' . $arr["user_contact"] . '<br/>
                                                <strong>Location: </strong>' . $arr["user_location"] . '<br/><br/>
                                                <strong>Request ID: </strong>' . $request_id . '<br/><br/>
                                                <strong>Message: </strong>' . $arr["user_message"] . '<br/>
                                                ';
                            $email_model->sendMail(SITE_EMAIL, "New message via " . SITE_NAME, $message);

                            // message to the user                            
                            $this->load->library('EmailTemplates');
                            $emailTemplate = new EmailTemplates();
                            $messageContent = $emailTemplate->contactUsEmail(ucwords($arr["full_name"]), $request_id);
                            $email_model->sendMail($arr["user_email"], "Thank you for contacting us | " . SITE_NAME, $messageContent);
                        }

                        $this->session->set_flashdata('success', 'Your message has been delivered successfully');
                        redirect(base_url('contact-us'));
                    }
                }
            }
            else
            {
                $data = array();
                $data["meta_title"] = "Contact Us | " . SITE_NAME;

                $this->template->write_view("content", "pages/staticpage/contact", $data);
                $this->template->render();
            }
        }

        public function updateSitemap()
        {
            $this->ci = & get_instance();
            $this->ci->load->database();
            $this->ci->load->model('Common_model');
            $model = new Common_model();

            $xml = '<?xml version = "1.0" encoding = "UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";
            $xml .= '<url><loc>' . base_url() . '</loc><lastmod>' . date('Y-m-d') . 'T' . date('H:i:s') . '+00:00</lastmod><changefreq>weekly</changefreq><priority>1.00</priority></url>' . "\n";

            // all the static links
            $static_links_without_base_url = array('contact-us', 'about-us', 'how-it-works', 'privacy-policy', 'terms', 'login', 'signup', 'forgot-password');
            foreach ($static_links_without_base_url as $slKey => $slValue)
            {
                $xml .= '<url><loc>' . base_url($slValue) . '</loc><lastmod>' . date('Y-m-d') . 'T' . date('H:i:s') . '+00:00</lastmod><changefreq>weekly</changefreq><priority>0.85</priority></url>' . "\n";
            }

            // all the active trips
            $trip_records = $model->fetchSelectedData('url_key', TABLE_TRIPS, array('trip_status' => '1'), 'trip_id', 'DESC');
            foreach ($trip_records as $trKey => $trValue)
            {
                $trip_url = getTripUrl($trValue['url_key']);
                $xml .= '<url><loc>' . $trip_url . '</loc><lastmod>' . date('Y-m-d') . 'T' . date('H:i:s') . '+00:00</lastmod><changefreq>weekly</changefreq><priority>1.00</priority></url>' . "\n";
            }

            // all the active users
            $user_records = $model->fetchSelectedData('username', TABLE_USERS, array('user_status' => '1'), 'user_id', 'DESC');
            foreach ($user_records as $urKey => $urValue)
            {
                $public_profile_url = getPublicProfileUrl($urValue['username']);
                $xml .= '<url><loc>' . $public_profile_url . '</loc><lastmod>' . date('Y-m-d') . 'T' . date('H:i:s') . '+00:00</lastmod><changefreq>weekly</changefreq><priority>1.00</priority></url>' . "\n";
            }

            // all the active blogs
            $blog_records = $model->fetchSelectedData('blog_url_key', TABLE_BLOGS, array('blog_status' => '1', 'blog_url_key !=' => ''), 'blog_id', 'DESC');
            foreach ($blog_records as $brKey => $brValue)
            {
                $blog_url = base_url('blog/read/' . $brValue['blog_url_key']);
                $xml .= '<url><loc>' . $blog_url . '</loc><lastmod>' . date('Y-m-d') . 'T' . date('H:i:s') . '+00:00</lastmod><changefreq>weekly</changefreq><priority>1.00</priority></url>' . "\n";
            }

            // all the view photo pages
            $photo_records = $model->fetchSelectedData('photo_id, album_key', TABLE_PHOTOS, array('image_name !=' => '', 'album_key !=' => ''), 'album_id', 'DESC');
            foreach ($photo_records as $prKey => $prValue)
            {
                $photo_url = base_url('view/photo/' . $prValue['album_key'] . '/' . $prValue['photo_id']);
                $xml .= '<url><loc>' . $photo_url . '</loc><lastmod>' . date('Y-m-d') . 'T' . date('H:i:s') . '+00:00</lastmod><changefreq>weekly</changefreq><priority>1.00</priority></url>' . "\n";
            }

            $xml .= '</urlset>';
//            prd($xml);

            $file = fopen((APPPATH . '/../sitemap.xml'), 'w');
            fwrite($file, $xml);
            fclose($file);
            die;
        }

        public function cronjob()
        {
            $this->updateSitemap();
        }

    }
    