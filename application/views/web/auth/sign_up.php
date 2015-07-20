<div class="container-auth">
	<div class="site-wrapper">
		<div class="site-wrapper-inner">
			<?= form_open('signup', array(
				'class'      => 'form form-auth',
				'name'       => 'signup',
				'novalidate' => ''
			)) ?>
				<h2>Sign Up</h2>
				<?= form_error('username') ?>
				<div class="form-group <?php $error = form_error('username'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': signup.username.$dirty && signup.username.$invalid, 'has-success':  signup.username.$dirty && signup.username.$valid }">
					<label for="username">Username</label>
					<input type="text" class="form-control" name="username" placeholder="Username" tabindex="1" value="<?= set_value('username') ?>" ng-allowed="username" ng-unique="username" ng-model="user.username" ng-maxlength="45" required autofocus>
					<span class="help-block ng-hide" ng-messages="signup.username.$error" ng-show="signup.username.$dirty && signup.username.$invalid">
						<p ng-message="required">You're gonna need a username&hellip;</p>
						<p ng-message="maxlength">Your username's too long!</p>
						<p ng-message="allowed">The username {{user.username}} is not allowed!</p>
						<p ng-message="unique">The username {{user.username}} is already taken!</p>
					</span>
				</div>
				<?= form_error('email') ?>
				<div class="form-group <?php $error = form_error('email'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': signup.email.$dirty && signup.email.$invalid, 'has-success':  signup.email.$dirty && signup.email.$valid }">
					<label for="email">Email</label>
					<input type="email" class="form-control" name="email" placeholder="Email" tabindex="2" value="<?= set_value('email') ?>" ng-unique="email" ng-model="user.email" ng-maxlength="45" required>
					<span class="help-block ng-hide" ng-messages="signup.email.$error" ng-show="signup.email.$dirty && signup.email.$invalid">
						<p ng-message="required">Please give us you're email&hellip;</p>
						<p ng-message="email">That's not a valid email!</p>
						<p ng-message="maxlength">Your email's too long!</p>
						<p ng-message="unique">The email {{user.email.email}} is already being used!</p>
					</span>
				</div>
				<?= form_error('pass1') ?>
				<div class="form-group <?php $error = form_error('pass1'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': signup.pass1.$dirty && signup.pass1.$invalid, 'has-success':  signup.pass1.$dirty && signup.pass1.$valid, 'form-group-last': signup.pass1.$pristine }">
					<label for="pass1">Password</label>
					<input type="password" class="form-control" name="pass1" placeholder="Password" tabindex="3" ng-model="user.pass1" ng-class="{ 'form-control-last': signup.pass1.$pristine }" required>
					<span class="help-block ng-hide" ng-messages="signup.pass1.$error" ng-show="signup.pass1.$dirty && signup.pass1.$invalid">
						<p ng-message="required">Secure your account with a password&hellip;</p>
					</span>
				</div>
				<?= form_error('pass2') ?>
				<div class="form-group <?php $error = form_error('pass2'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': signup.pass2.$dirty && signup.pass2.$invalid, 'has-success':  signup.pass2.$dirty && signup.pass2.$valid }" ng-show="signup.pass1.$dirty || signup.pass1.$valid">
					<label for="pass2">Confirm Password</label>
					<input type="password" class="form-control form-control-last" name="pass2" placeholder="Confirm Password" tabindex="4" ng-match="signup.pass1" ng-class="{ 'form-control-last': signup.pass2.$pristine || signup.pass2.$valid }" ng-model="user.pass2" required>
					<span class="help-block ng-hide" ng-messages="signup.pass2.$error" ng-show="signup.pass2.$dirty && signup.pass2.$invalid">
						<p ng-message="required">Let's double check your password&hellip;</p>
						<p ng-message="match">Your passwords don't match!</p>
					</span>
				</div>
				<button type="submit" ng-disabled="signup.$invalid">Sign Me Up</button>
				<a class="btn-signup" href="<?= site_url('signin') ?>" role="button">I've Already Signed Up</a>
			<?= form_close() ?>
		</div>
	</div>
</div>