<div id="modal-status" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-status-label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h1 id="modal-status-label">{{statuses[activeStatusKey].status}}</h1>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6">
						<dl class="dl-horizontal">
							<dt>Status ID</dt>
							<dd><code>{{statuses[activeStatusKey].statusId}}</code></dd>
							<dt ng-if="statuses[activeStatusKey].replyStatusId">Reply Status ID</dt>
							<dd ng-if="statuses[activeStatusKey].replyStatusId"><code>{{statuses[activeStatusKey].replyStatusId}}</code></dd>
							<dt ng-if="statuses[activeStatusKey].geoLat || statuses[activeStatusKey].geoLong">Geo</dt>
							<dd ng-if="statuses[activeStatusKey].geoLat || statuses[activeStatusKey].geoLong">{{statuses[activeStatusKey].geoLat}} {{statuses[activeStatusKey].geoLong}}</dd>
						</dl>
					</div>
					<div class="col-sm-6">
						<dl class="dl-horizontal">
							<dt>Score</dt>
							<dd>{{statuses[activeStatusKey].score}}</dd>
							<dt>Posted</dt>
							<dd>{{statuses[activeStatusKey].cIso8601 | date : 'd MMMM yyyy h:mm a'}}</dd>
							<dt ng-if="statuses[activeStatusKey].uTimestamp">Last Updated</dt>
							<dd ng-if="statuses[activeStatusKey].uTimestamp">{{statuses[activeStatusKey].uIso8601 | date : 'd MMMM yyyy h:mm a'}}</dd>
						</dl>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>