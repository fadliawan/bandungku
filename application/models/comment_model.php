<?php

class Comment_model extends Model {

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

	function Comment_model()
	{
		parent::Model();
	}
	
	function insert_record($data)
	{	
		$this->db->insert('comments', $data);
		// add current comment insertion to activities
		// select the latest comment first
		$this->db->select_max('comment_id');
		$comment_id = $this->db->get('comments')->row()->comment_id;
		// then select other fields, insert them into the activities table
		$this->db->where('comment_id', $comment_id);
		$query_row = $this->db->get('comments')->row();
		$this->add_activity($query_row->comment_id, $query_row->comment_parent_id, $query_row->commenter_id, $query_row->topic_id);
	}
	
	function get_records($topic_id)
	{
		$this->db->where(array('topic_id' => $topic_id, 'comment_parent_id' => NULL))->join('meta', 'meta.user_id = comments.commenter_id')->join('users', 'users.id = comments.commenter_id')->order_by('datetime', 'asc');
		$query = $this->db->get('comments');
		
		return $query->result();
	}
	
	function get_top_records($topic_id)
	{
		$this->db->where(array('topic_id' => $topic_id, 'comment_parent_id' => NULL, 'vote_count >=' => 5))->join('meta', 'meta.user_id = comments.commenter_id')->join('users', 'users.id = comments.commenter_id')->order_by('vote_count', 'desc')->limit(2);
		$query = $this->db->get('comments');
		
		return $query->result();
	}
	
	function add_comment_count($topic_id)
	{
		// topic's comment count
		$this->db->where('topic_id', $topic_id);
		$query_row = $this->db->get('topics')->row();
		// current comment counts
		$data['comment_count'] = $query_row->comment_count;
		$data['comment_count'] += 1;
		$this->db->where('topic_id', $topic_id);
		$this->db->update('topics', $data);
		
		// commenter's posted comment count
		$this->db->where('user_id', $this->ion_auth->get_user()->id);
		$query_row = $this->db->get('meta')->row();
		// current posted comment count
		$commenter_data['comments_posted'] = $query_row->comments_posted;
		$commenter_data['comments_posted'] += 1;
		$this->db->where('user_id', $this->ion_auth->get_user()->id);
		$this->db->update('meta', $commenter_data);
	}
	
	function substract_comment_count($comment_id)
	{
		// get how many comments to be deleted
		$this->db->where('comment_id', $comment_id)->or_where('comment_parent_id', $comment_id);
		$to_be_deleted = $this->db->get('comments');
		
		// topic's comment count
		$this->db->where('topic_id', $to_be_deleted->row()->topic_id);
		$query_row = $this->db->get('topics')->row();
		// current comment counts
		$data['comment_count'] = $query_row->comment_count;
		$data['comment_count'] -= $to_be_deleted->num_rows();
		$this->db->where('topic_id', $to_be_deleted->row()->topic_id);
		$this->db->update('topics', $data);
	}

	function get_all_comments()
	{
		// queries for pagination
		$this->db->select('*')->from('comments')->join('meta', 'meta.user_id = comments.commenter_id');
		$query = $this->db->get();
		
		// pagination config
		$this->pagination_config['base_url'] = base_url() . 'comments/index';
		$this->pagination_config['total_rows'] = $query->num_rows();
		$this->pagination_config['uri_segment'] = 3;
		$this->pagination->initialize($this->pagination_config);
		
		// queries to be displayed
		$this->db->select('*')->join('meta', 'meta.user_id = comments.commenter_id')->order_by('datetime', 'desc');
		$paginated_query = $this->db->get('comments', $this->pagination_config['per_page'], $this->uri->segment(3));

		return $paginated_query->result();
	}
	
	function current_record($comment_id)
	{
		$this->db->where('comment_id', $comment_id)->join('meta', 'meta.user_id = comments.commenter_id');
		$query = $this->db->get('comments');
		$current_record = $query->row();
		
		return $current_record;
	}
	
	function delete_record($comment_id)
	{
		// substract the comment count first!
		$this->substract_comment_count($comment_id);
		// delete the comment(s)
		$this->db->where('comment_id', $comment_id)->or_where('comment_parent_id', $comment_id);
		$this->db->delete('comments');
	}
	
	function add_activity($comment_id, $comment_parent_id, $user_id, $topic_id)
	{
		$data['comment_id'] = $comment_id;
		$data['comment_parent_id'] = $comment_parent_id;
		$data['user_id'] = $user_id;
		$data['topic_id'] = $topic_id;
		$data['activity_type'] = 'comment';
		
		$this->db->insert('activities', $data);
	}
	
	function get_latest_comments()
	{
		$this->db->select('comment_id, commenter_id, comments.datetime, comments.topic_id, first_name, content')->join('topics', 'topics.topic_id = comments.topic_id')->join('meta', 'meta.user_id = comments.commenter_id')->limit(7)->order_by('datetime', 'desc');
		$query = $this->db->get('comments');
		
		return $query->result();
	}
	
	function voting_check($voter_id, $comment_id)
	{
		$this->db->select('direction')->from('comment_votes')->where(array('voter_id' => $voter_id, 'comment_id' => $comment_id));
		$row = $this->db->get()->row();
		
		if ($row)
		{
			return $row->direction;
		}
		else
		{
			return NULL;
		}
	}
	
	function add_vote_record($voter_id, $comment_id, $direction)
	{
		$vote_data = array(
			'voter_id' => $voter_id,
			'comment_id' => $comment_id,
			'direction' => $direction
		);
		$this->db->insert('comment_votes', $vote_data);
		
		// update vote count
		$data['vote_count'] = $this->db->get_where('comments', array('comment_id' => $comment_id))->row()->vote_count;
		if ($direction == 'up')
		{
			$data['vote_count'] += 1;
		}
		else
		{
			$data['vote_count'] -= 1;
		}
		$this->db->where('comment_id', $comment_id);
		$this->db->update('comments', $data);
	}
	
	function remove_vote_record($voter_id, $comment_id, $direction)
	{
		$this->db->where(array('voter_id' => $voter_id, 'comment_id' => $comment_id));
		$this->db->delete('comment_votes');
		
		// update vote count
		$data['vote_count'] = $this->db->get_where('comments', array('comment_id' => $comment_id))->row()->vote_count;
		if ($direction == 'up')
		{
			$data['vote_count'] += 1;
		}
		else
		{
			$data['vote_count'] -= 1;
		}
		$this->db->where('comment_id', $comment_id);
		$this->db->update('comments', $data);
	}
}

/* End of file comment_model.php */
/* Location: ./system/application/models/comment_model.php */