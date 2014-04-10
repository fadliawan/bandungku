<?php

class Member_model extends Model {

	private $pagination_config = array(
		'per_page' => 10,
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

	function get_all_members()
	{
		// queries for pagination
		$this->db->select('*')->from('users')->join('meta', 'meta.user_id = users.id');
		$query = $this->db->get();
		
		// pagination config
		$this->pagination_config['base_url'] = base_url() . 'members/index';
		$this->pagination_config['total_rows'] = $query->num_rows();
		$this->pagination_config['uri_segment'] = 3;
		$this->pagination->initialize($this->pagination_config);
		
		// queries to be displayed
		$this->db->select('*')->join('meta', 'meta.user_id = users.id')->order_by('users.id', 'desc');
		$paginated_query = $this->db->get('users', $this->pagination_config['per_page'], $this->uri->segment(3));
		
		return $paginated_query->result();
	}

	function get_current_user($user_id)
	{
		$this->db->select('*')->from('users')->where(array('users.id' => $user_id, 'active' => TRUE))->join('meta', 'meta.user_id = users.id');
		$query = $this->db->get();
		
		return $query->row();
	}

	function update_member_profile($username, $additional_data)
	{
		// update the 'users' table
		$this->db->where('id', $this->ion_auth->get_user()->id);
		$this->db->update('users', array('username' => $username));
		
		// update the 'meta' table
		$this->db->where('user_id', $this->ion_auth->get_user()->id);
		$this->db->update('meta', $additional_data);
		
		return TRUE;
	}
	
	function update_user_avatar($data)
	{
		$this->db->where('user_id', $this->ion_auth->get_user()->id);
		$this->db->update('meta', $data);
		
		return TRUE;
	}
	
	function resize_avatar($source_path, $source_width, $source_height)
	{
		// resize the image if width > 128px or height > 128px
		if ($source_width > 175 || $source_height > 175)
		{
			$resize_config = array(
				'image_library' => 'GD2',
				'source_image' => $source_path,
				'maintain_ratio' => TRUE,
				'width' => 175,
                    'height' => 175
			);
			$this->image_lib->initialize($resize_config);
			$this->image_lib->resize();
                $this->image_lib->clear();
		}
        
          $crop_config = array(
               'image_library' => 'GD2',
               'source_image' => $source_path,
               'maintain_ratio' => FALSE,
               'width' => 128,
               'height' => 128
           );
		$this->image_lib->initialize($crop_config);
		$this->image_lib->crop();
	}
	
		
	function get_frontpage_avatars()
	{
		$this->db->select('*')->join('users', 'users.id = meta.user_id')->where(array('group_id' => 2, 'active' => TRUE))->order_by('created_on', 'desc');
		$query = $this->db->get('meta', 12, 0);
		
		return $query->result();
	}
	
	function count_posted_comments($user_id)
	{
		$this->db->where('commenter_id', $user_id);
		$query = $this->db->get('comments');
		
		return $query->num_rows();
	}
	
	function count_suggested_topics($user_id)
	{
		$this->db->select('*')->from('users')->join('topics', 'topics.author_id = users.id')->where(array('users.id' => $user_id, 'status' => 'published'));
		$num_rows = $this->db->get()->num_rows();
		
		return $num_rows;
	}
	
	function get_activities($user_id)
	{
		$this->db->where('user_id', $user_id)->order_by('activity_id', 'asc');
		$activities = $this->db->get('activities')->result();
		
		if ( ! $activities)
		{
			return NULL;
		}
		
		$row_temp = array();
		$rows = array();
		foreach ($activities as $activity)
		{
			$current_row = array($activity->user_id, $activity->topic_id);
			if ( ! in_array($current_row, $row_temp))
			{
				$row_temp[] = $current_row;
				array_push($current_row, $activity->activity_id, $activity->comment_id);
				$rows[] = $current_row;
			}
		}
		
		// get other users' activity on each topic
		$notifications = array();
		foreach ($rows as $row)
		{
			// $row == array(USER_ID, TOPIC_ID, ACTIVITY_ID, COMMENT_ID);
			$this->db->select('activity_id, commenter_id, first_name, activities.topic_id, activities.comment_id, content, comments.datetime, activities.comment_parent_id')->where(array('activities.topic_id' => $row[1], 'activity_id >' => $row[2], 'activities.user_id !=' => $user_id, 'activity_type' => 'comment'))->join('comments', 'comments.comment_id = activities.comment_id')->join('meta', 'meta.user_id = activities.user_id')->join('topics', 'topics.topic_id = activities.topic_id')->order_by('activity_id', 'desc');
			$query_result = $this->db->get('activities')->result_array();
			
			foreach ($query_result as $notif)
			{
				$comment_parent_id = $notif['comment_parent_id'];
				if ($comment_parent_id)
				{
					$this->db->select('commenter_id, first_name')->where('comment_id', $comment_parent_id)->join('meta', 'meta.user_id = comments.commenter_id');
					$row = $this->db->get('comments')->row();

					$notif['parent_commenter_id'] = $row->commenter_id;
					$notif['parent_commenter_name'] = $row->first_name;
				}
				array_push($notifications, $notif);
			}
		}
		
		if ( ! $notifications)
		{
			return NULL;
		}
		
		// re-sorting by activity_id
		foreach ($notifications as $key => $row)
		{
			$activity_id[$key] = $row['activity_id'];
		}
		array_multisort($activity_id, SORT_DESC, $notifications);
		
		return $notifications;
	}
	
	function get_his_activities($user_id)
	{
		$this->db->select('activity_id, commenter_id, first_name, activities.topic_id, activities.comment_id, activities.comment_parent_id, content, comments.datetime')->where('activities.user_id', $user_id)->join('comments', 'comments.comment_id = activities.comment_id')->join('meta', 'meta.user_id = activities.user_id')->join('topics', 'topics.topic_id = activities.topic_id')->order_by('activity_id', 'desc');
		$result_array = $this->db->get('activities')->result_array();
		
		$notifications = array();
		foreach ($result_array as $notif)
		{
			$comment_parent_id = $notif['comment_parent_id'];
			if ($comment_parent_id)
			{
				$this->db->select('commenter_id, first_name')->where('comment_id', $comment_parent_id)->join('meta', 'meta.user_id = comments.commenter_id');
				$row = $this->db->get('comments')->row();

				$notif['parent_commenter_id'] = $row->commenter_id;
				$notif['parent_commenter_name'] = $row->first_name;
			}
			array_push($notifications, $notif);
		}
		
		return $notifications;
	}
}

/* End of file member_model.php */
/* Location: ./system/application/models/member_model.php */