<div id="modal-user" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-user-label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h1 id="modal-user-label">{{users[activeUserKey].displayName}}</h1>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6">
						<dl class="dl-horizontal">
							<dt>User ID</dt>
							<dd><code>{{users[activeUserKey].userId}}</code></dd>
							<dt ng-if="users[activeUserKey].baseId">Base</dt>
							<dd ng-if="users[activeUserKey].baseId">{{users[activeUserKey].base.base}} {{users[activeUserKey].base.campus}}</dd>
							<dt>Language</dt>
							<dd>{{users[activeUserKey].language.language}}</dd>
							<dt>Country</dt>
							<dd>{{users[activeUserKey].country.country}}</dd>
							<dt>Username</dt>
							<dd>{{users[activeUserKey].username}}</dd>
							<dt ng-if="users[activeUserKey].name">Name</dt>
							<dd ng-if="users[activeUserKey].name">{{users[activeUserKey].name}}</dd>
							<dt ng-if="users[activeUserKey].bio">Bio</dt>
							<dd ng-if="users[activeUserKey].bio">{{users[activeUserKey].bio}}</dd>
							<dt ng-if="users[activeUserKey].location">Location</dt>
							<dd ng-if="users[activeUserKey].location">{{users[activeUserKey].location}}</dd>
							<dt ng-if="users[activeUserKey].siteUrl">Site</dt>
							<dd ng-if="users[activeUserKey].siteUrl"><a href="{{users[activeUserKey].siteUrl}}" target="_blank">{{users[activeUserKey].siteUrl}}</a></dd>
							<dt>Profile Picture</dt>
							<dd>
								<img class="img-responsive img-rounded" ng-src="{{users[activeUserKey].mediaUrl}}" ng-if="users[activeUserKey].mediaUrl">
								<span class="embed-responsive embed-responsive-4by3" ng-if=" ! users[activeUserKey].mediaUrl">
									<img class="img-responsive img-rounded embed-responsive-item" ng-trianglify="{ width: 200, height: 200, cell_size: 100, variance: 1, x_colors: ['#{{users[activeUserKey].colorHex}}', '#{{users[activeUserKey].colorHex}}'], y_colors: ['#fff', '#{{users[activeUserKey].colorHex}}'] }"></dd>
								</span>
							<dt>Color Hex</dt>
							<dd><span class="label" style="background-color: #{{users[activeUserKey].colorHex}};">#{{users[activeUserKey].colorHex}}</span></dd>
							<dt>Timezone</dt>
							<dd>{{users[activeUserKey].timezone}}</dd>
						</dl>
					</div>
					<div class="col-sm-6">
						<dl class="dl-horizontal">
							<dt>Status Count</dt>
							<dd>{{users[activeUserKey].statusCount}}</dd>
							<dt>Following Count</dt>
							<dd>{{users[activeUserKey].followingCount}}</dd>
							<dt>Followers Count</dt>
							<dd>{{users[activeUserKey].followersCount}}</dd>
							<dt>Vote Count</dt>
							<dd>{{users[activeUserKey].voteCount}}</dd>
							<dt ng-if="users[activeUserKey].baseTimestamp">Set Base</dt>
							<dd ng-if="users[activeUserKey].baseTimestamp">{{users[activeUserKey].baseIso8601 | date : 'd MMMM yyyy h:mm a'}}</dd>
							<dt>Signed Up</dt>
							<dd>{{users[activeUserKey].cIso8601 | date : 'd MMMM yyyy h:mm a'}}</dd>
							<dt ng-if="users[activeUserKey].uTimestamp">Last Updated</dt>
							<dd ng-if="users[activeUserKey].uTimestamp">{{users[activeUserKey].uIso8601 | date : 'd MMMM yyyy h:mm a'}}</dd>
						</dl>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>