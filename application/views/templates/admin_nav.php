<nav id="admin_nav">
	<ul class="clearfix">
		<li <?php if ($this->uri->segment(1) == 'topics') { echo 'class="active"'; } ?>><?php echo anchor('topics/all', 'Semua Topik'); ?></li>
		<li <?php if ($this->uri->segment(1) == 'comments') { echo 'class="active"'; } ?>><?php echo anchor('comments', 'Semua Komentar'); ?></li>
		<li <?php if ($this->uri->segment(1) == 'members') { echo 'class="active"'; } ?>><?php echo anchor('members', 'Semua Member'); ?></li>
		<li <?php if ($this->uri->segment(1) == 'auth') { echo 'class="active"'; } ?>><?php echo anchor('auth', 'Activate/Deactivate'); ?></li>
	</ul>
</nav>