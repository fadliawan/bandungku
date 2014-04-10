<?php $this->load->view('templates/header'); ?>

	<section id="main_col">
		<section id="top_bar" class="clearfix">
			<h1><?php echo $page_title; ?></h1>
		</section>
		<?php echo $this->session->flashdata('message'); ?>
		<section id="all_topics">
			<ul>
			<?php foreach ($rows as $row) : ?>
				<li>
					<article>
						<?php if ($row->image) { ?>
						<figure>
							<a class="attached_image" href="<?php echo base_url() . 'uploads/originals/' . $row->image; ?>"><img src="<?php echo base_url() . 'uploads/originals/' . $row->image; ?>" alt="Gambar Terkait" width="64" height="64" /></a>
						</figure>
						<?php } ?>
						<footer>
							<?php echo convert_to_ind_date($row->datetime); ?>
							<p>
								<strong><?php echo $row->category; ?></strong>
								<br />
								Tanggapan: <strong><?php echo anchor('topics/show/'.$row->topic_id.'/#responds', $row->comment_count); ?></strong>
								<br />
								Jumlah vote: <strong><?php echo anchor('topics/show/'.$row->topic_id, $row->vote_count); ?></strong>
							</p>
						</footer>
						<h1><?php echo anchor('topics/show/' . $row->topic_id, $row->content); ?></h1>						
					</article>
				</li>
			<?php endforeach; ?>
			</ul>
			<nav class="topic_nav clearfix">
				<div id="suggest_and_all">
					<?php echo anchor('topics/suggest', '<span>Sarankan Topik</span>', array('id' => 'to_suggest_topic')); ?>
				</div>
				<?php if ($this->pagination->create_links() != '') : ?>
					<ul id="pagination">
					<?php echo $this->pagination->create_links(); ?>
					</ul>
				<?php endif; ?>
			</nav>
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
		<div id="latest_comments">
			<?php if ($latest_comments) : ?>
			<h1>Tanggapan Terbaru</h1>
			<ul>
				
				<?php foreach ($latest_comments as $comment) : ?>
				
					<li><p><?php echo anchor('members/profile/' . $comment->commenter_id, '<strong>' . $comment->first_name . '</strong>'); ?> menanggapi <?php echo anchor('topics/show/' . $comment->topic_id . '/#comment-' . $comment->comment_id, substr($comment->content, 0, 30) . '...'); ?></p></li>
				
				<?php endforeach; ?>
				
			</ul>
			
			<?php else: ?>
			
			<p>Belum ada komentar.</p>
			
			<?php endif; ?>
		</div>
	</section>

<?php $this->load->view('templates/footer'); ?>