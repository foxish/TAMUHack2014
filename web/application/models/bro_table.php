<?php

class bro_table extends CI_Model {

    private static $DB_TABLE        = 'bro_table';
    private static $TYPES           = array('Tip', 'Suggestion');

    // RETURN ERROR CODES
    private static $SUCCESS         = array('ERROR' => 0, 'REASON' => null);
    private static $INVALID_URL     = array('ERROR' => 1, 'REASON' => 'INVALID URL');
    private static $INVALID_TYPE    = array('ERROR' => 2, 'REASON' => 'INVALID TYPE');
    private static $INVALID_STAR    = array('ERROR' => 3, 'REASON' => 'INVALID STAR');

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

        if($this->_validate($data)) {
            $this->db->insert(self::$DB_TABLE, $data);
        }

        $this->_return(array('response' => $this->db->_error_message()));
        return;
    }

    function fetch_url_data($data) {
        $fetched_data = array();
        if($this->_validate($data)) {
            $fetched_data = $this->db->order_by('upvotes', 'desc')->get_where(self::$DB_TABLE, $data, 5)->result();
        }

        echo json_encode($fetched_data);
        return;
    }

    // Return true or false
    function _validate($input) {
        $return_code = true;

        // Validate URL
        // if(!filter_var($input['url'], FILTER_VALIDATE_URL)) {
        //     $return_code = false;
        // }

        // Validate type
        // $type_validator = function($value) {
        //     foreach ($arr as &$value) {
        //         $value = $value * 2;
        //     }
        // };

        // if(!($input['type'] == 'Tip' || ))

        return $return_code;
    }

    function _return($data) {
        echo json_encode($data);
    }

    function upvote($data) {
        $current = $this->db->get_where(self::$DB_TABLE, $data)->row();
        $current->upvotes++;

        $this->db->where('Id', $data['Id']);
        $this->db->update(self::$DB_TABLE, $current);

        // echo $upvotes;
        echo json_encode($current);
    }

    function downvote($data) {
        $current = $this->db->get_where(self::$DB_TABLE, $data)->row();
        $current->downvotes--;

        $this->db->where('Id', $data['Id']);
        $this->db->update(self::$DB_TABLE, $current);

        // echo $upvotes;
        echo json_encode($current);
    }

    function spam($data) {
        $current = $this->db->get_where(self::$DB_TABLE, $data)->row();
        $current->spam++;

        $this->db->where('Id', $data['Id']);
        $this->db->update(self::$DB_TABLE, $current);

        // echo $upvotes;
        echo json_encode($current);
    }
}
?>
