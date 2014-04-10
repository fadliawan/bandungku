<?php $this->load->view('templates/header') ?>

<section id="narrow_column" class="login">
	<div class='mainInfo'>

		<header id="form_header">
			<h1 class="pageTitle">Login</h1>
			<p>Silakan login dengan email dan passwordmu.</p>
		</header>
		
		<div id="infoMessage"><?php echo $message;?></div>
		
		<?php echo form_open("auth/login");?>
			
		  <div class="form_field">
			<label for="email">Email</label>
			<?php echo form_input($email);?>
		  </div>
		  
		  <div class="form_field">
			<label for="password">Password</label>
			<?php echo form_input($password);?>
			<p><?php echo anchor('forgot_password', 'Saya lupa passwordnya.'); ?></p>
		   </div>
		  
		  <div class="form_field">
			  <label for="remember" style="float:left;margin-top:-0px">Ingat Saya:</label>
			  <?php echo form_checkbox('remember', '1', FALSE);?>
		   </div>
		  
		  <div class="form_field">
		  <input type="submit" name="submit" value="Login" class="basic_button" />
		  </div>
			
		  
		<?php echo form_close();?>
		
		<footer id="form_footer">
			<p>Tidak punya akun? Ayo <?php echo anchor('register', 'daftar sekarang'); ?>. Dengan membuat akun kamu dapat memiliki profil, memberikan tanggapan, dan menyarankan topik. Hidup Persib!</p>
		</footer>

	</div>
</section>

<?php $this->load->view('templates/footer') ?>