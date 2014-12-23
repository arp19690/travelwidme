<?php

    class Blog extends CI_Controller
    {

        public function __construct()
        {
            parent::__construct();
        }

        public function index()
        {
            $model = new Common_model();

            $max_records = 20;
            $page = 1;
            if ($this->input->get('page'))
            {
                $page = $this->input->get('page');
            }
            $paginationLimit = getPaginationLimit($page, $max_records);

//            $blog_records = $model->getAllDataFromJoin("b.*,u.first_name,u.last_name, u.username", TABLE_BLOGS . " as b", array(TABLE_USERS . " as u" => "u.user_id = b.user_id"), "INNER", array("b.blog_status" => "1", "u.user_status" => "1"), "blog_id", "DESC", $paginationLimit);
            $blog_records = $model->fetchSelectedData('*', TABLE_BLOGS, array('blog_status' => '1'), "blog_id", "DESC", $paginationLimit);
//            prd($blog_records);

            $total_records = $model->getTotalCount("blog_id", TABLE_BLOGS, array("blog_status" => "1"));
            $pagination = getPaginationLinks(current_url(), $total_records[0]["totalcount"], $page, $max_records);
//            prd($pagination);

            $data = array();
            $data["pagination"] = $pagination;
            $data["record"] = $blog_records;

            $breadcrumbArray = array(
                "Blog" => base_url("blog"),
            );

            $data["breadcrumbArray"] = $breadcrumbArray;
            $data["meta_title"] = "Blogs | " . SITE_NAME;

            $this->template->write_view("content", "pages/blog/index", $data);
            $this->template->render();
        }

        public function read($blog_id)
        {
            if ($blog_id)
            {
                $model = new Common_model();
                $record = $model->fetchSelectedData("*", TABLE_BLOGS, array("blog_id" => $blog_id, "blog_status" => "1"));
                if (!empty($record))
                {
                    $data["record"] = $record[0];

                    $recent_blogs = $model->fetchSelectedData("blog_id, blog_title", TABLE_BLOGS, array("blog_status" => "1", "blog_id != " => $blog_id), "blog_id", "rand()");
                    $data["recent_blogs"] = $recent_blogs;

                    $breadcrumbArray = array(
                        "Blogs" => base_url("blog"),
                        ucwords($record[0]["blog_title"]) => base_url("blog/read/" . $record[0]["blog_id"]),
                    );
                    $data["breadcrumbArray"] = $breadcrumbArray;
                    $data["meta_title"] = ucwords($record[0]["blog_title"]) . " | " . SITE_NAME;
                    $data["meta_keywords"] = $record[0]["meta_keywords"];
                    $data["meta_description"] = html_entity_decode(trim(strip_tags($record[0]["meta_description"])));

                    $this->template->write_view("content", "pages/blog/read", $data);
                    $this->template->render();
                }
                else
                {
                    redirect("blog");
                }
            }
            else
            {
                redirect("blog");
            }
        }

        public function write()
        {
            $data = array();
//            if (isset($this->session->userdata["user_id"]))
//            {
            if ($this->input->post())
            {
                $arr = $this->input->post();
//                prd($arr);

                $blog_title = addslashes($arr["blog_title"]);
                $blog_content = addslashes($arr["blog_content"]);

                $model = new Common_model();

                $is_exists = $model->is_exists("blog_id", TABLE_BLOGS, array("blog_title" => $blog_title));
                if (empty($is_exists))
                {
                    //insert

                    $meta_keywords = str_replace(" ", ",", $blog_title);
                    $meta_keywords = str_replace(",,", ",", $meta_keywords);

                    if (!isset($this->session->userdata["user_id"]))
                    {
                        $user_id = "0";
                        $user_full_name = $arr['user_full_name'];
                        $user_email = $arr["user_email"];
                    }
                    else
                    {
                        $user_id = $this->session->userdata["user_id"];
                        $user_full_name = $this->session->userdata["first_name"] . " " . $this->session->userdata["last_name"];
                        $user_email = $this->session->userdata["user_email"];
                    }

                    $data_array = array(
                        "blog_title" => $blog_title,
                        "blog_content" => $blog_content,
                        "meta_keywords" => $meta_keywords,
                        "meta_description" => strip_tags(substr($blog_content, 0, 220)),
                        "user_id" => $user_id,
                        "user_full_name" => $user_full_name,
                        "user_email" => $user_email,
                        "blog_status" => "1",
                        "user_ipaddress" => USER_IP,
                        "user_agent" => USER_AGENT,
                    );
                    $model->insertData(TABLE_BLOGS, $data_array);
                    $blog_id = $this->db->insert_id();

                    if (isset($_FILES["blog_img"]) && $_FILES["blog_img"]["size"] > 0 && !empty($_FILES["blog_img"]))
                    {
                        $source = $_FILES["blog_img"]["tmp_name"];
                        $destination = BLOG_IMG_PATH . "/" . getEncryptedString($blog_id) . ".jpg";
                        $width = BLOG_IMG_WIDTH;
                        $height = BLOG_IMG_HEIGHT;

                        $this->load->library("SimpleImage");
                        $img = new SimpleImage();
                        $img->uploadImage($source, $destination, $width, $height);
                    }

                    $this->session->set_flashdata('success', 'Your blog has been sent for review and will be published soon');
                    redirect(base_url('blog'));
                }
                else
                {
                    //duplicate title
                    $this->session->set_flashdata('warning', 'Blog title already exists');

                    $data["record"] = array(
                        "blog_title" => $blog_title,
                        "blog_content" => $blog_content,
                    );

                    $breadcrumbArray = array(
                        "Blogs" => base_url("blog"),
                        "Write a Blog" => base_url("blog/write"),
                    );
                    $data["breadcrumbArray"] = $breadcrumbArray;
                    $data["meta_title"] = "Write a Blog | " . SITE_NAME;

                    $this->template->write_view("content", "pages/blog/write", $data);
                    $this->template->render();
                }
            }
            else
            {
                $breadcrumbArray = array(
                    "Blogs" => base_url("blog"),
                    "Write a Blog" => base_url("blog/write"),
                );
                $data["breadcrumbArray"] = $breadcrumbArray;
                $data["meta_title"] = "Write a Blog | " . SITE_NAME;

                $this->template->write_view("content", "pages/blog/write", $data);
                $this->template->render();
            }
//            }
//            else
//            {
//                $this->session->set_flashdata('warning', 'Please login in order to write a Blog');
//                redirect(base_url('blog'));
//            }
        }

        public function delete($blog_id)
        {
            if (isset($this->session->userdata["user_id"]) && $blog_id)
            {
                $user_id = $this->session->userdata["user_id"];
                $model = new Common_model();
                @unlink(BLOG_IMG_PATH . "/" . getEncryptedString($blog_id) . ".jpg");
                $model->deleteData(TABLE_BLOGS, array("user_id" => $user_id, "blog_id" => $blog_id));
                $this->session->set_flashdata('success', 'Your blog has been deleted permanently');
                redirect(base_url('my-account'));
            }
            else
            {
                redirect("blog");
            }
        }

        public function myBlogs()
        {
            $data = array();
            $user_id = $this->session->userdata["user_id"];
            $model = new Common_model();

            $max_records = 10;
            $page = 1;
            if ($this->input->get('page'))
            {
                $page = $this->input->get('page');
            }
            $paginationLimit = getPaginationLimit($page, $max_records);

            $records = $model->fetchSelectedData("blog_id, blog_title, blog_timestamp", TABLE_BLOGS, array("user_id" => $user_id), "blog_id", "DESC", $paginationLimit);
            $total_records = $model->getTotalCount('blog_id', TABLE_BLOGS, array("user_id" => $user_id));

            $pagination = getPaginationLinks(current_url(), $total_records[0]['totalcount'], $page, $max_records);

            $data["record"] = $records;
            $data["pagination"] = $pagination;

            $data["page_title"] = "My Blogs";
            $this->template->write_view("content", "pages/blog/my-blogs", $data);
            $this->template->render();
        }

    }