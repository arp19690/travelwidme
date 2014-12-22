<?php

    class Custom_model extends CI_Model
    {

        public function __construct()
        {
            parent::__construct();
            // Load DB here
            $this->load->database();
        }

        public function getUnreadChatsAjax($loggedin_user_id, $other_user_id, $limit = NULL)
        {
            $whereCondArr = array(
                "message_from" => $other_user_id,
                "message_to" => $loggedin_user_id,
                "message_read" => "0",
            );

            $records = $this->getThreadMessages($loggedin_user_id, $other_user_id, $whereCondArr, $limit);
//            prd($records);

            if (!empty($records))
            {
                $updateWhereCondArr = array();
                foreach ($records as $key => $value)
                {
                    $updateWhereCondArr["message_id"] = $value["message_id"];
                }

                // updating the records fetched so that they do not come back in the array next time
                $this->db->update(TABLE_MESSAGES, array("message_read" => "1"), $updateWhereCondArr);
            }

            return $records;
        }

        public function getNearbyPosts($fields, $latitude, $longitude, $trip_purpose = "travel", $limit = 50)
        {
            $sql = "SELECT $fields FROM (SELECT *, (((acos(sin((" . $latitude . "*pi()/180)) *
                        sin((`latitude`*pi()/180))+cos((" . $latitude . "*pi()/180)) *
                        cos((`longitude`*pi()/180)) * cos(((" . $longitude . "-
                        `longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
                        FROM `" . TABLE_TRIPS . "`) " . TABLE_TRIPS . " 
                        WHERE trip_purpose = '$trip_purpose' AND latitude != '' AND longitude != '' 
                        ORDER BY distance ASC 
                        LIMIT $limit";
            return $this->db->query($sql)->result_array();
        }

        public function getSearchResults($looking_for, $destination = null, $limit = null, $fields = NULL, $whereCondArray = NULL)
        {
            if ($fields == NULL)
                $fields = "t.*, u.first_name, u.last_name, u.username, u.user_facebook_id";

            $fields .=" , tl.user_id as like_user_id";

            $records = $this->db->select($fields);
            $records->join(TABLE_USERS . " as u", "u.user_id = t.trip_user_id", "INNER");

            $join_cond = "t.trip_id = tl.trip_id";
            if (isset($this->session->userdata["user_id"]))
                $join_cond = "t.trip_id = tl.trip_id AND tl.user_id = " . $this->session->userdata["user_id"];

            $records->join(TABLE_TRIP_LIKES . " as tl", "$join_cond", "LEFT");

            $this->db->order_by("t.trip_id", "DESC");

            if ($limit != NULL)
            {
                $start = "0";
                $explode_limit = explode(",", $limit);
//                prd($explode_limit);
                if (isset($explode_limit[1]))
                    $start = $explode_limit[1];
                $this->db->limit($start, $explode_limit[0]);
            }

            if ($destination != NULL)
                $this->db->like("trip_destination", $destination);

            $this->db->like("trip_purpose", $looking_for);

            $whereCondArr = array(
                "user_status" => "1",
                "trip_status" => "1",
            );

            if ($whereCondArray != NULL)
            {
                foreach ($whereCondArray as $wKey => $wValue)
                {
                    $whereCondArr[$wKey] = $wValue;
                }
            }

            $records = $records->get_where(TABLE_TRIPS . " as t", $whereCondArr);

            $records = $records->result_array();
//            prd($records);
            return $records;
        }

        public function getSearchResultsTotalCount($looking_for, $destination)
        {
            $fields = "COUNT(trip_id) as totalcount";

            $records = $this->db->select($fields);
            $records->join(TABLE_USERS . " as u", "u.user_id = t.trip_user_id", "INNER");

            $this->db->like("trip_destination", $destination);
            $this->db->like("trip_purpose", $looking_for);

            $whereCondArr = array(
                "user_status" => "1",
                "trip_status" => "1",
            );
            $records = $records->get_where(TABLE_TRIPS . " as t", $whereCondArr);

            $records = $records->result_array();
            return $records[0]["totalcount"];
        }

        public function getInboxList_new($loggedin_user_id, $limit = null)
        {
            $message_array = array();
            $output = array();


            $messageWhereCond = "message_from IN ($loggedin_user_id) OR message_to IN ($loggedin_user_id)";

            $message_array_record = $this->db->select("message_content, message_from, message_to, message_timestamp, message_read, message_id")
                    ->join(TABLE_USERS . " as u", "u.user_id = m.message_from OR u.user_id = m.message_to")
                    ->order_by("m.message_id", "DESC")
//                        ->limit(1)
                    ->group_by("m.message_id")
                    ->get_where(TABLE_MESSAGES . " as m", $messageWhereCond)
                    ->result_array();
            prd($message_array_record);


            $user_array_record = $this->db->select("DISTINCT(user_id) as user_id, first_name, last_name, user_facebook_id")
                    ->join(TABLE_USERS . " as u", "u.user_id = m.message_from OR u.user_id = m.message_to")
                    ->group_by("m.message_from")
                    ->order_by("m.message_id", "DESC")
                    ->get_where(TABLE_MESSAGES . " as m", "(m.message_from = $loggedin_user_id OR m.message_to = $loggedin_user_id) AND username != '" . $this->session->userdata["username"] . "'")
                    ->result_array();
            prd($user_array_record);

            foreach ($user_array_record as $uKey => $uValue)
            {
                $other_user_id = $uValue["user_id"];
                $messageWhereCond = "message_from IN ($other_user_id, $loggedin_user_id) AND message_to IN ($other_user_id, $loggedin_user_id)";

                $message_array_record = $this->db->select("message_content, message_from, message_timestamp, message_read, message_id")
                        ->join(TABLE_USERS . " as u", "u.user_id = m.message_from OR u.user_id = m.message_to")
                        ->order_by("m.message_id", "DESC")
                        ->limit(1)
                        ->get_where(TABLE_MESSAGES . " as m", $messageWhereCond)
                        ->result_array();

                // adding the last message chat in the array
                foreach ($message_array_record[0] as $mKey => $mValue)
                {
                    $message_array[$mKey] = $mValue;
                }

                // adding the another user records in the array
                foreach ($uValue as $subkey => $subvalue)
                {
                    $message_array[$subkey] = $subvalue;
                }

                $output[] = $message_array;
            }

            //obtaining the final array to sort by message_id
            foreach ($output as $key => $row)
            {
                $mid[$key] = $row['message_id'];
            }

            // Sort the data with mid descending
            // Add $data as the last parameter, to sort by the common key
            @array_multisort($mid, SORT_DESC, $output);

            return $output;
        }

        public function getInboxList($loggedin_user_id, $limit = null)
        {
            $message_array = array();
            $output = array();

            $user_array_record = $this->db->select("DISTINCT(user_id) as user_id, first_name, last_name, user_facebook_id, username")
                    ->join(TABLE_USERS . " as u", "u.user_id = m.message_from OR u.user_id = m.message_to")
                    ->group_by("m.message_from")
                    ->order_by("m.message_id", "DESC")
                    ->get_where(TABLE_MESSAGES . " as m", "(m.message_from = $loggedin_user_id OR m.message_to = $loggedin_user_id) AND username != '" . $this->session->userdata["username"] . "'")
                    ->result_array();

            foreach ($user_array_record as $uKey => $uValue)
            {
                $other_user_id = $uValue["user_id"];
                $messageWhereCond = "message_from IN ($other_user_id, $loggedin_user_id) AND message_to IN ($other_user_id, $loggedin_user_id)";

                $message_array_record = $this->db->select("message_content, message_from, message_timestamp, message_read, message_id")
                        ->join(TABLE_USERS . " as u", "u.user_id = m.message_from OR u.user_id = m.message_to")
                        ->order_by("m.message_id", "DESC")
                        ->limit(1)
                        ->get_where(TABLE_MESSAGES . " as m", $messageWhereCond)
                        ->result_array();

                // adming the last message chat in the array
                foreach ($message_array_record[0] as $mKey => $mValue)
                {
                    $message_array[$mKey] = $mValue;
                }

                // adding the another user records in the array
                foreach ($uValue as $subkey => $subvalue)
                {
                    $message_array[$subkey] = $subvalue;
                }

                $output[] = $message_array;
            }

            //obtaining the final array to sort by message_id
            foreach ($output as $key => $row)
            {
                $mid[$key] = $row['message_id'];
            }

            // Sort the data with mid descending
            // Add $data as the last parameter, to sort by the common key
            @array_multisort($mid, SORT_DESC, $output);

            return $output;
        }

        public function getOutboxList($loggedin_user_id, $limit = null)
        {
            $fields = "DISTINCT(u.user_id) as user_id, u.*, m.*";

            $records = $this->db->select($fields);

            if ($limit != NULL)
            {
                $start = "0";
                $explode_limit = explode(",", $limit);
//                prd($explode_limit);
                if (isset($explode_limit[1]))
                    $start = $explode_limit[1];
                $this->db->limit($start, $explode_limit[0]);
            }

            $this->db->order_by("message_id", "DESC");
            $this->db->group_by(array("user_id"));

            $records->join(TABLE_USERS . " as u", "u.user_id = m.message_to", "LEFT");

            $records = $records->get_where(TABLE_MESSAGES . " as m", array("message_to !=" => $loggedin_user_id));

            $records = $records->result_array();
            return $records;
        }

        public function getThreadMessages($loggedin_user_id, $other_user_id, $whereCondArr = NULL, $limit = null)
        {
            $message_array = array();
            $output = array();

            $whereCond = "";
            if ($whereCondArr == NULL)
            {
                $whereCond = "message_from IN ($other_user_id, $loggedin_user_id) AND message_to IN ($other_user_id, $loggedin_user_id)";
            }
            else
            {
                $whereCond = $whereCondArr;
            }

            $user_array_record = $this->db->select("user_id, first_name, last_name, user_facebook_id, message_content, message_timestamp, message_id")
                    ->join(TABLE_USERS . " as u", "u.user_id = m.message_from")
                    ->group_by("m.message_id")
                    ->order_by("m.message_id", "ASC")
                    ->get_where(TABLE_MESSAGES . " as m", $whereCond)
                    ->result_array();

            return ($user_array_record);
        }

        public function areFriends($loggedin_user_id, $other_user_id)
        {
            $returnValue = FALSE;

            $records = $this->db->select("is_accepted");

            $this->db->where("sent_from", $loggedin_user_id);
            $this->db->or_where("sent_to", $loggedin_user_id);
            $this->db->or_where("sent_from", $other_user_id);
            $this->db->or_where("sent_to", $other_user_id);

            $records = $records->get(TABLE_FRIENDS);
            $records = $records->result_array();

            if (!empty($records) && ($records[0]["is_accepted"] == "1"))
            {
                $returnValue = TRUE;
            }

            return $returnValue;
        }

        public function isFriend($loggedin_user_id, $other_uder_id, $fields = NULL)
        {
            if ($fields == NULL)
                $fields = "*";

            $sql = "SELECT $fields FROM " . TABLE_FRIENDS . " WHERE sent_from IN ($loggedin_user_id, $other_uder_id) AND sent_to IN ($loggedin_user_id, $other_uder_id)";
            return $this->db->query($sql)->result_array();
        }

        public function getMyFriends($loggedin_user_id, $fields = null, $limit = NULL, $order_by = NULL)
        {
            if ($fields == NULL)
                $fields = "*";

            $records = $this->db->select($fields);

            $this->db->group_by("u.user_id");
            if ($limit != NULL)
            {
                $start = "0";
                $explode_limit = explode(",", $limit);
//                prd($explode_limit);
                if (isset($explode_limit[1]))
                    $start = $explode_limit[1];
                $this->db->limit($start, $explode_limit[0]);
            }

            if ($order_by == NULL)
                $order_by = "rand()";

            $this->db->order_by($order_by);

            $this->db->where("u.user_status", "1");
            $this->db->where("u.user_id !=", $loggedin_user_id);
            $this->db->where("f.sent_from", $loggedin_user_id);
            $this->db->or_where("f.sent_to", $loggedin_user_id);

            $records->join(TABLE_USERS . " as u", "u.user_id = f.sent_from OR u.user_id = f.sent_to AND u.user_id != $loggedin_user_id", "INNER");

            $records = $records->get(TABLE_FRIENDS . " as f");
            $records = $records->result_array();

//            prd($records);
            return $records;
        }

        public function getMyFriendsCount($loggedin_user_id)
        {
            $records = $this->db->select('COUNT(friend_id) as totalcount');

            $this->db->where("u.user_status", "1");
            $this->db->where("u.user_id !=", $loggedin_user_id);
            $this->db->where("f.sent_from", $loggedin_user_id);
            $this->db->or_where("f.sent_to", $loggedin_user_id);

            $records->join(TABLE_USERS . " as u", "u.user_id = f.sent_from OR u.user_id = f.sent_to AND u.user_id != $loggedin_user_id", "INNER");

            $records = $records->get(TABLE_FRIENDS . " as f");
            $records = $records->result_array();

//            prd($records);
            return $records[0]['totalcount'];
        }

        public function getAllAlbums($user_id, $limit = NULL)
        {
            $records = $this->db->select('album_id, album_name, album_description, album_key');

            $this->db->where("a.user_id", $user_id);
            $this->db->order_by("a.album_id", "DESC");

            if ($limit != NULL)
            {
                $start = "0";
                $explode_limit = explode(",", $limit);
//                prd($explode_limit);
                if (isset($explode_limit[1]))
                    $start = $explode_limit[1];
                $this->db->limit($start, $explode_limit[0]);
            }

            $records = $records->get(TABLE_ALBUMS . " as a");
            $records = $records->result_array();

            $photo_output = array();
            if (!empty($records))
            {
                foreach ($records as $key => $value)
                {
                    $sql = "SELECT image_name FROM " . TABLE_PHOTOS . " as p WHERE album_id = " . $value['album_id'] . " ORDER BY photo_id ASC LIMIT 0,1";
                    $photo_records = $this->db->query($sql)->result_array();
                    $photo_output[] = @$photo_records[0];
                }
            }

            foreach ($photo_output as $pKey => $pValue)
            {
                $records[$pKey]['image_name'] = $pValue['image_name'];
            }

//            prd($records);
            return $records;
        }

        public function getAllPhotosOnAlbum($album_key, $limit = NULL)
        {
            $records = $this->db->select('album_name, album_description, photo_id, photo_name, photo_description, image_name, photo_timestamp, ph.user_id');
            $this->db->where("a.album_key", $album_key);
            $this->db->order_by("ph.photo_id", "ASC");

            if ($limit != NULL)
            {
                $start = "0";
                $explode_limit = explode(",", $limit);
//                prd($explode_limit);
                if (isset($explode_limit[1]))
                    $start = $explode_limit[1];
                $this->db->limit($start, $explode_limit[0]);
            }

            $this->db->join(TABLE_ALBUMS . " as a", "a.album_id = ph.album_id", "INNER");
            $records = $records->get(TABLE_PHOTOS . " as ph")->result_array();
            return $records;
        }

        public function getPhotosAndComments($album_key, $photo_id, $fields = NULL)
        {
            if ($fields == NULL)
                $fields = "*";

            $records = $this->db->select($fields);
            $this->db->where("ph.album_key", $album_key);
            $this->db->where("ph.photo_id", $photo_id);

            $this->db->join(TABLE_USERS . " as u", "u.user_id = ph.user_id", "LEFT");
            $this->db->join(TABLE_PHOTO_COMMENTS . " as pc", "pc.image_name = ph.image_name", "LEFT");
            $this->db->join(TABLE_PHOTO_LIKES_DISLIKES . " as pld", "pld.image_name = ph.image_name", "LEFT");
            $records = $records->get(TABLE_PHOTOS . " as ph")->result_array();
            return $records;
        }

        public function getTotalLikesAndDislikes($image_name)
        {
            $sql = "SELECT (SELECT COUNT(`pld_id`) FROM photo_likes_dislikes WHERE `like_dislike` = '0' AND image_name = '$image_name') as dislikes, 
                        COUNT(`pld_id`) as likes FROM `photo_likes_dislikes` WHERE `like_dislike` = '1' AND image_name = '$image_name'";
            $records = $this->db->query($sql)->result_array();

            $output = array(
                'likes' => $records[0]['likes'],
                'dislikes' => $records[0]['dislikes'],
            );
            return $output;
        }

    }