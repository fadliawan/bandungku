<!DOCTYPE html>

<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>

	<!-- Wilujeng Sumping. Feel free to check the source code out. Greetings from Bandung! -->
	
	<meta charset="utf-8" />	

	<title><?php $page_title = isset($page_title) ? $page_title." | " : ""; echo $page_title; ?>Menurutmu.com</title>
	
	<meta name="author" content="menurutmu.com">
	<meta name="description" content="Sebuah tempat online untuk menyimpan aspirasi masyarakat Kota Bandung dan sekitarnya." />
	<meta name="keywords" content="bandung, topik, aspirasi" />
	
	<link rel="shortcut icon" href="<?php echo base_url(); ?>images/favicon.ico">

	<!-- CSS Files -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>style/css/main.min.css" media="screen">
	
	<?php if ($this->ion_auth->is_admin()) : ?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>style/css/admin.min.css" media="screen">
	<?php endif; ?>
	
	<!-- CSS for jQuery Fancybox -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>scripts/plugin/fancybox/jquery.fancybox-1.3.4.min.css" media="screen">

	<!-- Javascript Files -->	
	<!-- Modernizr -->
	<script type="text/javascript" src="http://ajax.cdnjs.com/ajax/libs/modernizr/1.7/modernizr-1.7.min.js"></script>
	<script type="text/javascript">!window.Modernizr && document.write(unescape('%3Cscript src="<?php echo base_url(); ?>scripts/lib/modernizr-1.7.min.js"%3E%3C/script%3E'))</script>
</head>

<body>

<header id="main_header">
	<div id="header_bar">
		<div id="header_bar_inner" class="clearfix">
			<div id="logo">
				<a href="<?php echo base_url(); ?>">Menurutmu.com</a>
			</div>
			<nav id="member_action">
				<?php if ( ! $this->ion_auth->logged_in()) : ?>
					<a id="login" href="<?php echo site_url('login'); ?>">Login</a>
					<a id="register" href="<?php echo site_url('register'); ?>">Daftar</a>
				<?php else : ?>
					<a id="to_profile" href="<?php echo site_url('members/profile/' . $this->ion_auth->get_user()->id); ?>"><strong><?php echo $this->ion_auth->get_user()->first_name . ' ' . $this->ion_auth->get_user()->last_name; ?></strong></a>
					<a id="logout" href="<?php echo site_url('logout'); ?>">Logout</a>
				<?php endif; ?>
			</nav>
		</div>
	</div>
</header> <!-- END SITE HEADER -->
<div id="container">	
	<section id="main_content" class="clearfix">
		

