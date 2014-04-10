<?php $this->load->view('templates/header') ?>

<section id="narrow_column">

	<div class='mainInfo'>
		<header id="form_header">
			<h1>Lupa Password</h1>
			<p>Silakan masukkan email kamu agar kami dapat mengirim email untuk me-reset passwordmu.</p>
		</header>	

		<div id="infoMessage"><?php echo $message;?></div>

		<?php echo form_open("forgot_password");?>

			  <div class="form_field">
			  <label>Alamat Email</label>
			  <?php echo form_input($email);?>
			  </div>
			  
			  <div class="form_field"><input type="submit" name="submit" value="Submit" class="basic_button" /></div>
			  
		<?php echo form_close();?>
		
	</div>
</section>

<?php $this->load->view('templates/footer') ?>