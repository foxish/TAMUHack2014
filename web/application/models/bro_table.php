<?php

class bro_table extends CI_Model {

    private static $DB_TABLE      = 'bro_table';

    // RETURN ERROR CODES
    private static $SUCCESS       = array('ERROR' => 0, 'REASON' => null);
    private static $INVALID_URL   = array('ERROR' => 1, 'REASON' => 'INVALID URL');
    private static $INVALID_TYPE  = array('ERROR' => 2, 'REASON' => 'INVALID TYPE');
    private static $INVALID_STAR  = array('ERROR' => 3, 'REASON' => 'INVALID STAR');

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
            $this->db->insert(self::$DB_TABLE, $data);
        }

        unset($validate_result['return']);
        echo json_encode($validate_result);
        return;
    }

    function fetch_url_data($data) {
        $validate_result = $this->_validate($data);

        $fetched_data = array();
        if($validate_result['return']) {
            $fetched_data = $this->db->get_where(self::$DB_TABLE, $data)->result();
        }

        unset($validate_result['return']);
        $validate_result['DATA'] = $fetched_data;
        echo json_encode($validate_result);
        return;
    }

    // Return an array of the form ('return' => false, 'reason' => '2')
    function _validate($input) {

        $return_code = self::$SUCCESS;
        $return_code['return'] = true;

        // Validate URL
        if(!filter_var($input['url'], FILTER_VALIDATE_URL)) {
            $return_code = self::$INVALID_URL;
            $return_code['return'] = false;
        }

        return $return_code;
    }

}
?>
