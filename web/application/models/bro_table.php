<?php

class bro_table extends CI_Model {

    private static $DB_TABLE = 'bro_table';

    function add_new_comment($input){
        $data = array(
            'url'           => $input['url'] ,
            'type'          => $input['type'] ,
            'description'   => $input['description'],
            'title'         => $input['title'],
        );

        if(isset($input['stars'])) {
            $data['stars'] = $input['stars'];
        }

        if(isset($input['username'])) {
            $data['username'] = $input['username'];
        }

        $data['url'] = $this->clear_url($data['url']);
        $this->db->insert(self::$DB_TABLE, $data);

        echo json_encode(array('response' => $this->db->_error_message()));
        return;
    }

    function fetch_url_data($data) {
        $fetched_data = array();
        $data['url'] = $this->clear_url($data['url']);
        if($this->_validate($data)) {
            $data['spam <'] = 5;
            $fetched_data = $this->db->order_by('upvotes', 'desc')->order_by('downvotes', 'desc')->order_by('timestamp', 'desc')->get_where(self::$DB_TABLE, $data, 5)->result();
        }

        echo json_encode($fetched_data);
        return;
    }

    function clear_url($url)
    {
        $temp = parse_url($url);
        unset($temp['query']);
        return http_build_query($temp);
    }

    // Return true or false
    function _validate($input) {
        $return_code = true;
        return $return_code;
    }

    function upvote($data) {
        $current = $this->db->get_where(self::$DB_TABLE, $data)->row();
        $current->upvotes++;

        $this->db->where('id', $data['id']);
        $this->db->update(self::$DB_TABLE, $current);
    }

    function downvote($data) {
        $current = $this->db->get_where(self::$DB_TABLE, $data)->row();
        $current->downvotes--;

        $this->db->where('id', $data['id']);
        $this->db->update(self::$DB_TABLE, $current);
    }

    function spam($data) {
        $current = $this->db->get_where(self::$DB_TABLE, $data)->row();
        $current->spam++;

        $this->db->where('id', $data['id']);
        $this->db->update(self::$DB_TABLE, $current);
    }

    // function edit_comment($data) {
    //     $current = $this->db->get_where(self::$DB_TABLE, $data)->row();
    //     $current->description = $data['description'];

    //     $this->db->where('id', $data['id']);
    //     $this->db->update(self::$DB_TABLE, $current);
    // }
}
?>
