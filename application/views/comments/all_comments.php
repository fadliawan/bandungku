<?php $this->load->view('templates/header'); ?>

<?php $this->load->view('templates/admin_nav'); ?>

	<h1>Semua Komentar</h1>
	<p>Semua komentar ada di sini.</p>
	<?php echo $this->session->flashdata('message'); ?>
	<table id="all_topics">
		<thead>
			<tr>		
				<th>ID</th>
				<th style="width:400px">Isi Komentar</th>
                    <th>ID Topik</th>
				<th>Oleh</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($all_comments as $row) : ?>
			<tr>
				<td><?php echo $row->comment_id; ?></td>
				<td><?php echo anchor('topics/show/' . $row->topic_id . '/#comment-' . $row->comment_id, nl2br($row->commenter_comment)); ?></td>
				<td><?php echo anchor('topics/show/' . $row->topic_id, $row->topic_id); ?></td>
                    <td><?php echo $row->first_name; ?></td>
				<td><?php echo anchor('comments/confirm_delete/' . $row->comment_id, 'Hapus'); ?></td>
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