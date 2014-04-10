<?php $this->load->view('templates/header'); ?>

<?php $this->load->view('templates/admin_nav'); ?>

	<h1>Semua Member</h1>
	<p>Semua member ada di bawah ini.</p>
	<table id="all_topics">
		<thead>
			<tr>		
				<th>ID</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Avatar</th>
				<th>Email</th>
				<th>Bergabung Sejak</th>
				<th>Terakhir Login</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($members as $row) : ?>
			<tr>
				<td><?php echo $row->id; ?></td>
				<td><?php echo anchor('members/profile/' . $row->id, $row->first_name); ?></td>
				<td><?php echo $row->last_name; ?></td>
				<td><img src="<?php echo base_url() . 'uploads/avatars/' . $row->avatar; ?>" alt="User's avatar" width="48" height="48" /></td>
				<td><?php echo $row->email; ?></td>
				<td><?php echo date('d-m-Y', $row->created_on); ?></td>
				<td><?php echo date('d-m-Y <br /> H:i:s', $row->last_login); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<nav id="pagination_admin">
		<ul>
		<?php echo $this->pagination->create_links(); ?>
		</ul>
	</nav>

<?php $this->load->view('templates/footer'); ?>