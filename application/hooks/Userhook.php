<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Userhook
    {

        public function __construct()
        {
            $this->CI = & get_instance();
            $this->CI->load->database();
        }

        public function index()
        {
            
        }

    }