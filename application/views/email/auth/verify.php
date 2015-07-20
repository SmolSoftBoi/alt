<div class="jumbotron">
	<h2>You've Signed Yourself Up!</h2>
	<p class="lead">Hello <?= $username ?>!</p>
	<p>To verify your email, visit:<br>
	<a class="btn" href="<?= site_url('verify/' . $code) ?>" role="button">Verify My Email</a></p>
</div>