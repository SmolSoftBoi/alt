<h1 class="page-header">Statuses</h1>
<div class="table-responsive">
	<table class="table table-hover table-condensed">
		<thead>
			<tr>
				<th ng-click="reverse =! reverse; sortStatuses('statusId', reverse)">Status ID</th>
				<th ng-click="reverse =! reverse; sortStatuses('replyStatusId', reverse)">Reply Status ID</th>
				<th ng-click="reverse =! reverse; sortStatuses('status', reverse)">Status</th>
				<th ng-click="reverse =! reverse; sortStatuses('cTimestamp', reverse)">Posted</th>
				<th ng-click="reverse =! reverse; sortStatuses('uTimestamp', reverse)">Last Updated</th>
				<th></th>
			</tr>
		</thead>
		<tbody class="ng-hide" ng-repeat="(statusKey, status) in statuses | filter:search" ng-show="active">
			<tr>
				<td><code>{{status.statusId}}</code></td>
				<td><code ng-if="status.replyStatusId">{{status.replyStatusId}}</code></td>
				<td>{{status.status}}</td>
				<td>{{status.cIso8601 | date : 'd MMMM yyyy h:mm a'}}</td>
				<td><span ng-if="status.uTimestamp">{{status.uIso8601 | date : 'd MMMM yyyy h:mm a'}}</span></td>
				<td><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-status" ng-click="setActiveStatusKey(statusKey)">View Status</button></td>
			</tr>
		</tbody>
	</table>
	<div class="progress" ng-show="loading">
		<div class="progress-bar progress-bar-striped active" role="progressbar" style="width: 100%;">
			<span class="sr-only">Loading&hellip;</span>
		</div>
	</div>
</div>