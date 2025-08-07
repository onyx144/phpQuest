<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');
	require_once(ROOT . '/admin/view/template/blocks/header.php');
    require_once(ROOT . '/admin/view/template/blocks/nav.php');
?>
	<div class="main-content">
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col-12 col-lg-10 col-xl-8">
					<div class="header">
    					<div class="header-body">
    						<div class="row align-items-center">
    							<div class="col">
                                    <!-- <h6 class="header-pretitle">Overview</h6> -->
    								<h1 class="header-title text-truncate">Add User</h1>
    							</div>
                                <div class="col-auto">
                                    <div class="btn btn-primary ms-2 wf-save-add-user">Save</div>
                                </div>
    						</div>
    					</div>
    				</div>

    				<form action="/" method="POST" class="wf-form-add-user">
    					<div class="row">
    						<div class="col-12 col-md-6">
    							<div class="form-group">
    								<label class="form-label">Login (from 3 to 30 symbols) *</label>
    								<input type="text" class="form-control" name="add-user-login" value="" autocomplete="off">
    							</div>
    						</div>
    						<div class="col-12 col-md-6">
    							<div class="form-group">
    								<label class="form-label">Password (from 3 to 30 symbols) *</label>
    								<input type="text" class="form-control" name="add-user-password" value="" autocomplete="off">
    							</div>
    						</div>
    						<div class="col-12 col-md-6">
    							<div class="form-group">
    								<label class="form-label">Role</label>
    								<select name="add-user-role-id" class="form-control">
    									<option value="2" selected="selected">Admin</option>
    									<option value="3">Only view source</option>
    								</select>
    							</div>
    						</div>
    						<div class="col-12 col-md-6">
    							<div class="form-group">
    								<label class="form-label">Source</label>
    								<select name="add-user-source-id" class="form-control" disabled="disabled">
    									<option value="0" selected="selected"></option>
    									<?php
    									    foreach ($sources as $source) {
    									    	echo '<option value="' . $source['id'] . '">' . $source['source_name'] . '</option>';
    									    }
    									?>
    								</select>
    							</div>
    						</div>
    						<div class="col-12 col-md-6" style="margin-top: 15px;">
    							<div class="form-group">
    								<div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="add-user-status" checked="checked">
                                        <label class="custom-control-label" for="add-user-status">Status</label>
                                    </div>
    							</div>
    						</div>
    					</div>
    				</form>
				</div>
			</div>
		</div>
	</div>
<?php
	require_once(ROOT . '/admin/view/template/blocks/footer.php');
