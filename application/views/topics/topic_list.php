<?php $this->load->view('templates/header'); ?>

<?php $this->load->view('templates/admin_nav'); ?>

	<h1>Semua Topik</h1>
	<?php echo anchor('topics/add/', 'Topik Baru', array('class' => 'basic_button')); ?>
	<p class="clearfix">Saat ini telah ada <?php echo $promoted_records; ?> topik untuk halaman depan.</p>
	<?php echo $this->session->flashdata('message'); ?>
	<table id="all_topics">
		<thead>
			<tr>		
				<th>ID</th>
				<th style="width:250px">Isi Topik</th>
				<th style="width:100px">Tanggal / Pukul</th>
				<th>Oleh</th>
				<th>via</th>
				<th>Kategori</th>
				<th>Tanggapan</th>
				<th>Status</th>
				<th style="width:70px"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($rows as $row) : ?>
			<tr>
				<td><?php echo $row->topic_id; ?></td>
				<td>
					<?php echo $row->content; ?><br />
					<?php if ($row->image) { ?>
					<img src="<?php echo base_url(); ?>images/application.png" alt="This topic has an image." title="This topic has an image." width="16" height="16" />
					<?php } ?>
				</td>
				<td><?php echo $row->datetime; ?></td>
				<td><?php echo $row->first_name; ?></td>
				<td><?php echo $row->twitter_via ? anchor('http://twitter.com/' . $row->twitter_via, '@' . $row->twitter_via) : ''; ?></td>
				<td><?php echo $row->category; ?></td>
				<td><?php echo $row->comment_count; ?></td>
				<td>
					<?php 
					if ($row->status == 'published')
					{
						echo "<span class='published'>" . $row->status . "</span><br />";
					}
					else
					{
						echo "<span class='pending'>" . $row->status . "</span><br />";
					}			
					?>
					<?php 
					if ($row->is_promoted == TRUE)
					{
						echo "<span class='promoted'>Frontpage</span>";
					}
					?>
				</td>
				<td>
					<?php 
					if ($row->status == 'pending')
					{
						echo anchor('topics/publish/' . $row->topic_id, 'Publish');	
					}
					else
					{
						echo anchor('topics/unpublish/' . $row->topic_id, 'Unpublish');
					}
					?>
					<br />
					<?php
					if ($row->is_promoted == TRUE)
					{
						echo anchor('topics/degrade/' . $row->topic_id, 'Degrade');
					}
					else
					{
						echo anchor('topics/promote/' . $row->topic_id, 'Promote');
					}
					?>
					<br />
					<?php echo anchor('topics/show/' . $row->topic_id, 'Lihat'); ?>
					<br />
					<?php echo anchor('topics/edit/' . $row->topic_id, 'Edit'); ?>
					<br />
					<?php echo anchor('topics/confirm_delete/' . $row->topic_id, 'Hapus'); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<nav id="pagination_admin">
		<ul>
		<?php echo $this->pagination->create_links(); ?>
		</ul>
	</nav>
	

<?php $this->load->view('templates/footer'); ?>