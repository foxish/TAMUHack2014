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
	public function index()
	{
		echo "bla";
        $this->load->model('bro_table');
        // return $this->bro_table->add_new_comment();
        //$this->load->view('welcome_message');
	}

	public function new_comment()
	{
		$data = $this->input->post();
		$data = array(
            'url'           => 1 ,
            'type'          => 2 ,
            'description'   => 3,
            'star'			=> null,
        );

		$this->load->model('bro_table');
		return $this->bro_table->add_new_comment($data);
	}

	public function get_url_comment()
	{
		$data = $this->input->post();
		$this->load->model('bro_table');
		$data = array('url' => 1);
		return $this->bro_table->fetch_url_data($data['url']);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
