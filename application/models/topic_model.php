<?php

class Topic_model extends Model {

	private $pagination_config = array(
		'per_page' => 5,
		'num_links' => 1,
		'first_link' => 'Terbaru',
		'first_tag_open' => '<li>',
		'first_tab_close' => '</li>',
		'last_link' => 'Terlawas',
		'last_tag_open' => '<li>',
		'last_tab_close' => '</li>',
		'next_link' => 'Lebih lama &raquo;',
		'next_tag_open' => '<li>',
		'next_tab_close' => '</li>',
		'prev_link' => '&laquo; Lebih baru',
		'prev_tag_open' => '<li>',
		'prev_tab_close' => '</li>',
		'cur_tag_open' => '<li><strong>',
		'cur_tab_close' => '</strong></li>',
		'num_tag_open' => '<li>',
		'num_tab_close' => '</li>'
	);

	function Topic_model()
	{
		parent::Model();
	}
	
	function get_all_records()
	{	
		// queries for pagination
		$this->db->select('*')->from('topics')->join('meta', 'meta.user_id = topics.author_id');
		$this->db->order_by('datetime', 'desc');
		$query = $this->db->get();
		
		// pagination config
		$this->pagination_config['base_url'] = base_url() . 'topics/all';
		$this->pagination_config['total_rows'] = $query->num_rows();
		$this->pagination->initialize($this->pagination_config);
		
		// queries to be displayed
		$this->db->select('*')->join('meta', 'meta.user_id = topics.author_id');
		$this->db->order_by('datetime', 'desc');
		$paginated_query = $this->db->get('topics', $this->pagination_config['per_page'], $this->uri->segment(3));
		
		return $paginated_query->result();
	}
	
	function get_published_records($category)
	{
		// queries for pagination
		$this->db->select('*')->from('topics')->where('status', 'published')->join('meta', 'meta.user_id = topics.author_id');
		if ($category != 'semua')
		{
			$this->db->where('category', $category);
		}
		$query = $this->db->get();
		
		// pagination config
		switch ($category)
		{
			case 'semua':
				$this->pagination_config['base_url'] = base_url() . 'topics/display/semua/';
				break;
			case 'berita':
				$this->pagination_config['base_url'] = base_url() . 'topics/display/berita/';
				break;
			case 'hiburan':
				$this->pagination_config['base_url'] = base_url() . 'topics/display/hiburan/';
				break;
			case 'dokumenter':
				$this->pagination_config['base_url'] = base_url() . 'topics/display/dokumenter/';
				break;
			case 'twitter':
				$this->pagination_config['base_url'] = base_url() . 'topics/display/twitter/';
				break;
		}
		$this->pagination_config['total_rows'] = $query->num_rows();
		$this->pagination_config['uri_segment'] = 4;
		$this->pagination->initialize($this->pagination_config);
		
		// queries for to be displayed
		$this->db->select('*')->where('status', 'published')->join('meta', 'meta.user_id = topics.author_id');
		if ($category != 'semua')
		{
			$this->db->where('category', $category);
		}
		$this->db->order_by('datetime', 'desc');
		$paginated_query = $this->db->get('topics', $this->pagination_config['per_page'], $this->uri->segment(4));
		
		return $paginated_query->result();
	}

	function insert_record($data)
	{
		$this->db->insert('topics', $data);
		// add current topic insertion to activities
		// select the latest topic first
		$this->db->select_max('topic_id');
		$topic_id = $this->db->get('topics')->row()->topic_id;
		// then select other fields, insert them into the activities table
		$this->db->where('topic_id', $topic_id);
		$query_row = $this->db->get('topics')->row();
		$this->add_activity($query_row->author_id, $query_row->topic_id);
	}
	
	function current_record($topic_id)
	{
		$this->db->where('topic_id', $topic_id)->join('meta', 'meta.user_id = topics.author_id')->join('users', 'users.id = topics.author_id');
		$query = $this->db->get('topics');
		$current_record = $query->row();
		
		// must be the first, the one and only record
		return $current_record;
	}
	
	function update_record($topic_id, $data)
	{
		$this->db->where('topic_id', $topic_id);
		$this->db->update('topics', $data);
	}
	
	function delete_record($topic_id)
	{
		$this->db->where('topic_id', $topic_id);
		$this->db->delete('topics');
	}
	
	function change_to_public($topic_id)
	{
		$data['status'] = "published";
		$this->db->where('topic_id', $topic_id);
		$this->db->update('topics', $data);
	}
	
	function change_to_pending($topic_id)
	{
		$data['status'] = "pending";
		$this->db->where('topic_id', $topic_id);
		$this->db->update('topics', $data);
	}
	
	function change_to_promoted($topic_id)
	{
		$data['is_promoted'] = TRUE;
		$this->db->where('topic_id', $topic_id);
		$this->db->update('topics', $data);
	}
	
	function change_to_degradated($topic_id)
	{
		$data['is_promoted'] = FALSE;
		$this->db->where('topic_id', $topic_id);
		$this->db->update('topics', $data);
	}
	
	function get_promoted_records()
	{
		$this->db->where(array('is_promoted' => TRUE, 'status' => 'published'));
		$this->db->limit(10);
		$this->db->order_by('datetime', 'desc');
		$query = $this->db->get('topics');
		
		return $query->result();
	}
	
