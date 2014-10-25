<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends CI_Controller {

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

	public function new() {
		$data = $this->input->post();
		$this->load->model('bro_table');
		return $this->bro_table->add_new_comment($data);
	}

	public function get() {
		$data = $this->input->post();
		$this->load->model('bro_table');
		return $this->bro_table->fetch_url_data($data);
	}

	public function upvote() {
		$data = $this->input->post();
		$this->load->model('bro_table');
		return $this->bro_table->upvote($data);
	}

	public function downvote() {
		$data = $this->input->post();
		$this->load->model('bro_table');
		return $this->bro_table->downvote($data);
	}

	public function spam() {
		$data = $this->input->post();
		$this->load->model('bro_table');
		return $this->bro_table->spam($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
