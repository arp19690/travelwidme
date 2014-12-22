<?php

    class Email_model extends CI_Model
    {

        public function __construct()
        {
            parent::__construct();
            // Load DB here
            $this->load->database();
        }

        public function sendMail($to_email, $subject, $message)
        {
            $this->load->library('email');

            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = 'html';

            $this->email->initialize($config);

            $from_email = SITE_EMAIL;
            $from_name = SITE_NAME;

            $this->email->from($from_email, $from_name);
            $this->email->to($to_email);

            $this->email->subject($subject);
            $this->email->message($message);

            $this->email->send();
        }

    }

?>
