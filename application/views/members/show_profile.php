<?php $this->load->view('templates/header') ?>
	
	<section id="main_col">
	
		<?php echo $this->session->flashdata('message'); ?>
		
		<div id="member" class="clearfix">
			<section id="avatar_and_actions">
				<div id="avatar_and_actions_inner" class="clearfix">
					<figure>
						<img src="<?php echo base_url() . 'uploads/avatars/' . $current_user->avatar; ?>" alt="Foto Profil <?php echo $current_user->first_name?>" title="Foto Profil <?php echo $current_user->first_name?>" width="128" height="128" />
					</figure>
					
					<?php if ($current_user->fb_profile || $current_user->twitter_name) : ?>
					<p>
						<?php if ($current_user->fb_profile) : ?>
							<?php echo anchor('http://facebook.com/' . $current_user->fb_profile, $current_user->first_name, array('id' => 'facebook')); ?>
						<?php endif; ?>
						<?php if ($current_user->twitter_name) : ?>
							<?php echo anchor('http://twitter.com/' . $current_user->twitter_name, '@' . $current_user->twitter_name, array('id' => 'twitter')); ?>
						<?php endif; ?>
					</p>
					<?php else : ?>
						<?php if ($this->ion_auth->logged_in() && $this->ion_auth->get_user()->id == $current_user->id) : ?>
						
						<p><?php echo anchor('members/edit_profile', 'Tambahkan profil Facebook dan Twitter.'); ?></p>
						
						<?php endif; ?>					
					<?php endif; ?>
					
					<?php if ($this->ion_auth->logged_in() && $this->ion_auth->get_user()->id == $current_user->id) : ?>
						<?php echo anchor('topics/display/semua', '<span>Semua Topik</span>', array('id' => 'to_all_topics')); ?>
					<?php endif; ?>
					
				</div>
			</section>
			<section id="member_info">
				<div id="member_info_inner">
					<h1><?php echo $current_user->first_name . " " . $current_user->last_name; ?></h1>
					
					<p id="how_many">
						<strong><?php echo $comments_posted; ?></strong> komentar<br />
						<?php if ($topics_suggested > 0) : ?>
							<?php echo anchor('topics/by/' . $current_user->id, '<strong>' . $topics_suggested . '</strong> topik'); ?>
						<?php else: ?>
							<strong>0</strong> topik
						<?php endif; ?>
					</p>
					
					<h2>Domisili:</h2>
					<p><?php echo $current_user->address; ?></p>
					
					<h2>Harapan tentang Bandung:</h2>
					<p>
						<?php
							$hopes = nl2br($current_user->hopes);
							$hopes = explode('<br />', $hopes);
							echo implode('</p><p>', $hopes);
						?>			
					</p>
					
					<?php if ($this->ion_auth->logged_in() && $this->ion_auth->get_user()->id == $current_user->id) : ?>
					<nav id="edit_actions">
						<ul class="clearfix">
							<li><?php echo anchor('members/edit_profile', 'Edit Profil'); ?></li>
							<li><?php echo anchor('members/avatar', 'Ganti Foto Profil'); ?></li>
							<li><?php echo anchor('password', 'Ganti Password'); ?></li>
						</ul>
					</nav>
					<?php endif; ?>
				</div>				
			</section>
		</div>
	</section> <!-- END MAIN COLUMN -->
	<section id="secondary_col">
		<section id="latest_activity">
			<h1>Aktivitas Terbaru</h1>
			
			<?php if ($notifications) : ?>
			<?php $notifications = array_slice($notifications, 0, 5); ?>
		
			<ul>
				<?php foreach ($notifications as $notif) : ?>
					
					<li>
						<p style="margin-bottom:7px">
						<?php echo anchor('members/profile/' . $notif['commenter_id'], '<strong>' . $notif['first_name'] . '</strong>'); ?> 
 
						<?php if (isset($notif['parent_commenter_id'])) : ?>
							<?php if ($this->ion_auth->logged_in()
									&& $notif['parent_commenter_id'] == $this->ion_auth->get_user()->id) : // if the logged in user authored the parent comment ?>

									mengomentari <?php echo anchor('topics/show/' . $notif['topic_id'] . '/#comment-' . $notif['comment_parent_id'], 'tanggapanmu'); ?> di topik						
									
							<?php elseif ($notif['parent_commenter_id'] == $notif['commenter_id']) : // if the author commented on his own comment ?>
							
								mengomentari <?php echo anchor('topics/show/' . $notif['topic_id'] . '/#comment-' . $notif['comment_parent_id'], 'tanggapannya'); ?> di topik
								
							<?php elseif ($this->ion_auth->logged_in() && 
										$notif['parent_commenter_id'] == $this->ion_auth->get_user()->id) : // if current viewed member commented on logged in user's comment ?>
								
							<?php else : // if somebody else commented on the comment ?>
							
								mengomentari <?php echo anchor('topics/show/' . $notif['topic_id'] . '/#comment-' . $notif['comment_parent_id'], 'tanggapan ' . $notif['parent_commenter_name']); ?> di topik
								
							<?php endif; ?>
							
							<?php $topic_text = substr($notif['content'], 0, 25) . '...'; ?>
							
						<?php else : ?>
						
							menanggapi
						
							<?php if (strlen($notif['content']) <= 40) : ?>
								<?php $topic_text = $notif['content']; ?>
							<?php else : ?>
								<?php $topic_text = substr($notif['content'], 0, 40) . '...'; ?>
							<?php endif; ?>
							
						<?php endif; ?>
						
						<?php echo anchor('topics/show/' . $notif['topic_id'] . '/#comment-' . $notif['comment_id'], $topic_text); ?>
						</p>
						<time><?php echo notification_date($notif['datetime']); ?></time>
					</li>
					
				<?php endforeach; ?>
			</ul>
			
			<?php if ($this->ion_auth->logged_in() && $this->ion_auth->get_user()->id == $current_user->id) : ?>
			<p><?php echo anchor('members/notifications', 'Lihat Semua &raquo;'); ?></p>
			
			<?php endif; ?>
		
			<?php else : ?>
			
			<p>Belum ada aktivitas terbaru.</p>
		
			<?php endif; ?>
			<?php if ($this->ion_auth->logged_in() 
					&& $this->ion_auth->get_user()->id == $current_user->id 
					&& $this->member_model->get_his_activities($this->ion_auth->get_user()->id)) : ?>
				<p><?php echo anchor('members/activities', 'Lihat Aktivitas Kamu &raquo;'); ?></p>
			<?php endif; ?>
		</section>
	</section> <!-- END SECONDARY COLUMN -->

<?php $this->load->view('templates/footer') ?>