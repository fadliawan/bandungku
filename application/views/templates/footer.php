	</section> <!-- END SITE CONTENT -->
</div> <!-- END CONTAINER -->
<footer id="main_footer" class="clearfix">
	<div id="main_footer_inner">
		<p id="copyright">&copy; 2011 Menurutmu.com</p>
		<nav>
			<ul>
				<li><a class="to_fancybox" href="#about_us">Tentang Situs Ini</a></li>
				<li><a href="<?php echo base_url(); ?>topics/suggest">Sarankan Topik</a></li>
				<li><a class="to_fancybox" href="#contact_us">Kontak</a></li>
			</ul>
		</nav>
		<ul id="social_media">
			<li><a href="http://twitter.com/menurutmudotcom">Twitter</a></li>
			<li><a href="http://www.facebook.com/pages/Menurutmucom/208065985877207">Facebook</a></li>
		</ul>
		<div style="display:none">
			<div id="about_us" style="padding:10px;width:500px">
				<p>Kami adalah segerombolan mahasiswa dan mantan mahasiswa yang ingin berbagi cerita dan berita tentang Kota Bandung. Situs ini adalah salah satu pelampiasan untuk mengungkapkan segala jenis cerita, uneg-uneg, serta berbagai pengalaman lainnya yang kita alami dalam kehidupan sehari-hari. Di sini kita bisa berbagi, serta mendiskusikan segala macam hal yang berkaitan dengan Kota Bandung tercinta.</p>
				<p>Bagi yang memiliki perasaan yang sama dengan kami, ayo ikutan! Semoga suara dan aspirasi kita didengar oleh yang berada di atas sana. Semoga...</p>
			</div>
			<div id="contact_us" style="padding:10px">
				<p>Punya sesuatu yang ingin disampaikan? Silakan kontak kami di <a href="mailto:halo@menurutmu.com"><strong>halo @ menurutmu.com</strong></a>. Terima kasih!</p>
			</div>
		</div>
	</div>
</footer> <!-- END SITE FOOTER -->
<!-- Javascript Files -->
<!-- Twitter share -->
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<!-- Facebook share
<script type="text/javascript" src="http://static.ak.fbcdn.net/connect.php/js/FB.Share"></script>
-->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript">!window.jQuery && document.write(unescape('%3Cscript src="<?php echo base_url(); ?>scripts/lib/jquery-1.5.1.min.js"%3E%3C/script%3E'))</script>
<!-- jQuery Fancybox, already combined with menurutmu.min.js
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/plugin/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
-->
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/menurutmu.min.js"></script>

<?php if ($this->ion_auth->logged_in() && isset($current_record) && $comments) : ?>
		
	<script type="text/javascript">
		(function(a){a(".add_comment").toggle(function(b){var c=a(this).attr("name");a(this).text("Batal").parent().after('<form class="reply" action="<?php echo base_url(); ?>comments/submit/" method="post"><textarea name="comment" rows="3" cols="70" style="display:block"></textarea><input type="hidden" name="topic_id" value="<?php echo $current_record->topic_id; ?>" /><input type="hidden" name="comment_parent_id" value="'+c+'" /><input class="basic_button" type="submit" name="submit" value="Komentari" /></form>').next().find("textarea").focus();b.preventDefault()},function(b){a(this).text("Komentari").parent().next(".reply").remove();b.preventDefault()})})(jQuery);
	</script>
		
<?php endif; ?>

	<!-- Google Analytics -->
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-21925555-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>

</body>
</html>