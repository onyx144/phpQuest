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
    								<h1 class="header-title text-truncate">Add game name</h1>
    							</div>
                                <div class="col-auto">
                                    <div class="btn btn-primary ms-2 wf-save-add-gamename">Save</div>
                                </div>
    						</div>
    					</div>
    				</div>

    				<form action="/" method="POST" class="wf-form-add-gamename">
    					<div class="row">
    						<!-- <div class="col-12 col-md-6"> -->
    						<div class="col-12">
    							<div class="form-group">
    								<label class="form-label">Game name *</label>
    								<input type="text" class="form-control" name="add-game-name" value="" autocomplete="off">
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
