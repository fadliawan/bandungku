<?php $this->load->view('templates/header') ?>

<section id="narrow_column">
	<div class='mainInfo'>

        <header id="form_header">
			<h1 class="pageTitle">Hapus Topik</h1>
			<p>Apakah Anda yakin mau menghapus topik ini?</p>
		</header>	

        <div style="padding:15px">
        <p><?php echo $current_record->content; ?></p>
        <br class="clear" />
        <?php echo anchor('topics/delete/' . $current_record->topic_id, 'Hapus', array('class' => 'basic_button')); ?>
        <?php echo anchor('topics/all/' . $this->session->userdata('last_all_topics_page_number'), 'Tidak', array('class' => 'basic_button')); ?>
        </div>
	</div>
</section>

<?php $this->load->view('templates/footer') ?>
