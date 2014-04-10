<?php $this->load->view('templates/header') ?>

<section id="narrow_column">

	<div class='mainInfo'>
		<header id="form_header">
		<h1>Ganti Foto Profil</h1>
		</header>

		<div id="update_avatar">
			<figure>
				<img src="<?php echo base_url() . 'uploads/avatars/' . $current_user->avatar; ?>" alt="Default Profile Picture" width="128" height="128" />
			</figure>
			<?php echo form_open_multipart('members/update_avatar'); ?>

				<?php echo $this->session->flashdata('message'); ?>
			
				<p>
					<input type="file" name="userfile" size="20" /><br />
					Kami menyarankan gambar persegi berukuran 128 x 128 pixel.<br />
					Besar maksimum foto adalah 512kB dengan jenis .jpg atau .png.
				</p>
				
				<p>
					<input type="submit" name="submit" value="Ganti Foto" class="basic_button" />
				</p>
				
			<?php echo form_close(); ?>
			
			<br class="clear" />
			
			
		</div>
		
		<footer id="form_footer">
			<p><?php echo anchor('members/profile/' . $current_user->id, '&laquo; Kembali ke Profilmu'); ?></p>
		</footer>
	</div>
</section>
<?php $this->load->view('templates/footer') ?>