<?php $this->load->view('templates/header') ?>

<section id="narrow_column">
	<div class='mainInfo'>

		<header id="form_header">
			<h1>Edit Profilmu</h1>
		</header>
		
		<?php echo $this->session->flashdata('message'); ?>
		
		<?php echo form_open("members/update_profile");?>
		  
		 <div class="form_field">
		 <label for="first_name">Nama Depan</label>
		  <?php echo form_input($first_name);?>
		  </div>
		  
		  <div class="form_field">
		  <label for="last_name">Nama Belakang</label>
		  <?php echo form_input($last_name);?>
		  </div>
		  
		 <div class="form_field">
		 <label for="fb_profile"> http://facebook.com/</label>
		  <?php echo form_input($fb_profile);?>
		  </div>
		  
		 <div class="form_field">
		 <label for="twitter_name">http://twitter.com/ </label>
		  <?php echo form_input($twitter_name);?>
		  </div>
		  
		  <div class="form_field">
		  <label for="address">Domisili di Bandung</label>
		  <?php echo form_input($address);?>
		  </div>
				
		 <div class="form_field">
		 <label for="hopes">Harapan untuk Bandung</label>
		  <?php echo form_textarea($hopes);?>
		  </div>		  
		  
		  
		  <div class="form_field"><input type="submit" name="submit" value="Update Profil" class="basic_button" /> </div>	
		  
		<?php echo form_close();?>
		
		<footer id="form_footer">
		<p><?php echo anchor('members/profile/' . $this->ion_auth->get_user()->id, '&laquo; Kembali ke Profilmu'); ?></p>
		</footer>

	</div>
</section>

<?php $this->load->view('templates/footer') ?>
