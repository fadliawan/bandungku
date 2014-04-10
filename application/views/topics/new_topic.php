<?php $this->load->view('templates/header') ?>

	<section id="one_column">
	
		<?php $mode = $this->uri->segment(2); ?>
		<?php if ($mode == 'suggest') : ?>
			<h1>Sarankan Sebuah Topik</h1>
			<p style="font-size:1.2em;margin-top: 1em;">Punya uneg-uneg, pengalaman, atau opini tentang Bandung? Sarankan sebuah topik untuk kami. <br />Setelah disarankan, topikmu akan dipertimbangkan oleh admin untuk dipublish.</p>
			<?php echo form_open_multipart('topics/create_suggestion'); ?>
		<?php else: ?>
			<h1>Membuat Topik Baru</h1>
			<?php echo form_open_multipart('topics/create'); ?>
		<?php endif; ?>
			
		<div class="field">
			<label for="content">Isi Topik</label>
			<?php echo form_error('content'); ?>
			<?php 
			$data = array(
				'name' => 'content',
				'id' => 'content',
				'value' => set_value('content'),
				'rows' => 3,
				'cols' => 75
			);
		
			echo form_textarea($data); 
			?>
			<p>Panjang maksimum isi topik adalah 160 karakter.</p>
		</div>
		<div class="field">
			<label for="category">Kategori</label>
			<select id="category" name="category">
				<option value="Berita" <?php echo set_select('category', 'Berita', TRUE); ?>>Berita</option>
				<option value="Dokumenter" <?php echo set_select('category', 'Dokumenter'); ?>>Dokumenter</option>
				<option value="Hiburan" <?php echo set_select('category', 'Hiburan'); ?>>Hiburan</option>
				<option value="Twitter" <?php echo set_select('category', 'Twitter'); ?>>dari Twitter</option>
			</select>
		</div>
		<div class="field">
			<label>Gambar terkait <span>(bila ada)</span></label>
			<input type="file" name="userfile" size="20" />
			<p>Besar maksimum gambar adalah <strong>1 MB</strong> dengan jenis .jpg atau .png.</p>
			<?php echo $error; ?>
		</div>
		
		<?php if ($mode == 'add') : ?>
		<div class="field">
			<label for="twitter_via">Twitter via</label>
			<input type="text" name="twitter_via" value="<?php echo set_value('twitter_via'); ?>" id="twitter_via" />
			<p>Jika saran datang dari Twitter, masukkan username penyaran di sini.</p>
		</div>
		<?php endif; ?>

		<input type="submit" name="submit" value="Submit Topik" class="basic_button" />
		
		<?php echo form_close(); ?>
		
		<?php if ($this->ion_auth->is_admin()) : ?>
			<p><?php echo anchor('topics/all/' . $this->session->userdata('last_all_topics_page_number'), '&laquo; Kembali ke semua topik'); ?></p>
		<?php else : ?>
			<p><?php echo anchor('topics/display/' . $this->session->userdata('last_display_topics_page_number'), '&laquo; Kembali ke semua topik'); ?></p>
		<?php endif; ?>
	
	</section> <!-- END ONE COLUMN -->

<?php $this->load->view('templates/footer') ?>
