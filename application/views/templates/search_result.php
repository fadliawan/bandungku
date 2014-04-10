<?php $this->load->view('templates/header') ?>

	<section id="main_col">
		<section id="top_bar" class="clearfix">
			<h1>Hasil pencarian untuk: <?php echo $this->session->userdata('search_term'); ?></h1>
		</section>
		
		<section id="all_topics">

			<?php if (isset($search_result) && $search_result != NULL) : ?> 
			<ul>
			
				<?php foreach ($search_result as $row) : ?>
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
				
			<?php else: ?>
				
				<?php echo $message; ?>
			
			<?php endif; ?>
			
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
			<?php echo form_input('search_term', $this->session->userdata('search_term')); ?>
			<?php echo form_submit('submit', 'Search'); ?>
		<?php echo form_close(); ?>
		<nav id="category_filter">
			<ul class="clearfix">
				<?php 
					$pages = array(
						'semua' => 'Semua',
						'berita' => 'Berita',
						'hiburan' => 'Hiburan',
						'dokumenter' => 'Dokumenter'
					);
				?>
				<?php foreach ($pages as $key => $value) : ?>
				<li <?php if ($this->uri->segment(3) == $key) echo "class='current'"; ?>><?php echo anchor('topics/display/' . $key, $value ); ?></li>
				<?php endforeach; ?>
			</ul>
		</nav>
	</section>

<?php $this->load->view('templates/footer') ?>