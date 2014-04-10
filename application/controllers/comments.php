<?php

class Comments extends Controller {

	function Comments()
	{
		parent::Controller();
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
	
	function index()
	{
		$this->_admin_filter();
		
		$data['all_comments'] = $this->comment_model->get_all_comments();
		
		// store last all comments page number in the cookie
		$this->session->set_userdata('last_all_comments_page_number', $this->uri->segment(3));
		
		$this->load->view('comments/all_comments', $data);
	}
	
	function submit()
	{	
		$data['topic_id'] = $this->input->post('topic_id', TRUE);
		if ($this->input->post('comment_parent_id') == 0)
		{
			$data['comment_parent_id'] = NULL;
		}
		else
		{
			$data['comment_parent_id'] = $this->input->post('comment_parent_id');
		}
		$data['commenter_id'] = $this->ion_auth->get_user()->id;
		// $data['commenter_name'] = $this->input->post('name', TRUE);
		// $data['commenter_email'] = $this->input->post('email', TRUE);
		// $data['commenter_site'] = "http://" . $this->input->post('website', TRUE);
		$data['commenter_response'] = $this->input->post('response');
		$data['commenter_comment'] = $this->input->post('comment', TRUE);
		$indonesian_time = time() + (7 * 60 * 60);
		$data['datetime'] = date('Y-m-d H:i:s', $indonesian_time);
		
		// validate the comment form
		$this->form_validation->set_rules('comment', 'Komentar', 'trim|required|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			// tell the user to fill the unfilled comment
			$this->session->set_flashdata('message', '<p class="notice">Mohon masukkan tanggapan kamu.</p>');
			// check if the current topic exists
			$this->db->select('topic_id')->from('topics')->where('topic_id', $data['topic_id']);
			$query = $this->db->get();
			
			if ($query->num_rows() == 1)
			{
				redirect('topics/show/' . $data['topic_id']);
			}
			else
			{
				// if the topic doesn't exist
				$this->session->set_flashdata('message', '<p class="notice">Maaf, topik yang ingin kamu komentari tidak tersedia.</p>');
				redirect('topics/display/semua');
			}
		}
		else
		{
			// check the status of the current topic
			$this->db->select('status')->from('topics')->where('topic_id', $data['topic_id']);
			$query = $this->db->get();
			
			if ($query->num_rows() > 0)
			{
				$topic_status = $query->row()->status;
				
				if ($topic_status == 'pending')
				{
					$this->session->set_flashdata('message', '<p class="notice">Maaf, topik yang ingin kamu komentari tidak tersedia.</p>');
					redirect('topics/display/semua');
				}
				else
				{	
					// insert comment data
					$this->comment_model->insert_record($data);
					// add comment count for current topic
					$this->comment_model->add_comment_count($data['topic_id']);

					$this->session->set_flashdata('message', '<p class="success">Tanggapan kamu telah ditambahkan</p>');
					redirect('topics/show/' . $data['topic_id']);
				}
			}
			else
			{
				$this->session->set_flashdata('message', '<p class="notice">Maaf, topik yang ingin kamu komentari tidak tersedia.</p>');
				redirect('topics/display/semua');
			}
		}
	}
	
	function confirm_delete($comment_id)
	{
		$this->_login_filter();
		
		// retrieve the commenter_id first
		$this->db->select('commenter_id, topic_id')->from('comments')->where('comment_id', $comment_id);
		$query_row = $this->db->get()->row();
		
		if ($this->ion_auth->get_user()->id == $query_row->commenter_id || $this->ion_auth->is_admin())
		{
			$data['page_title'] = "Konfirmasi Hapus Komentar";
			$data['current_record'] = $this->comment_model->current_record($comment_id);
			
			// store the parent topic in the cookie
			$this->session->set_userdata('last_shown_topic', $query_row->topic_id);
			
			$this->load->view('comments/delete_comment', $data);
		}
		else
		{
			show_404('page');
		}
	}
	
	function delete($comment_id)
	{
		$this->_login_filter();
		
		// retrieve the commenter_id first
		$this->db->select('commenter_id')->from('comments')->where('comment_id', $comment_id);
		$commenter_id = $this->db->get()->row()->commenter_id;
		
		if ($this->ion_auth->get_user()->id == $commenter_id || $this->ion_auth->is_admin())
		{
			$this->comment_model->delete_record($comment_id);
			if ($this->ion_auth->is_admin())
			{// if admin, back to all comments
				$this->session->set_flashdata('message', '<p class="notice">Komentar telah berhasil dihapus.</p>');
				redirect('comments/index/' . $this->session->userdata('last_all_comments_page_number'));
			}
			else
			{// if regular user, back to previous topic
				$this->session->set_flashdata('message', '<p class="notice">Komentar telah berhasil dihapus.</p>');
				redirect('topics/show/' . $this->session->userdata('last_shown_topic'));
			}
		}
		else
		{
			show_404('page');
		}
	}
	
	function vote($direction, $comment_id)
	{
		if ( ! $this->ion_auth->logged_in())
		{	
			// if the user's not logged in
			$this->session->set_flashdata('message', '<p class="notice">Kamu harus login agar bisa voting komentar.</p>');
			redirect('login');
		}
		else
		{
			$logged_in_user = $this->ion_auth->get_user()->id;
			$topic_id = $this->db->get_where('comments', array('comment_id' => $comment_id))->row()->topic_id;
			
			if ($direction == 'up' || $direction == 'down')
			{
				$current_direction = $this->comment_model->voting_check($logged_in_user, $comment_id);
				if ($current_direction)
				{
					if ($current_direction == 'up')
					{
						if ($direction == 'up')
						{
							// if current direcion's up and the user is voting up again
							redirect('topics/show/' . $topic_id . '/#comment-' . $comment_id);
						}
						else
						{
							$this->comment_model->remove_vote_record($logged_in_user, $comment_id, $direction);
							redirect('topics/show/' . $topic_id . '/#comment-' . $comment_id);
						}
					}
					else
					{
						if ($direction == 'down')
						{
							// if current direcion's down and the user is voting down again
							redirect('topics/show/' . $topic_id . '/#comment-' . $comment_id, $direction);
						}
						else
						{
							$this->comment_model->remove_vote_record($logged_in_user, $comment_id, $direction);
							redirect('topics/show/' . $topic_id . '/#comment-' . $comment_id);
						}
					}
				}
				else
				{
					$this->comment_model->add_vote_record($logged_in_user, $comment_id, $direction);
					redirect('topics/show/' . $topic_id . '/#comment-' . $comment_id);
				}
			}
			else
			{
				// if the direction is anything else than 'up' or 'down', 
				// show 404
				show_404('page');
			}
		}
	}
}

/* End of file comments.php */
/* Location: ./system/application/controllers/comments.php */