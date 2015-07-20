<div class="container">
	<div class="jumbotron">
		<img class="img-circle" <?php if ( ! is_null($this->session->user['media_url'])): ?>src="<?= $this->session->user['media_url'] ?>"<?php else: ?>ng-trianglify="{ width: 200, height: 200, cell_size: 100, variance: 1, x_colors: ['#{{alt.colorHex}}', '#{{alt.colorHex}}'], y_colors: ['#fff', '#{{alt.correctedColorHex}}'] }"<?php endif; ?>>
		<h2>Hello <?= $this->session->user['display_name'] ?>!</h2>
	</div>
</div>
<div class="container-settings">
	<div class="site-wrapper">
		<div class="site-wrapper-inner">
			<?= form_open_multipart('me/settings', array(
				'class'         => 'form form-settings',
				'name'          => 'settings',
				'novalidate'    => '',
				'ng-controller' => 'settings',
				'ng-user-id'    => $this->session->user['user_id']
			)) ?>
				<div role="tabpanel">
					<ul class="nav" role="tablist">
						<li role="presentation" class="active"><a href="#account" aria-controls="account" role="tab" data-toggle="tab">Account</a></li>
						<li role="presentation"><a href="#password" aria-controls="password" role="tab" data-toggle="tab">Password</a></li>
						<li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
					</ul>
					<div class="tabs">
						<div role="tabpanel" class="tab-pane in active" id="account">
							<?= form_error('username') ?>
							<div class="form-group <?php $error = form_error('username'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': settings.username.$dirty && settings.username.$invalid, 'has-success':  settings.username.$dirty && settings.username.$valid }">
								<label for="username">Username</label>
								<input type="text" class="form-control" name="username" placeholder="Username" tabindex="1" value="<?= set_value('username', $user['username']) ?>" ng-allowed="username" ng-unique="username" ng-exclude="<?= $user['username'] ?>" ng-model="user.username" ng-maxlength="45" required autofocus>
								<span class="help-block" ng-messages="settings.username.$error">
									<p class="ng-hide" ng-show="user.username"><?= strip_protocol(site_url()) ?>{{user.username}}</p>
									<p ng-message="required">You're gonna need a username&hellip;</p>
									<p ng-message="maxlength">Your username's too long!</p>
									<p ng-message="allowed">The username {{user.username}} is not allowed!</p>
									<p ng-message="unique">The username {{user.username}} is already taken!</p>
								</span>
							</div>
							<?= form_error('email') ?>
							<div class="form-group <?php $error = form_error('email'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': settings.email.$dirty && settings.email.$invalid, 'has-success':  settings.email.$dirty && settings.email.$valid }">
								<label for="email">Email</label>
								<input type="email" class="form-control" name="email" placeholder="Email" tabindex="2" value="<?= set_value('email', $user['email']) ?>" ng-unique="email" ng-exclude="<?= $user['email']['email'] ?>" ng-model="user.email.email" ng-maxlength="45" required>
								<span class="help-block ng-hide" ng-messages="settings.email.$error" ng-show="settings.email.$dirty && settings.email.$invalid">
									<p ng-message="required">Please give us you're email&hellip;</p>
									<p ng-message="email">That's not a valid email!</p>
									<p ng-message="maxlength">Your email's too long!</p>
									<p ng-message="unique">The email {{user.email.email}} is already taken!</p>
								</span>
							</div>
							<?= form_error('language_id') ?>
							<div class="form-group <?php $error = form_error('language_id'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': settings.language_id.$dirty && settings.language_id.$invalid, 'has-success':  settings.language_id.$dirty && settings.language_id.$valid }">
								<label for="username">Language</label>
								<select class="form-control" name="language_id" tabindex="3" ng-model="user.languageId" required>
									<?php foreach($languages as $language): ?>
										<option value="<?= $language['language_id'] ?>"><?= $language['language'] ?></option>
									<?php endforeach; ?>
								</select>
								<span class="help-block ng-hide" ng-messages="settings.language_id.$error" ng-show="settings.language_id.$dirty && settings.language_id.$invalid">
									<p ng-message="required">Select your language&hellip;</p>
								</span>
							</div>
							<?= form_error('country_id') ?>
							<div class="form-group <?php $error = form_error('country_id'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': settings.country_id.$dirty && settings.country_id.$invalid, 'has-success':  settings.country_id.$dirty && settings.country_id.$valid }">
								<label for="username">Country</label>
								<select class="form-control" name="country_id" tabindex="4" ng-model="user.countryId" required>
									<?php foreach($countries as $country): ?>
										<option value="<?= $country['country_id'] ?>"><?= $country['country'] ?></option>
									<?php endforeach; ?>
								</select>
								<span class="help-block ng-hide" ng-messages="settings.country_id.$error" ng-show="settings.country_id.$dirty && settings.country_id.$invalid">
									<p ng-message="required">Select your country&hellip;</p>
								</span>
							</div>
							<?= form_error('timezone') ?>
							<div class="form-group <?php $error = form_error('timezone'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': settings.timezone.$dirty && settings.timezone.$invalid, 'has-success':  settings.timezone.$dirty && settings.timezone.$valid, 'form-control-last': settings.timezone.$pristine || settings.timezone.$valid }">
								<label for="username">Timezone</label>
								<?= timezone_menu('UTC', 'form-control form-control-last', 'timezone', array(
									'tabindex' => '5',
									'ng-model' => 'user.timezone',
									'required' => ''
								)) ?>
								<span class="help-block ng-hide" ng-messages="settings.timezone.$error" ng-show="settings.timezone.$dirty && settings.timezone.$invalid">
									<p ng-message="required">Select your timezone&hellip;</p>
								</span>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="password">
							<?= form_error('pass') ?>
							<div class="form-group <?php $error = form_error('pass'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': settings.pass.$dirty && settings.pass.$invalid, 'has-success':  settings.pass.$dirty && settings.pass.$valid }">
								<label for="pass">Current Password</label>
								<input type="text" class="form-control" name="pass" placeholder="Current Password" tabindex="6" ng-model="user.pass">
								<span class="help-block ng-hide" ng-messages="settings.pass.$error" ng-show="settings.pass.$dirty && settings.pass.$invalid">
									<p ng-message="required">Secure your account with a password&hellip;</p>
								</span>
							</div>
							<?= form_error('pass1') ?>
							<div class="form-group <?php $error = form_error('pass1'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': settings.pass1.$dirty && settings.pass1.$invalid, 'has-success':  settings.pass1.$dirty && settings.pass1.$valid }">
								<label for="pass">New Password</label>
								<input type="text" class="form-control" name="pass1" placeholder="New Password" tabindex="7" ng-model="user.pass1">
								<span class="help-block ng-hide" ng-messages="settings.pass1.$error" ng-show="settings.pass1.$dirty && settings.pass1.$invalid">
									<p ng-message="required">Secure your account with a password&hellip;</p>
								</span>
							</div>
							<?= form_error('pass2') ?>
							<div class="form-group <?php $error = form_error('pass2'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': settings.pass2.$dirty && settings.pass2.$invalid, 'has-success':  settings.pass2.$dirty && settings.pass2.$valid }">
								<label for="pass2">Confirm Password</label>
								<input type="text" class="form-control" name="pass2" placeholder="Confirm Password" tabindex="8" ng-match="settings.pass1" ng-class="{ 'form-control-last': settings.pass2.$pristine || settings.pass2.$valid }" ng-model="user.pass2">
								<span class="help-block ng-hide" ng-messages="settings.pass2.$error" ng-show="settings.pass2.$dirty && settings.pass2.$invalid">
									<p ng-message="required">Let's double check your password&hellip;</p>
									<p ng-message="match">Your passwords don't match!</p>
								</span>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="profile">
							<?= form_error('media') ?>
							<div class="form-group <?php $error = form_error('media'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': settings.media.$dirty && settings.media.$invalid, 'has-success':  settings.media.$dirty && settings.media.$valid }">
								<label for="media">Profile Picture</label>
								<input type="file" class="form-control" name="media" placeholder="Profile Picture" tabindex="9" value="<?= set_value('media') ?>" ng-model="user.media">
								<span class="help-block" ng-messages="settings.media.$error" ng-show="settings.media.$dirty && settings.media.$invalid">
								</span>
							</div>
							<?= form_error('name') ?>
							<div class="form-group <?php $error = form_error('name'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': settings.name.$dirty && settings.name.$invalid, 'has-success':  settings.name.$dirty && settings.name.$valid }">
								<label for="name">Name</label>
								<input type="text" class="form-control" name="name" placeholder="Name" tabindex="10" value="<?= set_value('name', $user['name']) ?>" ng-model="user.name" ng-maxlength="45">
								<span class="help-block" ng-messages="settings.name.$error" ng-show="settings.name.$dirty && settings.name.$invalid">
									<p ng-message="maxlength">Your name's too long!</p>
								</span>
							</div>
							<?= form_error('bio') ?>
							<div class="form-group <?php $error = form_error('bio'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': settings.bio.$dirty && settings.bio.$invalid, 'has-success':  settings.bio.$dirty && settings.bio.$valid }">
								<label for="bio">Bio</label>
								<textarea class="form-control" name="bio" rows="2" placeholder="Bio" tabindex="11" ng-model="user.bio" ng-maxlength="145"><?= set_value('bio', $user['bio']) ?></textarea>
								<span class="help-block" ng-messages="settings.bio.$error" ng-show="settings.bio.$dirty && settings.bio.$invalid">
									<p ng-message="maxlength">Your bio's too long!</p>
								</span>
							</div>
							<?= form_error('location') ?>
							<div class="form-group <?php $error = form_error('location'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': settings.location.$dirty && settings.location.$invalid, 'has-success':  settings.location.$dirty && settings.location.$valid }">
								<label for="location">Location</label>
								<input type="text" class="form-control" name="location" placeholder="Location" tabindex="12" value="<?= set_value('location', $user['location']) ?>" ng-model="user.location" ng-maxlength="45">
								<span class="help-block" ng-messages="settings.location.$error" ng-show="settings.location.$dirty && settings.location.$invalid">
									<p ng-message="maxlength">Your location's too long!</p>
								</span>
							</div>
							<?= form_error('site_url') ?>
							<div class="form-group <?php $error = form_error('site_url'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': settings.site_url.$dirty && settings.site_url.$invalid, 'has-success':  settings.site_url.$dirty && settings.site_url.$valid }">
								<label for="site_url">Site</label>
								<input type="url" class="form-control" name="site_url" placeholder="Site" tabindex="13" value="<?= set_value('site_url', $user['site_url']) ?>" ng-model="user.siteUrl" ng-maxlength="45">
								<span class="help-block" ng-messages="settings.site_url.$error" ng-show="settings.site_url.$dirty && settings.site_url.$invalid">
									<p ng-message="url">Your site should start with <code>http://</code> or <code>https://</code>!</p>
									<p ng-message="maxlength">Your site's too long!</p>
								</span>
							</div>
							<?= form_error('color_hex') ?>
							<div class="form-group <?php $error = form_error('color_hex'); if ( ! empty($error)): ?>has-error<?php endif; ?>" ng-class="{ 'has-error': settings.color_hex.$dirty && settings.color_hex.$invalid, 'has-success':  settings.color_hex.$dirty && settings.color_hex.$valid }">
								<label for="color_hex">Color</label>
								<div class="input-group input-group-last" ng-class="{ 'input-group-last': settings.color_hex.$pristine || settings.color_hex.$valid }">
									<span class="input-group-addon" id="hex-addon">#</span>
									<input type="text" class="form-control" name="color_hex" placeholder="Color" tabindex="14" aria-describedby="hex-addon" value="<?= set_value('color_hex', $user['color_hex']) ?>" ng-model="user.colorHex" autocomplete="false" required>
									<span class="input-group-btn">
										<button class="btn-random-color" type="button" ng-click="randomColorHex()">Random</button>
									</span>
								</div>
								<span class="help-block ng-hide" ng-messages="settings.color_hex.$error" ng-show="settings.color_hex.$dirty && settings.color_hex.$invalid">
									<p ng-message="required">You're gonna need a username&hellip;</p>
								</span>
							</div>
						</div>
						<button type="submit" ng-disabled="settings.$invalid">Update My Settings</button>
						<a href="#" class="btn-archive">Get My Archive</a>
					</div>
				</div>
			<?= form_close() ?>
		</div>
	</div>
</div>