<?php $this->load->view('templates/header') ?>

<section id="one_column">

	<h1>Edit Topik</h1>
	<?php echo $this->session->flashdata('message'); ?>
	<?php echo form_open_multipart('topics/update/' . $current_record->topic_id); ?>

	<div class="field">
		<label for="content">Isi Topik</label>
		<?php 
		$data = array(
			'name' => 'content',
			'id' => 'content',
			'value' => $current_record->content,
			'rows' => 3,
			'cols' => 75
		);

		echo form_textarea($data); 
		?>
	</div>
	<?php if ($current_record->image) { ?>
	<img src="<?php echo base_url() . 'uploads/originals/' . $current_record->image; ?>" alt="Gambar Terkait" />
	<?php } ?>
	<div class="field">
		<label>Kategori</label>
		<?php 
		$options = array(
			'Berita' => 'Berita',
			'Dokumenter' => 'Dokumenter',
			'Hiburan' => 'Hiburan',
			'Twitter' => 'dari Twitter'
		);

		echo form_dropdown('category', $options, $current_record->category);
		?>
	</div>
	<div class="field">
		<label>Gambar terkait <span>(gambar yang lama akan tergantikan)</span></label>
		<input type="file" name="userfile" size="20" />
		<p>Ukuran maksimum gambar adalah <strong>1 MB</strong> dengan jenis .jpg atau .png.</p>
		<?php echo $error; ?>
	</div>
	
	<?php if ($current_record->twitter_via != NULL) : ?>
	<div class="field">
		<label for="twitter_via">Twitter via</label>
		<input type="text" name="twitter_via" value="<?php echo $current_record->twitter_via; ?>" id="twitter_via" />
		<p>Jika saran datang dari Twitter, masukkan username penyaran di sini.</p>
	</div>
	<?php endif; ?>

	<input type="submit" name="submit" value="Update Topik" class="basic_button" />

	<?php echo form_close(); ?>

	<p><?php echo anchor('topics/all/' . $this->session->userdata('last_all_topics_page_number'), '&laquo; Kembali ke semua topik'); ?></p>
	
</section> <!-- END ONE COLUMN -->

<?php $this->load->view('templates/footer') ?>
