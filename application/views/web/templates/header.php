<!DOCTYPE html>
<html lang="en" ng-app="alt">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>alt<?php if (isset($title)): ?> - <?= $title ?><?php endif; ?></title>
		<meta name="keywords" content="Alt, EpicKris, Kristian, Matthews">
		<meta name="copyright" content="Copyright (c) 2015 Kristian Matthews. All rights reserved.">
	
		<!-- CSS -->
		<link href="<?= base_url('resources/css/alt.min.css') ?>" rel="stylesheet">
		<style>
			body {
				<?php if ($this->session->authed === TRUE): ?>
					background-color: #<?= $this->session->user['color_hex'] ?> !important;
				<?php endif; ?>
				background-color: #{{alt.topColorHex}} !important !important;
			}
		</style>
	
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
				_gs('identify', '<?= $this->session->user['user_id'] ?>', {
					id: <?= $this->session->user['user_id'] ?>,
					username: '<?= $this->session->user['username'] ?>',
					name: '<?= $this->session->user['name'] ?>',
					email: '<?= $this->session->user['email']['email'] ?>',
					created_at: '<?= date('Y-m-d G:i:s', $this->session->user['c_timestamp']) ?>',
					custom: {
						color_hex: '<?= $this->session->user['color_hex'] ?>'
					}
				});
			<?php endif; ?>
		</script>
	</head>

	<body ng-authed="<?php if ($this->session->authed === TRUE): ?>true<?php else: ?>false<?php endif; ?>" ng-controller="alt" ng-trianglify="{ cell_size: 100, variance: 1, x_colors: ['#{{alt.colorHex}}', '#{{alt.colorHex}}'], y_colors: ['#{{alt.correctedColorHex}}', '#fff'] }" ng-color-hex="<?php if ($this->session->authed === TRUE): ?><?= $this->session->user['color_hex'] ?><?php else: ?>337ab7<?php endif; ?>">

		<div class="container">
			<div class="header">
				<nav>
					<ul class="nav-main">
						<li role="presentation" class="<?php if (isset($nav)) if ($nav === 'home'): ?>active<?php endif; ?>"><a href="<?= site_url() ?>">Home<?php if (isset($nav)) if ($nav === 'home'): ?> <span class="sr-only">(current)</span><?php endif; ?></a></li>
						<?php if ($this->session->authed === TRUE): ?>
							<li role="presentation" class="dropdown <?php if (isset($nav)) if ($nav === 'me'): ?>active<?php endif; ?>">
								<a id="me" class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">Me<?php if (isset($nav)) if ($nav === 'me'): ?> <span class="sr-only">(current)</span><?php endif; ?></a>
								<ul class="dropdown-menu" role="menu" aria-labelledby="me">
									<div class="caret-me">
										<span class="outer"></span>
										<span class="inner"></span>
									</div>
									<li role="presentation" class="dropdown-header"><?= $this->session->user['display_name'] ?></li>
									<li role="presentation"><a href="<?= site_url('me') ?>">Me</a></li>
									<li role="presentation"><a href="<?= site_url('me/settings') ?>">My Settings</a></li>
									<li role="presentation" class="divider"></li>
									<?php if (in_array('admin', $this->session->user['roles'])): ?>
										<li role="presentation"><a href="<?= site_url('admin') ?>" target="_blank">Admin</a></li>
										<li role="presentation" class="divider"></li>
									<?php endif; ?>
									<li role="presentation"><a href="<?= site_url('signout') ?>">Sign Me Out</a></li>
								</ul>
							</li>
						<?php else: ?>
							<li role="presentation" class="<?php if (isset($nav)) if ($nav === 'me'): ?>active<?php endif; ?>"><a role="menuitem" tabindex="-1" href="<?= site_url('me') ?>">Me</a></li>
						<?php endif; ?>
					</ul>
				</nav>
				<a href="<?= site_url() ?>"><h1>alt</h1></a>
			</div>
		</div>