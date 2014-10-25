<?php
define('INVALID_URL', '1');

class bro_table extends CI_Model {

    const INVALID_URL   = 1;
    const DB_TABLE      = 'bro_table';

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
        $this->db->insert(self::DB_TABLE, $data);
    }

    function fetch_url_data($url)
    {
        if(isset($url)){
            if($url == ""){
                echo json_encode(self::INVALID_URL);
                return json_encode(self::INVALID_URL);
            } else{
                var_dump(json_encode($this->db->get_where(self::DB_TABLE, array('url' => $url))->result()));
                return json_encode($this->db->get_where(self::DB_TABLE, array('url' => $url))->result());
            }
        } else{
            echo json_encode(self::INVALID_URL);
            return json_encode(self::INVALID_URL);
        }
    }
}
?>
