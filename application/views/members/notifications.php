<?php $this->load->view('templates/header') ?>

	<section id="main_col">
		<section id="latest_activity">
		
			<h1><?php echo $page_title; ?></h1>
			
			<p><?php echo anchor('members/profile/' . $this->ion_auth->get_user()->id, '&laquo; Kembali ke Profilmu'); ?></p>
			
			<?php if ($notifications) : ?>
			<?php $notifications = array_slice($notifications, 0, 30); ?>
		
			<ul>
				<?php foreach ($notifications as $notif) : ?>
					
					<li>
						<p>
						<?php if ($this->ion_auth->logged_in()
								&& $this->ion_auth->get_user()->id == $notif['commenter_id']) : ?>
							Kamu
						<?php else : ?>
							<?php echo anchor('members/profile/' . $notif['commenter_id'], '<strong>' . $notif['first_name'] . '</strong>'); ?>
						<?php endif; ?>
						
						<?php if (isset($notif['parent_commenter_id'])) : ?>
						
							<?php if ($notif['parent_commenter_id'] == $this->ion_auth->get_user()->id) : // if the logged in user authored the parent comment ?>
							
								mengomentari <?php echo anchor('topics/show/' . $notif['topic_id'] . '/#comment-' . $notif['comment_parent_id'], 'tanggapanmu'); ?> di topik
								
							<?php elseif ($notif['parent_commenter_id'] == $notif['commenter_id']) : // if the author commented on his own comment ?>
							
								mengomentari <?php echo anchor('topics/show/' . $notif['topic_id'] . '/#comment-' . $notif['comment_parent_id'], 'tanggapannya'); ?> di topik
								
							<?php else : // if somebody else commented on the comment ?>
							
								mengomentari <?php echo anchor('topics/show/' . $notif['topic_id'] . '/#comment-' . $notif['comment_parent_id'], 'tanggapan ' . $notif['parent_commenter_name']); ?> di topik
								
							<?php endif; ?>
							
							<?php $topic_text = substr($notif['content'], 0, 60) . '...'; ?>
							
						<?php else : ?>
						
							menanggapi
						
							<?php if (strlen($notif['content']) <= 80) : ?>
								<?php $topic_text = $notif['content']; ?>
							<?php else : ?>
								<?php $topic_text = substr($notif['content'], 0, 80) . '...'; ?>
							<?php endif; ?>
							
						<?php endif; ?>

						<?php echo anchor('topics/show/' . $notif['topic_id'] . '/#comment-' . $notif['comment_id'], $topic_text); ?>
					
						</p>
						<p><time><?php echo notification_date($notif['datetime']); ?></time></p>
					</li>
					
				<?php endforeach; ?>
			</ul>
		
			<?php else : ?>
			
			<p>Belum ada aktivitas terbaru.</p>
		
			<?php endif; ?>
		</section>
	</section> <!-- END MAIN COLUMN -->
	<section id="secondary_col">
		<?php echo form_open('topics/do_search', array('id' => 'search')); ?>
			<?php echo form_input('search_term', 'cari topik'); ?>
			<?php echo form_submit('submit', 'Search'); ?>
		<?php echo form_close(); ?>
		<nav id="category_filter">
			<ul class="clearfix">
				<?php 
					$pages = array(
						'semua' => 'Semua',
						'berita' => 'Berita',
						'hiburan' => 'Hiburan',
						'dokumenter' => 'Dokumenter',
						'twitter' => 'Saran dari Twitter'
					);
				?>
				<?php foreach ($pages as $key => $value) : ?>
				<li <?php if ($this->uri->segment(3) == $key) echo "class='current'"; ?>><?php echo anchor('topics/display/' . $key, $value ); ?></li>
				<?php endforeach; ?>
			</ul>
		</nav>
	</section> <!-- END SECONDARY COLUMN -->

<?php $this->load->view('templates/footer') ?>