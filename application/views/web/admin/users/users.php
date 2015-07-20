<h1 class="page-header">Users</h1>
<div class="table-responsive">
	<table class="table table-hover table-condensed">
		<thead>
			<tr>
				<th ng-click="reverse =! reverse; sortUsers('userId', reverse)">User ID</th>
				<th ng-click="reverse =! reverse; sortUsers('username', reverse)">Username</th>
				<th ng-click="reverse =! reverse; sortUsers('name', reverse)">Name</th>
				<th ng-click="reverse =! reverse; sortUsers('cTimestamp', reverse)">Signed Up</th>
				<th ng-click="reverse =! reverse; sortUsers('uTimestamp', reverse)">Last Updated</th>
				<th></th>
			</tr>
		</thead>
		<tbody class="ng-hide" ng-repeat="(userKey, user) in users | filter:search" ng-show="active">
			<tr>
				<td><code>{{user.userId}}</code></td>
				<td>{{user.username}}</td>
				<td>{{user.name}}</td>
				<td>{{user.cIso8601 | date : 'd MMMM yyyy h:mm a'}}</td>
				<td>{{user.uIso8601 | date : 'd MMMM yyyy h:mm a'}}</td>
				<td><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-user" ng-click="setActiveUserKey(userKey)">View User</button></td>
			</tr>
		</tbody>
	</table>
	<div class="progress" ng-show="loading">
		<div class="progress-bar progress-bar-striped active" role="progressbar" style="width: 100%;">
			<span class="sr-only">Loading&hellip;</span>
		</div>
	</div>
</div>