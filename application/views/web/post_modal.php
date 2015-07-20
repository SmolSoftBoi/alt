<div class="modal fade" id="post-modal" tabindex="-1" role="dialog" aria-labelledby="post-modal-label" aria-hidden="true">
	<div class="site-wrapper">
		<div class="site-wrapper-inner">
			<div class="modal-dialog">
				<div class="modal-content">
					<?= form_open('post', array(
						'class'         => 'form form-post',
						'name'          => 'post',
						'novalidate'    => '',
						'ng-controller' => 'post',
						'ng-submit'     => 'submitPost($event)'
					)) ?>
						<div class="modal-body">
							<?= form_error('status') ?>
							<div class="form-group <?php $error = form_error('status'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': post.status.$dirty && post.status.$invalid, 'has-success':  post.status.$dirty && post.status.$valid }">
								<label for="status">Status</label>
								<textarea class="form-control" name="status" rows="2" placeholder="Status" tabindex="1" ng-model="status.status" ng-maxlength="150" required autofocus><?= set_value('status') ?></textarea>
								<span class="help-block" ng-messages="post.status.$error" ng-show="post.status.$dirty && post.status.$invalid">
									<p ng-message="required">What's on your mind?</p>
									<p ng-message="maxlength">Your status is too long!</p>
								</span>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" ng-disabled="post.$invalid">Post</button>
						</div>
					<?= form_close() ?>
				</div>
			</div>
		</div>
	</div>
</div>