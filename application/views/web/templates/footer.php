		<div class="container">
			<footer class="footer">
				<p>Copyright &copy; 2015 Kristian Matthews. All rights reserved.</p>
			</footer>
		</div>

		<div class="modal fade" id="signin-modal" tabindex="-1" role="dialog" aria-labelledby="signin-modal-label" aria-hidden="true">
			<div class="site-wrapper">
				<div class="site-wrapper-inner">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-body">
								<?= form_open('signin', array(
									'class'      => 'form',
									'name'       => 'signin',
									'novalidate' => ''
								)) ?>
									<h2 id="signin-modal-label">Sign In</h2>
									<?php if ( ! is_null($this->session->errors)) if ($this->session->errors['auth'] === FALSE): ?>
										<div class="alert alert-danger" role="alert">
											Looks like your username and password don't match&hellip;
										</div>
									<?php endif; ?>
									<?= form_error('username') ?>
									<div class="form-group <?php $error = form_error('username'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': signin.username.$dirty && signin.username.$invalid, 'has-success':  signin.username.$dirty && signin.username.$valid }">
										<label for="username">Username or Email</label>
										<input type="text" class="form-control" name="username" placeholder="Username or Email" tabindex="1" value="<?= set_value('username') ?>" ng-model="user.username" ng-maxlength="45" required autofocus>
										<span class="help-block ng-hide" ng-messages="signin.username.$error" ng-show="signin.username.$dirty && signin.username.$invalid">
											<p ng-message="required">Use your username or email to sign in&hellip;</p>
											<p ng-message="maxlength">Your username or email's too long!</p>
										</span>
									</div>
									<?= form_error('pass') ?>
									<div class="form-group form-group-last <?php $error = form_error('pass'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': signin.pass.$dirty && signin.pass.$invalid, 'has-success':  signin.pass.$dirty && signin.pass.$valid }">
										<label for="pass">Password</label>
										<input type="password" class="form-control form-control-last" name="pass" placeholder="Password" tabindex="2" ng-model="user.pass" ng-class="{ 'form-control-last': signin.pass.$pristine || signin.pass.$valid }" required>
										<span class="help-block ng-hide" ng-messages="signin.pass.$error" ng-show="signin.pass.$dirty && signin.pass.$invalid">
											<p ng-message="required">Use your password to sign in&hellip;</p>
										</span>
									</div>
									<div class="checkbox">
										<label><input type="checkbox" name="remember" value="TRUE" <?= set_checkbox('remember', 'TRUE') ?>> Remember me</label>
									</div>
									<button type="submit" ng-disabled="signin.$invalid">Sign Me In</button>
									<a class="btn-signup" href="<?= site_url('signup') ?>" role="button">Sign Me Up</a>
								<?= form_close() ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- JavaScript -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular-animate.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular-messages.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/trianglify/0.2.0/trianglify.min.js"></script>
		<script src="<?= base_url('resources/js/bootstrap.min.js') ?>"></script>
		<script src="<?= base_url('resources/js/alt.js') ?>"></script>

	</body>

</html>