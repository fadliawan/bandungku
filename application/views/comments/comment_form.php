	<?php echo validation_errors(); /* kok ga muncul ya? */?>
	
	<?php echo form_open('comments/submit/'); ?>
	
	<label for="comment">Menurutmu?</label>
	<?php 
	$data = array(
		'name' => 'comment',
		'id' => 'comment',
		'rows' => 15,
		'cols' => 70,
		'value' => set_value('comment')
	);
	
	echo form_textarea($data);
	?>
    
    <label for="response">Tanggapan</label>
	<select id="response" name="response">
		<option value="happy" <?php echo set_select('response', 'happy', TRUE); /* kok ga berfungsi ya? */?>>Senang</option>
		<option value="sad" <?php echo set_select('response', 'sad'); ?>>Kecewa</option>
		<option value="indifferent" <?php echo set_select('response', 'indifferent'); ?>>Biasa Saja</option>
	</select>
	
	<p>Oleh <strong><?php echo $this->ion_auth->get_user()->first_name." "; echo $this->ion_auth->get_user()->last_name; ?></strong> di <?php echo $this->ion_auth->get_user()->address; ?></p>
	
	<input type="submit" name="submit" value="Submit Tanggapan" class="basic_button" />
	<?php echo form_hidden('topic_id', $current_record->topic_id); ?>

	<?php echo form_close(); ?>
