<!DOCTYPE html>
<html lang="en" ng-app="alt">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	
		<!-- CSS -->
		<link href="<?= base_url('resources/css/alt.min.css') ?>" rel="stylesheet">
	
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- JavaScript -->
		<!-- GoSquared Analytics -->
		<script>
			!function(g,s,q,r,d){r=g[r]=g[r]||function(){(r.q=r.q||[]).push(
			arguments)};d=s.createElement(q);q=s.getElementsByTagName(q)[0];
			d.src='//d1l6p2sc9645hc.cloudfront.net/tracker.js';q.parentNode.
			insertBefore(d,q)}(window,document,'script','_gs');

			_gs('GSN-267068-I');
		</script>
	</head>

	<body>

		<div class="container">
			<div class="header">
				<nav>
					<ul class="nav-main">
						<li role="presentation"><a href="<?= site_url() ?>">Home</a></li>
						<li role="presentation"><a href="<?= site_url('me') ?>">Me</a></li>
					</ul>
				</nav>
				<a href="<?= site_url() ?>"><h1>alt</h1></a>
			</div>