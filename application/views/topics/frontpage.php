<?php $this->load->view('templates/frontpage_header') ?>
	
	<section id="main_col">
		<section id="top_bar" class="clearfix">
			<h1>Hot Topics!</h1>
		</section>
		<?php echo $this->session->flashdata('message'); ?>
		<div id="hot_topics">
			<ul>
				<?php foreach ($promoted_topics as $topic) : ?>
				<li>
					<article class="clearfix">
						<?php if ($topic->image) { ?>
						<figure>
							<a class="attached_image" href="<?php echo base_url() . 'uploads/originals/' . $topic->image; ?>"><img src="<?php echo base_url() . 'uploads/originals/' . $topic->image; ?>" alt="Gambar Terkait" width="64" height="64" /></a>
						</figure>
						<?php } ?>
						<h1>
							<a href="topics/show/<?php echo $topic->topic_id; ?>"><?php echo $topic->content; ?></a>
						</h1>
						<footer>
							<a class="comment_count" href="topics/show/<?php echo $topic->topic_id; ?>/#responds"><?php echo $topic->comment_count; ?></a>
							<?php echo convert_to_ind_date($topic->datetime); ?>
						</footer>
					</article>
				</li>
				<?php endforeach; ?>
			</ul>
			<nav id="topic_nav" class="clearfix">
				<div id="suggest_and_all">
					<?php echo anchor('topics/suggest', '<span>Sarankan Topik</span>', array('id' => 'to_suggest_topic')); ?>
					<?php echo anchor('topics/all', '<span>Lihat Semua Topik</span>', array('id' => 'to_all_topics')); ?>
				</div>
			</nav>
		</div>
	</section> <!-- END MAIN COLUMN -->
	<section id="secondary_col">
	<?php echo form_open('topics/do_search', array('id' => 'search')); ?>
	<?php echo form_input('search_term', 'cari topik'); ?>
	<?php echo form_submit('submit', 'Search'); ?>
	<?php echo form_close(); ?>
    
	<?php if ( ! $this->ion_auth->logged_in()) : ?>
	
		<div id="intro">
			<p>Menurutmu.com adalah sebuah tempat online untuk menampung aspirasi, keluhan, maupun pujian terhadap segala sesuatu yang berlangsung di Bandung dan sekitarnya.</p>
			<p>Diskusikan setiap topik yang ada. Beritakan pendapatmu, ceritakan pengalamanmu. Mari berbagi demi Bandung yang lebih baik.</p>
		</div>
		
		<?php echo anchor('register', 'Daftar Sekarang', array('id' => 'big_signup')); ?>
		
		<h1>Siapa saja yang sudah gabung?</h1>
		<ul id="avatars">
			<?php foreach ($members as $member) : ?>
			<li>
				<a href="<?php echo site_url('members/profile/' . $member->id); ?>" title="<?php echo $member->first_name . ' ' . $member->last_name; ?>">
					<img src="<?php echo site_url('uploads/avatars/' . $member->avatar); ?>" alt="<?php echo $member->first_name . '\'s picture'?>" width="48" height="48" />
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
		
	<?php else: ?>

		<section id="profile_card">
			<div id="profile_card_inner">
				<?php $current_member = $this->ion_auth->get_user(); ?>
				<figure>
					<img src="<?php echo site_url('uploads/avatars/' . $current_member->avatar); ?>" alt="Foto Profil Kamu" title="Foto Profil Kamu" width="128" height="128" />
				</figure>
				<h2><?php echo anchor('members/profile/' . $current_member->id, $current_member->first_name . ' ' . $current_member->last_name); ?></h2>
				<p>di <strong><?php echo $current_member->address; ?></strong></p>
				<p id="his_hopes"><?php echo nl2br(substr($current_member->hopes, 0, 100)) . '...'; ?></p>
				<p id="his_comment_and_topic">
					<strong><?php echo $current_member->comments_posted; ?></strong> komentar<br />
					<?php if ($this->member_model->count_suggested_topics($current_member->id) > 0) : ?>
						
						<?php echo anchor('topics/by/' . $current_member->id, '<strong>' . $this->member_model->count_suggested_topics($current_member->id) . '</strong> topik'); ?>
						
					<?php else: ?>
					
						<strong>0</strong> topik
					
					<?php endif; ?>
				</p>
				<p><?php echo anchor('members/edit_profile', 'Edit Profil'); ?></p>
			</div>
		</section>
	
	<?php endif; ?>
	
	</section> <!-- END SECONDARY COLUMN -->

<?php $this->load->view('templates/footer') ?>