<?php $this->load->view('templates/header') ?>	

	<section id="one_column">
		<?php echo $this->session->flashdata('message'); ?>
		<section id="voter">
			<?php if (isset($direction_voted) && $direction_voted == 'up') : ?>
				<span id="vote_up_inactive"><span>Voted Up</span></span>
				
				<?php if ($current_record->vote_count > 0) : ?>
					<?php echo 'Votes <strong style="color:#64b651">'; ?>
				<?php elseif ($current_record->vote_count < 0) : ?>
					<?php echo 'Votes <strong style="color:#ef5b52">'; ?>
				<?php else : ?>
					<?php echo 'Votes <strong>'; ?>
				<?php endif; ?>
				<?php echo $current_record->vote_count.'</strong> '; ?>
				
				<?php echo anchor('topics/vote/down/' . $current_record->topic_id, '<span>Unvote</span>', array('id' => 'vote_down')); ?>
			<?php elseif (isset($direction_voted) && $direction_voted == 'down' ) : ?>
				<?php echo anchor('topics/vote/up/' . $current_record->topic_id, '<span>Unvote</span>', array('id' => 'vote_up')); ?>
				
				<?php if ($current_record->vote_count > 0) : ?>
					<?php echo 'Votes <strong style="color:#64b651">'; ?>
				<?php elseif ($current_record->vote_count < 0) : ?>
					<?php echo 'Votes <strong style="color:#ef5b52">'; ?>
				<?php else : ?>
					<?php echo 'Votes <strong>'; ?>
				<?php endif; ?>
				<?php echo $current_record->vote_count.'</strong> '; ?>
				
				<span id="vote_down_inactive"><span>Voted Down</span></span>
			<?php elseif ( ! isset($direction_voted)) : ?>
				<?php echo anchor('topics/vote/up/' . $current_record->topic_id, '<span>Vote Up</span>', array('id' => 'vote_up')); ?>
				
				<?php if ($current_record->vote_count > 0) : ?>
					<?php echo 'Votes <strong style="color:#64b651">'; ?>
				<?php elseif ($current_record->vote_count < 0) : ?>
					<?php echo 'Votes <strong style="color:#ef5b52">'; ?>
				<?php else : ?>
					<?php echo 'Votes <strong>'; ?>
				<?php endif; ?>
				<?php echo $current_record->vote_count.'</strong> '; ?>
				
				<?php echo anchor('topics/vote/down/' . $current_record->topic_id, '<span>Vote Down</span>', array('id' => 'vote_down')); ?>
			<?php endif; ?>
		</section>
		<section id="current_topic">
			<article class="clearfix">
				<?php if ($current_record->image) { ?>
				<figure>
					<a class="attached_image" href="<?php echo base_url() . 'uploads/originals/' . $current_record->image; ?>"><img src="<?php echo base_url() . 'uploads/originals/' . $current_record->image; ?>" alt="Gambar Terkait" width="64" height="64" /></a>
				</figure>
				<?php } ?>
				<h1><?php echo $current_record->content; ?></h1>
				<footer>
				<?php echo convert_to_ind_date($current_record->datetime); ?><br />
				<a href="http://twitter.com/share" class="twitter-share-button" data-count="none" data-via="menurutmudotcom">Tweet</a>
				<!-- <a name="fb_share" type="button"></a> -->
				</footer>
			</article>
		</section>		
		
		<br class="clear" />
		
		<!-- TOPIC INFO -->
		<?php if ($current_record->user_id != 1) : ?>
			<p>Topik ini disarankan oleh <strong><?php echo anchor('members/profile/' . $current_record->id, $current_record->first_name . ' ' . $current_record->last_name); ?></strong>.</p>
		<?php endif; ?>
		
		<?php if ($current_record->twitter_via != NULL) : ?>
			<p>via <?php echo anchor('http://twitter.com/' . $current_record->twitter_via, '@' . $current_record->twitter_via); ?></p>
		<?php endif; ?>
		
		<?php if ($this->ion_auth->is_admin()) : ?>
			<p><?php echo anchor('topics/all/' . $this->session->userdata('last_all_topics_page_number'), '&laquo; Kembali ke semua topik'); ?></p>
		<?php else : ?>
			<p><?php echo anchor('topics/display/' . $this->session->userdata('last_display_topics_page_number'), '&laquo; Kembali ke semua topik'); ?></p>
		<?php endif; ?>
		
		<!-- COMMENT SECTION -->
		<?php if ($top_comments) : ?>
		
			<h3>Tanggapan terbaik:</h3>
			
			<ol class="comment_list">
			<?php
				$top_comment_data['comments'] = $top_comments;
				$this->load->view('comments/comment_list', $top_comment_data); 
			?>
			</ol>
			
		<?php endif; ?>
		
		<?php if ($comments) : ?>
		
			<h3 id="responds">Menurutmu?</h3>
		
			<ol class="comment_list">
			<?php
				$comment_data['comments'] = $comments;
				$this->load->view('comments/comment_list', $comment_data); 
			?>
			</ol>
			
		<?php else: ?>
		
			<p id="no_comments">Belum ada tanggapan untuk topik ini. Jadilah yang pertama! Menurutmu?</p>
		
		<?php endif; ?>
		
		<?php if ($this->ion_auth->logged_in()) :  ?>
		
			<?php $this->load->view('comments/comment_form'); ?>
		
		<?php else : ?>
		
			<p>Kamu harus <?php echo anchor('login', 'login'); ?> untuk memberikan tanggapan. Jika belum memiliki akun, <?php echo anchor('register', 'daftar'); ?> saja. Gampang dan cepat, kok!</p>
		
		<?php endif; ?>
	</section> <!-- END ONE COLUMN -->

<?php $this->load->view('templates/footer') ?>
