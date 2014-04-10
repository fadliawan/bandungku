<?php $this->load->view('templates/header') ?>

<section id="one_column">
	<div style="border:1px solid #ddd;margin-bottom:10px;padding:10px">
		<?php
		// converting newlines to paragraph
		$comment_content = nl2br($current_record->commenter_comment);
		$comment_content = explode("<br />", $comment_content);
		$comment_content = array_filter($comment_content);
		echo "<p>" . implode("</p><p>", $comment_content) . "</p>";
		?>
	</div>
	<p>Apakah Anda yakin mau menghapus komentar ini?</p>
	<?php echo anchor('comments/delete/' . $current_record->comment_id, 'Hapus', array('class' => 'basic_button')); ?>
	<?php if ($this->ion_auth->is_admin()) : ?>
		<?php echo anchor('comments/index/' . $this->session->userdata('last_all_comments_page_number'), 'Tidak', array('class' => 'basic_button')); ?>
	<?php else: ?>
		<?php echo anchor('topics/show/' . $this->session->userdata('last_shown_topic'), 'Tidak', array('class' => 'basic_button')); ?>

	<?php endif; ?>
</section>

<?php $this->load->view('templates/footer') ?>
