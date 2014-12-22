<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Initialcheck
    {

        public function __construct()
        {
            $this->CI = & get_instance();
            $this->CI->load->database();
        }
        
        public function setTimezone()
        {
            $this->CI->load->database();
            $this->CI->db->cache_on();
            $this->CI->db->query("SET SESSION time_zone = '+5:30'");
            date_default_timezone_set("Asia/Kolkata");
        }

    }