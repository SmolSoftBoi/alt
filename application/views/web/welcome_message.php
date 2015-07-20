<div class="container">
	<div class="jumbotron">
		<?php if ($this->session->authed === TRUE): ?>
			<img class="img-circle" <?php if ( ! is_null($this->session->user['media_url'])): ?>src="<?= $this->session->user['media_url'] ?>"<?php else: ?>ng-trianglify="{ width: 200, height: 200, cell_size: 100, variance: 1, x_colors: ['#{{alt.colorHex}}', '#{{alt.colorHex}}'], y_colors: ['#fff', '#{{alt.correctedColorHex}}'] }"<?php endif; ?>>
			<h2>Hello <?= $this->session->user['display_name'] ?>!</h2>
			<p><a class="btn btn-post" href="#" role="button" data-toggle="modal" data-target="#post-modal"><span class="sr-only">Post</span></a></p>
		<?php else: ?>
			<p><a class="btn btn-signup" href="<?= site_url('signup') ?>" role="button">Sign Me Up</a></p>
		<?php endif; ?>
	</div>
</div>
<div class="container-statuses" ng-controller="statuses" ng-scroll="load()">
	<div class="container-statuses-inner">
		<?php foreach ($statuses as $status): ?>
			<div class="row-status" ng-hide="statuses">
				<div class="status">
					<p><?= $status['status'] ?></p>
				</div>
				<div class="score">
					<a href="<?php if ($this->session->authed === TRUE): ?><?= site_url('vote/up/' . $status['status_id']) ?><?php else: ?><?= site_url('signup') ?><?php endif; ?>" class="vote-up <?php if (isset($status['vote'])) if ($status['vote']['vote'] > 0): ?>active<?php endif; ?>" role="button">Vote Up</a>
					<span><?= $status['score'] ?></span>
					<a href="<?php if ($this->session->authed === TRUE): ?><?= site_url('vote/down/' . $status['status_id']) ?><?php else: ?><?= site_url('signup') ?><?php endif; ?>" class="vote-down <?php if (isset($status['vote'])) if ($status['vote']['vote'] < 0): ?>active<?php endif; ?>" role="button">Vote Down</a>
				</div>
				<div class="timestamp">
					<span><?= timespan($status['c_timestamp'], now(), 1) ?> ago</span>
				</div>
			</div>
		<?php endforeach; ?>
		<noscript>
			<div class="row-status row-load">
				<div class="load">
					<p>Enable JavaScript to fetch more&hellip;</p>
				</div>
			</div>
		</noscript>
		<div class="row-status ng-hide" ng-repeat="status in statuses" ng-show="statuses">
			<div class="status">
				<p>{{status.status}}</p>
			</div>
			<div class="score">
				<a class="vote-up" role="button" ng-class="{ 'active': status.vote.vote > 0 }" ng-click="voteUp(status.statusId)">Vote Up</a>
				<span>{{status.score}}</span>
				<a class="vote-down" role="button" ng-class="{ 'active': status.vote.vote < 0 }" ng-click="voteDown(status.statusId)">Vote Down</a>
			</div>
			<div class="timestamp">
				<span ng-show="status.cTimestamp | timespan : 1">{{status.cTimestamp | timespan : 1}} Ago</span>
				<span ng-hide="status.cTimestamp | timespan : 1">Just Now</span>
			</div>
		</div>
		<div class="row-status row-load ng-hide" ng-if="loading" ng-show="loading">
			<div class="load">
				<div class="progress">
					<div class="progress-bar active" role="progressbar">
						<span>Fetching more&hellip;</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>