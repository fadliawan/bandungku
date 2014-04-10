<html>
<body>
	<style type="text/css">
		body { font-family: Arial, Helvetica, sans-serif; }
		h1 { font-size: 24px; line-height: 1.5em; }
		p { font: 14px/1.5em Arial; margin-bottom: 18px; }
	</style>
	<h1>Reset Password untuk <?php echo $identity;?></h1>
	<p>Silakan klik link berikut untuk me-reset password: <?php echo anchor('auth/reset_password/'. $forgotten_password_code, 'Reset Passwordku');?>.</p>
	<p style="font-size: 12px">
		<a href="http://menurutmu.com/">Menurutmu.com</a><br />
		Untuk informasi dan kontak, silakan hubungi <a href="mailto:halo@menurutmu.com">halo@menurutmu.com</a>
	</p>
</body>
</html>