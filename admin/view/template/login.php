<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');
	require_once(ROOT . '/admin/view/template/blocks/header.php');
?>
    <div class="container">
      	<div class="row justify-content-center">
        	<div class="col-12 col-md-5 col-xl-4 my-5">
          		<h1 class="display-4 text-center mb-3">Sign in</h1>
          		<p class="text-muted text-center mb-5">Free access to our dashboard.</p>
          		<form action="/auth" method="POST">
            		<div class="form-group">
              			<label class="form-label">Login</label>
              			<input type="text" class="form-control<?php if (!empty($_COOKIE['admin-err-login'])) { echo ' is-invalid'; } ?>" placeholder="user" autocomplete="off" name="login" value="<?php if (!empty($_COOKIE['admin-login'])) { echo $_COOKIE['admin-login']; } ?>">
              			<div class="invalid-feedback"><?php if (!empty($_COOKIE['admin-err-login'])) { echo $_COOKIE['admin-err-login']; } ?></div>
            		</div>
            		<div class="form-group">
              			<div class="row">
                			<div class="col">
                  				<label class="form-label">Password</label>
                			</div>
                			<!-- <div class="col-auto">
                  				<a href="password-reset-cover.html" class="form-text small text-muted">Forgot password?</a>
                			</div> -->
              			</div>
              			<div class="input-group input-group-merge">
                			<input class="form-control form-control-appended<?php if (!empty($_COOKIE['admin-err-password'])) { echo ' is-invalid'; } ?>" type="password" placeholder="Enter your password" autocomplete="off" name="password" value="<?php if (!empty($_COOKIE['admin-password'])) { echo $_COOKIE['admin-password']; } ?>">
                			<div class="input-group-append wf-cur-p wf-loginform-toggle-pass wf-loginform-view-pass">
	                            <span class="input-group-text">
	                                <i class="far fa-eye"></i>
	                            </span>
	                        </div>
              				<div class="invalid-feedback"><?php if (!empty($_COOKIE['admin-err-password'])) { echo $_COOKIE['admin-err-password']; } ?></div>
              			</div>
            		</div>
            		<button class="btn btn-lg w-100 btn-primary mb-3">Sign in</button>
          		</form>
        	</div>
      	</div>
    </div>
<?php
	require_once(ROOT . '/admin/view/template/blocks/footer.php');
