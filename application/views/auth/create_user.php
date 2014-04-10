<?php $this->load->view('templates/header') ?>

<section id="narrow_column">
	<div class='mainInfo'>

		<header id="form_header">
			<h1>Daftar</h1>
			<p>Silakan masukkan informasi tentang kamu di bawah ini.<br />Email dan password akan digunakan sebagai informasi untuk login.</p>
		</header>
		
		<div id="infoMessage"><?php echo $message;?></div>
		
		<?php echo form_open("register");?>
		  
		<div class="form_field">
		  <label for="email">Alamat Email</label>
		  <?php echo form_input($email);?>
		</div>
		  
		<div class="form_field">
		  <label for="password">Password</label>
		  <?php echo form_input($password);?>
		 </div>
		  
		  <div class="form_field">
		  <label for="password_confirm">Konfirmasi Password</label>
		  <?php echo form_input($password_confirm);?>
		  </div>
		  
		<div class="form_field">
		<label for="first_name">Nama Depan</label>
		  <?php echo form_input($first_name);?>
		 </div>
		  
		<div class="form_field">
		<label for="last_name">Nama Belakang</label>
		  <?php echo form_input($last_name);?>
		</div>
		  
		<div class="form_field">
		<label for="address">Domisili di Bandung</label>
		  <?php echo form_input($address);?>
		</div>
				
		<div class="form_field">
		<label for="hopes">Harapan untuk Bandung</label>
		  <?php echo form_textarea($hopes);?>
		 </div>
		  
		  <div class="form_field">
		 <input type="submit" name="submit" value="Daftarkan Saya!" class="basic_button" />
		 </div>

		  
		<?php echo form_close();?>

	</div>
</section>

<?php $this->load->view('templates/footer') ?>
