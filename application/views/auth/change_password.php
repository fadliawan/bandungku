<?php $this->load->view('templates/header') ?>

<section id="narrow_column">

	<div class='mainInfo'>
		<header id="form_header">
			<h1>Ganti Password</h1>
		</header>

		<div id="infoMessage"><?php echo $message;?></div>

		<?php echo form_open("auth/change_password");?>

			 <div class="form_field">
			  <label for="old">Password Lama</label>
			  <?php echo form_input($old_password);?>
			  </p>
			  </div>
			  
			   <div class="form_field">
			  <label for="new">Password Baru</label>
			  <?php echo form_input($new_password);?>
			  </p>
			  </div>
			  
			   <div class="form_field">
			  <label for="new_confirm">Konfirmasi Password Baru</label>
			  <?php echo form_input($new_password_confirm);?>
			  </p>
			  </div>
			  
			  <?php echo form_input($user_id);?>
			   <div class="form_field">
			 <input type="submit" name="submit" value="Ganti Password" class="basic_button" />
			  </div>
			  
		<?php echo form_close();?>

		<footer id="form_footer">
			<p><?php echo anchor('members/profile/' . $this->ion_auth->get_user()->id, '&laquo; Kembali ke Profilmu'); ?></p>
		</footer>
	</div>
</section>

<?php $this->load->view('templates/footer') ?>