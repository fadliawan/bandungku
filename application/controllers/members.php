<?php

class Members extends Controller {

	function Members()
	{
		parent::Controller();
	}
	
	function index()
	{
		$this->_admin_filter();
		
		// retrieving all members
		$data['members'] = $this->member_model->get_all_members();
		$this->load->view('members/all_members', $data);
	}
	
	function _admin_filter()
	{
		if ( ! $this->ion_auth->is_admin())
		{
			redirect('topics/all');
		}
	}
	
	function _login_filter()
	{
		if ( ! $this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}
	}
	
	function profile($user_id = '')
	{
		if ($user_id == '1' || $user_id == '')
		{
			redirect('topics/display/semua');
		}
		$data['current_user'] = $this->member_model->get_current_user($user_id);
		
		if ($data['current_user'])
		{
			// retreive how many comments he has posted
			$data['comments_posted'] = $this->member_model->count_posted_comments($user_id);
			// retrieve how many topics he has suggested			
			$data['topics_suggested'] = $this->member_model->count_suggested_topics($user_id);
			
			// retrieve his notifications
			if ($this->ion_auth->logged_in() && $this->ion_auth->get_user()->id == $data['current_user']->id)
			{
				$data['notifications'] = $this->member_model->get_activities($user_id);
			}
			else
			{
				$data['notifications'] = $this->member_model->get_his_activities($user_id);
			}
			$data['page_title'] = "Profil " . $data['current_user']->first_name . " " . $data['current_user']->last_name;
			
			$this->load->view('members/show_profile', $data);
		}
		else
		{
			redirect('topics/display/semua');
		}
	}
	
	function edit_profile()
	{
		$this->_login_filter();
	
		$data['page_title'] = "Edit Profilmu";
		$data['current_user'] = $this->member_model->get_current_user($this->ion_auth->get_user()->id);
		
		// form info
		$data['first_name'] = array(
			'name' => 'first_name',
			'id' => 'first_name',
			'value' => $data['current_user']->first_name
		);
		$data['last_name'] = array(
			'name' => 'last_name',
			'id' => 'last_name',
			'value' => $data['current_user']->last_name
		);
		$data['address'] = array(
			'name' => 'address',
			'id' => 'address',
			'value' => $data['current_user']->address
		);
		$data['hopes'] = array(
			'name' => 'hopes',
			'id' => 'hopes',
			'value' => $data['current_user']->hopes,
			'cols' => 45,
			'rows' => 10
		);
		$data['fb_profile'] = array(
			'name' => 'fb_profile',
			'id' => 'fb_profile',
			'value' => $data['current_user']->fb_profile
		);
		$data['twitter_name'] = array(
			'name' => 'twitter_name',
			'id' => 'twitter_name',
			'value' => $data['current_user']->twitter_name
		);
		
		$this->load->view('members/edit_profile', $data);
	}
	
