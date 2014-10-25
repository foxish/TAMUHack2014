<?php
class bro_table extends CI_Model {
    function add_new_comment(){
        $data = array(
            'url' => 'http://woof.com' ,
            'type' => 'tip' ,
            'description' => "Don't click on the red icon!!!"
        );
        $this->db->insert('bro_table', $data);
    }
}
?>
