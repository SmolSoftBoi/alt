<!DOCTYPE html>
<html lang="en" ng-app="alt">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>alt - Admin<?php if (isset($title)): ?> - <?= $title ?><?php endif; ?></title>
		<meta name="copyright" content="Copyright (c) 2015 Kristian Matthews. All rights reserved.">
	
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

			_gs('<?= $this->config->item('token') ?>');
			<?php if ($this->session->authed === TRUE): ?>
				_gs('identify', {
					id: <?= $this->session->user['user_id'] ?>,
					name: '<?= $this->session->user['display_name'] ?>',
					email: '<?= $this->session->user['email']['email'] ?>',
					created_at: '<?= date('Y-m-d G:i:s', $this->session->user['c_timestamp']) ?>',
					custom: {
						color_hex: '<?= $this->session->user['color_hex'] ?>'
					}
				});
			<?php endif; ?>
		</script>
	</head>

	<body class="body-admin" ng-controller="alt">

		<nav class="navbar-admin">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?= site_url('admin') ?>">alt Admin</a>
				</div>
				<div class="collapse navbar-collapse" id="main-navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?= $this->session->user['display_name'] ?> <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="<?= site_url('signout') ?>">Sign Out</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>

		<div class="container-admin">
			<div class="row">
				<div class="sidebar">
					<ul class="nav-sidebar">
						<li class="<?php if (isset($nav)) if ($nav === 'dashboard'): ?>active<?php endif; ?>"><a href="<?= site_url('admin') ?>">Dashboard<?php if (isset($nav)) if ($nav === 'dashboard'): ?> <span class="sr-only">(current)</span><?php endif; ?></a></li>
						<li class="<?php if (isset($nav)) if ($nav === 'statuses'): ?>active<?php endif; ?>"><a href="<?= site_url('admin/statuses') ?>">Statuses<?php if (isset($nav)) if ($nav === 'statuses'): ?> <span class="sr-only">(current)</span><?php endif; ?></a></li>
						<li class="<?php if (isset($nav)) if ($nav === 'users'): ?>active<?php endif; ?>"><a href="<?= site_url('admin/users') ?>">Users<?php if (isset($nav)) if ($nav === 'users'): ?> <span class="sr-only">(current)</span><?php endif; ?></a></li>
					</ul>
				</div>
				<div class="main" <?php if (isset($ng['controller'])): ?>ng-controller="<?= $ng['controller'] ?>"<?php endif; ?>>