<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index() {
        $this->load->model('bro_table');
        // return $this->bro_table->add_new_comment();
        //$this->load->view('welcome_message');
	}

	public function new_comment() {
		$data = $this->input->post();
		// var_dump($data);
		// die();
		// $data = array(
  //           'url'           => 'https://www.google.com/?gws_rd=ssl' ,
  //           'type'          => 2 ,
  //           'description'   => '$this->db->select() accepts an optional second parameter. If you set it to FALSE, CodeIgniter will not try to protect your field or table names with backticks. This is useful if you need a compound select statement.',
  //           'star'			=> null,
  //           'username' 		=> 'fox',
  //           'title' 		=> 'Good website + awesome freebies!!',
  //       );

		$this->load->model('bro_table');
		return $this->bro_table->add_new_comment($data);
	}

	public function get_url_comment() {
		$data = $this->input->post();
		$this->load->model('bro_table');
		return $this->bro_table->fetch_url_data($data);
	}

	public function upvote() {
		$data = $this->input->post();
		$this->load->model('bro_table');
		// $data['Id'] = 36;
		return $this->bro_table->upvote($data);
	}

	public function downvote() {
		$data = $this->input->post();
		$this->load->model('bro_table');
		// $data['Id'] = 36;
		return $this->bro_table->downvote($data);
	}

	public function spam() {
		$data = $this->input->post();
		$this->load->model('bro_table');
		// $data['Id'] = 36;
		return $this->bro_table->spam($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
