<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');	
	}

	public function index()
	{
		$data['title'] = 'Kangealme - Login';
		$this->load->view('templates/auth_header', $data);
		$this->load->view('auth/login');
		$this->load->view('templates/auth_footer');
	}

	public function registration()
	{
		$data['title'] = 'Kangealme - Registration';

		$this->form_validation->set_rules('name','Name', 'required|trim',['required' => 'Nama harus diisi']);
		$this->form_validation->set_rules('email','Email', 'required|trim|valid_email|is_unique[user.email]', ['is_unique' => 'email harus unik', 'required' => 'email harus diisi']);
		$this->form_validation->set_rules('password1','Password', 'required|trim|min_length[3]|matches[password2]',['matches' => 'Password tidak sama', 'min_length' => 'Password terlalu pendek','required' => 'Password harus diisi']);
		$this->form_validation->set_rules('password2','Password', 'required|trim|matches[password1]');


		if($this->form_validation->run() == false)
		{
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/registration');
			$this->load->view('templates/auth_footer');
		}else{
			$data = [
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'image' => 'default.jpg',
				'password' => password_hash($this->input->password1, PASSWORD_DEFAULT),
				'role_id' => 2,
				'is_active' => 1,
				'date_created' => time()
 			];

 			$this->db->insert('user', $data);

 			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data pengguna telah ditambahkan</div>');
 			redirect('auth');
		}
	}
}
