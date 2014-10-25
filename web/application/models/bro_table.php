<?php

class bro_table extends CI_Model {

    const DB_TABLE      = 'bro_table';

    // RETURN ERROR CODES
    const SUCCESS       = 0;
    const INVALID_URL   = 1;
    const INVALID_TYPE  = 2;
    const INVALID_STAR  = 3;

    function add_new_comment($input){
        $data = array(
            'url'           => $input['url'] ,
            'type'          => $input['type'] ,
            'description'   => $input['description'],
        );

        if(isset($input['stars'])) {
                $data['stars'] = $input['stars'];
        }

        if(isset($input['username'])) {
            $data['username'] = $input['username'];
        }

        $validate_result = $this->_validate($data);
        if($validate_result['return']) {
            $this->db->insert(self::DB_TABLE, $data);
            echo json_encode(self::SUCCESS);
        } else {
            echo json_encode($validate_result['reason']);
        }
    }

    function fetch_url_data($url) {
        if(isset($url)){
            if($url == ""){
                echo json_encode(self::INVALID_URL);
            } else{
                echo json_encode($this->db->get_where(self::DB_TABLE, array('url' => $url))->result());
            }
        } else{
            echo json_encode(self::INVALID_URL);
        }
    }

    // Return an array of the form ('return' => false, 'reason' => '2')
    function _validate($input) {
        return array('return' => true);
        return array('return' => false, 'reason' => self::INVALID_URL);
    }
}
?>
