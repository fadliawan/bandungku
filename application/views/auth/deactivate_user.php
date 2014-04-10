<?php $this->load->view('templates/header') ?>

<section id="narrow_column">
	<div class='mainInfo'>

		<header id="form_header">
			<h1 class="pageTitle">Deactivate User</h1>
			<p>Apakah kamu yakin mau men-deaktivasi user '<?php echo $user->username; ?>'</p>
		</header>		
		
		<?php echo form_open("auth/deactivate/".$user->id);?>
			
		  <div class="form_field">
			<label for="confirm">Deaktivasi</label>
			<input type="radio" name="confirm" value="yes" checked="checked" />
			<br />
			<label for="confirm">Batalkan</label>
			<input type="radio" name="confirm" value="no" />
		  </div>
		  
		  <?php echo form_hidden($csrf); ?>
		  <?php echo form_hidden(array('id'=>$user->id)); ?>
		  
		  <div class="form_field">
			<input type="submit" name="submit" value="Submit" class="basic_button" />
		  </div>

		<?php echo form_close();?>

	</div>
</section>

<?php $this->load->view('templates/footer') ?>