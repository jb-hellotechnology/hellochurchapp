	<script src="/assets/js/jquery.3.7.1.min.js"></script>
	<script src="/assets/js/tagify/tagify.js"></script>
	<script>
	$('button.menu').click(function(){
		$('.main-nav-container').toggleClass('show');
	});
	var input = document.querySelector('.tagify');
	new Tagify(input, {
	    // options here
	});
	</script>
</body>
</html>