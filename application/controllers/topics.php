<?php

class Topics extends Controller {
	
	function Topics()
	{
		parent::Controller();
		// $this->output->enable_profiler(TRUE);
	}
	
	function index()
	{		
		// list all promoted topics
		$data['page_title'] = "Serukan suaramu tentang Kota Bandung";
		$data['promoted_topics'] = $this->topic_model->get_promoted_records();
		
		// get all members' avatar
		$data['members'] = $this->member_model->get_frontpage_avatars();
		
		$this->load->view('topics/frontpage', $data);
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
	
	function all()
	{
		if ( ! $this->ion_auth->is_admin())
		{
			redirect('topics/display/semua');
		}
		else
		{		
			$data['rows'] = $this->topic_model->get_all_records();
			$data['page_title'] = "All topics";
			
			// count publlished records
			$data['promoted_records'] = $this->db->get_where('topics', array('is_promoted' => TRUE, 'status' => 'published'))->num_rows();
		
			// store last page number in the cookie
			$this->session->set_userdata('last_all_topics_page_number', $this->uri->segment(3));
			
			$this->load->view('topics/topic_list', $data);
		}
	}
	
	function display($category = 'semua')
	{
		$category = strtolower($category);
		if ($category == 'semua' 
			|| $category == 'berita' 
			|| $category == 'hiburan' 
			|| $category == 'dokumenter' 
			|| $category == 'twitter')
		{
			$data['rows'] = $this->topic_model->get_published_records($category);
			if ($category == 'semua')
			{
				$data['page_title'] = "Semua Topik";
			}
			elseif ($category == 'berita')
			{
				$data['page_title'] = "Kategori Berita";
			}
			elseif ($category == 'hiburan')
			{
				$data['page_title'] = "Kategori Hiburan";
			}
			elseif ($category == 'dokumenter')
			{
				$data['page_title'] = "Kategori Dokumenter";
			}
			elseif ($category == 'twitter')
			{
				$data['page_title'] = "Saran dari Twitter";
			}
			
			// store last display page in the cookie (uri segment 2 and 3)
			$this->session->set_userdata('last_display_topics_page_number', $this->uri->segment(3) . '/' . $this->uri->segment(4));
			
			// get latest comments
			$data['latest_comments'] = $this->comment_model->get_latest_comments();
			
			$this->load->view('topics/published_topic', $data);
		}
		else
		{
			show_404('page');
		}
	}
	
	function by($user_id = '')
	{
		$data['rows'] = $this->topic_model->get_topics_by($user_id);
		$data['page_title'] = "Topik oleh " . $data['rows'][0]->first_name . " " . $data['rows'][0]->last_name;
		
		if ($data['rows'])
		{
			// store last display page in the cookie
			$this->session->set_userdata('last_display_topics_by_page_number', $this->uri->segment(4));
			$this->load->view('topics/topics_by', $data);
		}
		else
		{
			show_404('page');
		}
	}
	
	function do_search()
	{
		if ($this->input->post('search_term') != '')
		{
			// temporary solution, search result cannot be bookmarked
			// just for storing the search term in the cookie
			// have to find a better solution for multiple search term
			$this->session->set_userdata('search_term', htmlentities($this->input->post('search_term'), ENT_QUOTES));
			$this->search();
		}
		else
		{
			$this->session->set_flashdata('message', '<p class="notice">Mohon masukkan kata kunci untuk pencarian.</p>');
			redirect('topics/display/semua');
		}
	}
	
	function search()
	{
		if ($this->session->userdata('search_term') != '')
		{
			$data['page_title'] = 'Hasil pencarian untuk: ' . $this->session->userdata('search_term');
			$data['search_result'] = $this->topic_model->get_search_result();
			if ($data['search_result'] == NULL)
			{
				$data['message'] = '<p class="notice">Maaf, topik dengan kata kunci yang kamu cari tidak ditemukan. Silakan coba lagi, atau sarankan topik baru.</p>';
			}
			$this->load->view('templates/search_result', $data);
		}
		else
		{
			$this->session->set_flashdata('message', '<p class="notice">Mohon masukkan kata kunci untuk pencarian.</p>');
			redirect('topics/display/semua');
		}
	}
	
	function show($topic_id)
	{
		$data['current_record'] = $this->topic_model->current_record($topic_id);
		
		if ($data['current_record'])
		{
			$data['page_title'] = substr($data['current_record']->content, 0, 76) . "...";
			$data['comments'] = $this->comment_model->get_records($topic_id);
			$data['top_comments'] = $this->comment_model->get_top_records($topic_id);
			
			if ($this->ion_auth->logged_in())
			{
				// if the user's logged in, perform voting check by current user
				if ($this->topic_model->voting_check($this->ion_auth->get_user()->id, $topic_id))
				{
					$data['direction_voted'] = $this->topic_model->vote_direction_check($this->ion_auth->get_user()->id, $topic_id);
				}
			}
			// add topic's view count
			$this->topic_model->add_view_count($topic_id);
			$this->load->view('topics/single_topic', $data);
		}
		else
		{	
			$this->session->set_flashdata('message', '<p class="notice">Maaf, topik yang kamu cari tidak tersedia.</p>');
			redirect('topics/display/semua');
		}
	}
	
	function add()
	{
		if ( ! $this->ion_auth->is_admin())
		{
			redirect('topics/suggest');
		}
		
		$data['page_title'] = "Create a new topic";
		$data['error'] = "";
			
		$this->load->view('topics/new_topic', $data);
	}
	
	function suggest()
	{
		if ( ! $this->ion_auth->logged_in())
		{
			$this->session->set_flashdata('message', '<p class="notice">Kamu harus login dahulu untuk menyarankan topik.</p>');
			redirect('login');
		}
		
		$data['page_title'] = "Suggest a topic";
		$data['error'] = "";
		
		$this->load->view('topics/new_topic', $data);
	}
	
	function create()
	{
		$this->_admin_filter();
		
		// validate the form first
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		$this->form_validation->set_rules('content', 'Isi Topik', 'required|trim|max_length[160]');
		$this->form_validation->set_rules('twitter_via', 'Twitter Via', 'trim|max_length[15]|xss_clean');
		
		if ($this->form_validation->run() == TRUE) 
		{// if the form validates	
			$data['content'] = $this->input->post('content', TRUE);
			$data['category'] = $this->input->post('category', TRUE);
			$indonesian_time = time() + (7 * 60 * 60);
			$data['datetime'] = date('Y-m-d H:i:s', $indonesian_time);
			$data['status'] = "published";
			$data['author_id'] = $this->ion_auth->get_user()->id;
			$data['twitter_via'] = $this->input->post('twitter_via', TRUE);

			// upload the image
			$config = array(
				'upload_path' => realpath(APPPATH . '../uploads/originals/'),
				'allowed_types' => 'jpg|jpeg|png',
				'file_name' => 'menurutmu.jpg',
				'overwrite' => FALSE,
				'max_size' => 1000,
				'encrypt_name' => TRUE
			);
			$this->load->library('upload', $config);
			
			// errors while uploading the image
			$error['error'] = $this->upload->display_errors('<p class="error">', '</p>');
			
			// it's okay for users not to upload any images
			if ($this->upload->do_upload() == TRUE || $error['error'] == '<p class="error">You did not select a file to upload.</p>' || $error['error'] == "")
			{
				$image_data = $this->upload->data();
				$data['image'] = $image_data['file_name'] != 'menurutmu.jpg' ? $image_data['file_name'] : '';
				
				// resize and crop the image
				$this->topic_model->resize_and_crop($image_data['full_path'], $image_data['image_width'], $image_data['image_height']);
				
				// insert the datas
				$this->topic_model->insert_record($data);
				
				// back to all topics
				$this->session->set_flashdata('message', '<p class="success">Topik baru telah berhasil dibuat.</p>');
				redirect('topics/all');
			}
			else
			{
				$error['page_title'] = "Please check again | Create a new topic";
				$this->load->view('topics/new_topic', $error);
			}
		}
		else
		{// if the form doesn't validate
			$data['page_title'] = "Create a new topic";
			$data['error'] = "";
				
			$this->load->view('topics/new_topic', $data);
		}
	}
	
	function create_suggestion()
	{
		$this->_login_filter();
		
		// validate the form first
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		$this->form_validation->set_rules('content', 'Isi Topik', 'required|trim|max_length[160]');
		
		// if the form validates
		if ($this->form_validation->run() == TRUE)
		{		
			$data['content'] = $this->input->post('content', TRUE);
			$data['category'] = $this->input->post('category', TRUE);
			$indonesian_time = time() + (7 * 60 * 60);
			$data['datetime'] = date('Y-m-d H:i:s', $indonesian_time);
			$data['status'] = "pending";
			$data['author_id'] = $this->ion_auth->get_user()->id;

			// upload the image
			$config = array(
				'upload_path' => realpath(APPPATH . '../uploads/originals'),
				'allowed_types' => 'jpg|jpeg|png',
				'file_name' => 'menurutmu.jpg',
				'overwrite' => FALSE,
				'max_size' => 1000,
				'encrypt_name' => TRUE			
			);
			$this->load->library('upload', $config);

			// errors while uploading the image
			$error['error'] = $this->upload->display_errors('<p class="error">', '</p>');

			// it's okay for users not to upload any images
			if ($this->upload->do_upload() == TRUE || $error['error'] == '<p class="error">You did not select a file to upload.</p>' || $error['error'] == "")
			{
				$image_data = $this->upload->data();
				$data['image'] = $image_data['file_name'] != 'menurutmu.jpg' ? $image_data['file_name'] : '';

				// resize and crop the image
				$this->topic_model->resize_and_crop($image_data['full_path'], $image_data['image_width'], $image_data['image_height']);
				
				// insert the datas
				$this->topic_model->insert_record($data);
				
				// back to all topics
				$this->session->set_flashdata('message', '<p class="success">Topikmu telah berhasil disarankan!</p>');
				redirect('topics/display/semua');
			}
			else
			{
				$error['page_title'] = "Please check again | Create a new topic";
				$this->load->view('topics/new_topic', $error);
			}
		} // end TRUE form validation
		else
		{// if the form doesn't validate
			$this->session->set_flashdata('message', validation_errors());
			$this->suggest();
		}
	}
	
	function edit($topic_id)
	{	
		$this->_admin_filter();
	
		$data['page_title'] = "Edit topic";
		$data['error'] = "";		
		$data['current_record'] = $this->topic_model->current_record($topic_id);
		
		$this->load->view('topics/edit_topic', $data);
	}
	
	function update($topic_id)
	{
		$this->_admin_filter();
		
		// validate the form first
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		$this->form_validation->set_rules('content', 'Isi Topik', 'required|trim|max_length[160]');
	
		// if the form validates
		if ($this->form_validation->run() == TRUE) 
		{
			$data['content'] = $this->input->post('content', TRUE);
			$data['category'] = $this->input->post('category');
			
			// upload the image
			$config = array(
				'upload_path' => realpath(APPPATH . '../uploads/originals'),
				'allowed_types' => 'jpg|jpeg|png',
				'file_name' => 'menurutmu.jpg',
				'overwrite' => FALSE,
				'max_size' => 1000,
				'encrypt_name' => TRUE			
			);
			$this->load->library('upload', $config);

			// errors while uploading the image
			$error['error'] = $this->upload->display_errors('<p class="error">', '</p>');

			// it's okay for users not to upload any images
			if ($this->upload->do_upload() == TRUE || $error['error'] == '<p class="error">You did not select a file to upload.</p>' || $error['error'] == "")
			{
				$image_data = $this->upload->data();
				$data['image'] = $image_data['file_name'] != 'menurutmu.jpg' ? $image_data['file_name'] : '' ;

				// resize and crop the image
				$this->topic_model->resize_and_crop($image_data['full_path'], $image_data['image_width'], $image_data['image_height']);

				// update the datas
				$this->topic_model->update_record($topic_id, $data);
				
				// back to all topics
				$this->session->set_flashdata('message', '<p class="success">Topik berhasil di-update.</p>');
				redirect('topics/all/' . $this->session->userdata('last_all_topics_page_number'));
			}
			else
			{
				$error['page_title'] = "Please check again | Create a new topic";
				$this->load->view('topics/edit_topic', $error);
			}
		} // end TRUE form validation
		else
		{
			$this->session->set_flashdata('message', validation_errors());
			$this->edit($topic_id);
		}
	}
	
	function publish($topic_id)
	{
		$this->_admin_filter();
		
		$this->topic_model->change_to_public($topic_id);
		// back to all topics
		redirect('topics/all/' . $this->session->userdata('last_all_topics_page_number'));
	}
	
	function unpublish($topic_id)
	{
		$this->_admin_filter();
	
		$this->topic_model->change_to_pending($topic_id);
		// back to all topics
		redirect('topics/all/' . $this->session->userdata('last_all_topics_page_number'));
	}
	
	function promote($topic_id)
	{
		$this->_admin_filter();
	
		$this->topic_model->change_to_promoted($topic_id);
		// back to all topics
		redirect('topics/all/' . $this->session->userdata('last_all_topics_page_number'));
	}
	
	function degrade($topic_id)
	{
		$this->_admin_filter();
		
		$this->topic_model->change_to_degradated($topic_id);
		// back to all topics
		redirect('topics/all/' . $this->session->userdata('last_all_topics_page_number'));
	}
	
	function confirm_delete($topic_id)
	{
		$this->_admin_filter();
	
		$data['page_title'] = "Confirm Topic Deletion";
		$data['current_record'] = $this->topic_model->current_record($topic_id);
		
		$this->load->view('topics/delete_topic', $data);
	}
	
	function delete($topic_id)
	{
		$this->_admin_filter();
	
		$this->topic_model->delete_record($topic_id);
		// back to all topics
		$this->session->set_flashdata('message', '<p class="notice">Topik telah berhasil dihapus.</p>');
		redirect('topics/all/' . $this->session->userdata('last_all_topics_page_number'));
	}
	
	function vote($direction, $topic_id)
	{
		// if the direction's up or down, check if the user's logged in
		if ($direction == 'up' || $direction == 'down')
		{
			// if the user's logged in, perform voting
			if ($this->ion_auth->logged_in())
			{				
				if ($direction == 'up')
				{// check if the user has voted
					if ($this->topic_model->voting_check($this->ion_auth->get_user()->id, $topic_id))
					{// check if the direction is 'up'
						if ($this->topic_model->vote_direction_check($this->ion_auth->get_user()->id, $topic_id) == 'up')
						{// if the direction is 'up', he cannot add more vote
							$this->session->set_flashdata('message', '<p class="notice">Kamu sudah vote-up untuk topik ini sebelumnya.</p>');
							redirect('topics/show/'. $topic_id);
						}
						else
						{// if the direction is 'down', remove the record
							$this->topic_model->remove_vote_record($this->ion_auth->get_user()->id, $topic_id, 'up');
							$this->session->set_flashdata('message', '<p class="notice">Kamu telah unvote topik ini.</p>');
							redirect('topics/show/'. $topic_id);
						}
					}
					else
					{// if the user hasn't voted, add vote record
						$this->session->set_flashdata('message', '<p class="success">Kamu telah vote-up topik ini.</p>');
						$this->topic_model->add_vote_record($this->ion_auth->get_user()->id, $topic_id, 'up');
						redirect('topics/show/'. $topic_id);
					}
				}
				else
				{
					// check if the user has voted
					if ($this->topic_model->voting_check($this->ion_auth->get_user()->id, $topic_id))
					{// check if the direction is 'down'
						if ($this->topic_model->vote_direction_check($this->ion_auth->get_user()->id, $topic_id) == 'down')
						{// if the direction is 'down', he cannot add more vote
							$this->session->set_flashdata('message', '<p class="notice">Kamu sudah vote-down untuk topik ini sebelumnya.</p>');
							redirect('topics/show/'. $topic_id);
						}
						else
						{// if the direction is 'up', remove the record
							$this->session->set_flashdata('message', '<p class="notice">Kamu telah unvote topik ini.</p>');
							$this->topic_model->remove_vote_record($this->ion_auth->get_user()->id, $topic_id, 'down');
							redirect('topics/show/'. $topic_id);
						}
					}
					else
					{// if the user hasn't voted, add vote record
						$this->session->set_flashdata('message', '<p class="notice">Kamu telah vote-down topik ini.</p>');
						$this->topic_model->add_vote_record($this->ion_auth->get_user()->id, $topic_id, 'down');
						redirect('topics/show/'. $topic_id);
					}
				}
			}
			else
			{
				// if not logged in, show login screen
				$this->session->set_flashdata('message', '<p class="notice">Kamu harus login agar bisa voting topik.</p>');
				redirect('login');
			}
		}
		else
		{
			// if direction's anything else, show 404 page
			show_404('page');
		}
		
	}
}

/* End of file topics.php */
/* Location: ./system/application/controllers/topics.php */