	function resize_and_crop($source_path, $source_width, $source_height)
	{
		// resize the image down if width > 620px or height > 400px
		if ($source_width > 620 || $source_height > 400)
		{
			$resize_config = array(
				'image_library' => 'GD2',
				'source_image' => $source_path,
				'maintain_ratio' => TRUE,
				'width' => 600,
				'height' => 400
			);
			$this->image_lib->initialize($resize_config);
			$this->image_lib->resize();
		}
		
		// create image thumbnail
		/*
		$thumb_config = array(
			'image_library' => 'GD',
			'source_image' => $source_path,
			'create_thumb' => TRUE,
			'maintain_ratio' => FALSE,
			'width' => 64,
			'height' => 64
		);
		$this->image_lib->initialize($thumb_config);
		$this->image_lib->resize();
		*/
	}
	
	function get_search_result()
	{
		// splitting the search term
		$search_terms = explode(' ', $this->session->userdata('search_term'));
		
		// query for pagination
		$this->db->select('*')->from('topics')->join('meta', 'meta.user_id = topics.author_id');
		// searching for the splitted term
		foreach ($search_terms as $term)
		{
			$this->db->or_like('content', $term);
		}
		$query = $this->db->get();
		
		// pagination config
		$this->pagination_config['base_url'] = base_url() . 'topics/search/';
		$this->pagination_config['total_rows'] = $query->num_rows();
		$this->pagination_config['uri_segment'] = 3;
		$this->pagination->initialize($this->pagination_config);
		
		// queries to be displayed
		$this->db->select('*')->join('meta', 'meta.user_id = topics.author_id');
		// searching for the splitted term
		foreach ($search_terms as $term)
		{
			$this->db->or_like('content', $term);
		}
		$this->db->order_by('datetime', 'desc');
		$paginated_query = $this->db->get('topics', $this->pagination_config['per_page'], $this->uri->segment(3));
		
		return $paginated_query->result();
	}
	
	function voting_check($user_id, $topic_id)
	{
		$this->db->select('*')->from('topic_votes')->where(array('voter_id' => $user_id, 'topic_id' => $topic_id));
		$query = $this->db->get();
		
		if ($query->num_rows() == 1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	function vote_direction_check($user_id, $topic_id)
	{
		$this->db->select('direction')->from('topic_votes')->where(array('voter_id' => $user_id, 'topic_id' => $topic_id));
		$query_row = $this->db->get()->row();
		
		return $query_row->direction;
	}
	
	function add_vote_record($user_id, $topic_id, $direction)
	{
		// populate the date to be inserted
		$vote_data = array(
			'topic_id' => $topic_id,
			'voter_id' => $user_id,
			'direction' => $direction
		);
		$this->db->insert('topic_votes', $vote_data);

		// update the topic's vote count 
		$this->db->select('vote_count')->where('topic_id', $topic_id);
		$query_row = $this->db->get('topics')->row();
			
		$data['vote_count'] = $query_row->vote_count;
		if ($direction == 'up')
		{
			$data['vote_count'] += 1;
		}
		else
		{
			$data['vote_count'] -= 1;
		}
		$this->db->where('topic_id', $topic_id);
		$this->db->update('topics', $data);
	}
	
	function remove_vote_record($user_id, $topic_id, $direction)
	{
		$this->db->where(array('voter_id' => $user_id, 'topic_id' => $topic_id));
		$this->db->delete('topic_votes');
		
		// update the topic's vote count 
		$this->db->select('vote_count')->where('topic_id', $topic_id);
		$query_row = $this->db->get('topics')->row();
			
		$data['vote_count'] = $query_row->vote_count;
		if ($direction == 'up')
		{
			$data['vote_count'] += 1;
		}
		else
		{
			$data['vote_count'] -= 1;
		}
		$this->db->where('topic_id', $topic_id);
		$this->db->update('topics', $data);
	}
	
	function get_topics_by($user_id)
	{
		// queries for pagination
		$this->db->select('*')->from('users')->join('topics', 'topics.author_id = users.id')->where(array('users.id' => $user_id, 'status' => 'published'));
		$query = $this->db->get();
		
		// pagination config
		$this->pagination_config['base_url'] = base_url() . 'topics/by/' . $user_id;
		$this->pagination_config['total_rows'] = $query->num_rows();
		$this->pagination_config['uri_segment'] = 4;
		$this->pagination->initialize($this->pagination_config);
		
		// queries to be displayed
		$this->db->select('*')->join('topics', 'topics.author_id = users.id')->join('meta', 'topics.author_id = meta.user_id')->where(array('users.id' => $user_id, 'status' => 'published'))->order_by('datetime', 'desc');
		$paginated_query = $this->db->get('users', $this->pagination_config['per_page'], $this->uri->segment(4));
		
		return $paginated_query->result();
	}
	
	function add_view_count($topic_id)
	{
		$this->db->where('topic_id', $topic_id);
		$row = $this->db->get('topics')->row();
		
		$data['view_count'] = $row->view_count;
		$data['view_count'] += 1;
		$this->db->where('topic_id', $topic_id);
		$this->db->update('topics', $data);
	}
	
	function add_activity($user_id, $topic_id)
	{
		$data['user_id'] = $user_id;
		$data['topic_id'] = $topic_id;
		$data['activity_type'] = 'topic';
		
		$this->db->insert('activities', $data);
	}
}

/* End of file topic_model.php */
/* Location: ./system/application/models/topic_model.php */