	function update_profile()
	{
		$this->_login_filter();
		
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('address', 'Home Address', 'required|xss_clean');
		$this->form_validation->set_rules('hopes', 'Your Hopes for Bandung', 'required|xss_clean');
		$this->form_validation->set_rules('fb_profile', 'Profil Facebook', 'xss_clean');
		$this->form_validation->set_rules('twitter_name', 'Username Twitter', 'xss_clean');
		
		if ($this->form_validation->run() == TRUE)
		{
			// if the form validates
			$username = strtolower(htmlentities($this->input->post('first_name'), ENT_QUOTES)) . ' ' . strtolower(htmlentities($this->input->post('last_name'), ENT_QUOTES));
			$username = explode(' ', $username);
			$username = implode('_', $username);
			$username = substr($username, 0, 15);

			$additional_data = array(
				'first_name' => ucfirst($this->input->post('first_name')),
				'last_name' => ucfirst($this->input->post('last_name')),
				'address' => ucfirst($this->input->post('address')),
				'hopes' => ucfirst($this->input->post('hopes')),
				'fb_profile' => $this->input->post('fb_profile'),
				'twitter_name' => $this->input->post('twitter_name')
			);
			
			$this->member_model->update_member_profile($username, $additional_data);
			
			$this->session->set_flashdata('message', '<p class="success">Profil kamu telah diubah.</p>');
			redirect('members/profile/' . $this->ion_auth->get_user()->id);
		}
		else
		{
			// if the form doesn't validate
			$this->session->set_flashdata('message', validation_errors('<p class="error">', '</p>'));
			
			$data['page_title'] = "Edit Profilmu";
			
			// form info, with the value before validation fails
			$data['first_name'] = array(
				'name' => 'first_name',
				'id' => 'first_name',
				'value' => $this->form_validation->set_value('first_name')
			);
			$data['last_name'] = array(
				'name' => 'last_name',
				'id' => 'last_name',
				'value' => $this->form_validation->set_value('last_name')
			);
			$data['address'] = array(
				'name' => 'address',
				'id' => 'address',
				'value' => $this->form_validation->set_value('address')
			);
			$data['hopes'] = array(
				'name' => 'hopes',
				'id' => 'hopes',
				'value' => $this->form_validation->set_value('hopes'),
				'cols' => 50,
				'rows' => 10
			);
			$data['fb_profile'] = array(
				'name' => 'fb_profile',
				'id' => 'fb_profile',
				'value' => $this->form_validation->set_value('fb_profile')
			);
			$data['twitter_name'] = array(
				'name' => 'twitter_name',
				'id' => 'twitter_name',
				'value' => $this->form_validation->set_value('twitter_name')
			);
			
			$this->load->view('members/edit_profile', $data);
		}
	}
	
	function avatar()
	{
		$this->_login_filter();
	
		$data['page_title'] = "Ganti Foto Profil";
		$data['current_user'] = $this->member_model->get_current_user($this->ion_auth->get_user()->id);
		
		$this->load->view('members/update_avatar', $data);
	}
	
	function update_avatar()
	{
		$this->_login_filter();
	
		// config the upload
		$upload_config = array(
			'upload_path' => realpath(APPPATH . '../uploads/avatars'),
			'allowed_types' => 'jpg|jpeg|png',
			'file_name' => 'menurutmu_avatar.jpg',
			'overwrite' => FALSE,
			'max_size' => 512,
			'encrypt_name' => TRUE
		);
		$this->load->library('upload', $upload_config);
		
		// if the upload success
		if ($this->upload->do_upload() == TRUE)
		{
			// retrieve the image name
			$image_data = $this->upload->data();
			$data['avatar'] = $image_data['file_name'];
			
			// resize the image
			$this->member_model->resize_avatar($image_data['full_path'], $image_data['image_width'], $image_data['image_height']);
			
			// crop the image?
			
			// update current avatar in the database
			$this->member_model->update_user_avatar($data);
			
			// back to user profile
			$this->session->set_flashdata('message', '<p class="success">Foto profil kamu berhasil diganti.</p>');
			redirect('members/profile/' . $this->ion_auth->get_user()->id);
		}
		else
		{
			// errors while uploading the image
			$this->session->set_flashdata('message', $this->upload->display_errors('<p class="error">', '</p>'));
			redirect('members/avatar');
		}		
	}
	
	function notifications()
	{
		$this->_login_filter();
		
		$data['notifications'] = $this->member_model->get_activities($this->ion_auth->get_user()->id);
		$data['page_title'] = 'Semua aktivitas oleh member lain';
		
		if ($data['notifications'])
		{
			$this->load->view('members/notifications', $data);
		}
		else
		{
			redirect('topics/display/semua');
		}		
	}
	
	function activities()
	{
		$this->_login_filter();
		
		$data['notifications'] = $this->member_model->get_his_activities($this->ion_auth->get_user()->id);
		$data['page_title'] = 'Semua aktivitasmu';

		if ($data['notifications'])
		{
			$this->load->view('members/notifications', $data);
		}
		else
		{
			redirect('topics/display/semua');
		}
	}
}

/* End of file members.php */
/* Location: ./system/application/controllers/members.php */