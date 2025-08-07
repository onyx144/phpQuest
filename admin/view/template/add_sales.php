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
    								<h1 class="header-title text-truncate">Generate Game Code</h1>
    							</div>
                                <div class="col-auto">
                                    <div class="btn btn-primary ms-2 wf-save-gamecode" data-norway-crone="<?php echo $this->settings['norway_crone']; ?>">Save</div>
                                </div>
    						</div>
    					</div>
    				</div>

    				<form action="/" method="POST" class="wf-form-add-sale">
    					<div class="row">
    						<div class="col-12 col-md-6">
    							<div class="form-group">
    								<label class="form-label">Date of sale</label>
    								<input type="text" class="form-control wf-addsale-datepicker" readonly="readonly" name="add-sale-date" value="" autocomplete="off">
    							</div>
    						</div>
    						<div class="col-12 col-md-6">
    							<div class="form-group">
    								<label class="form-label">Name of the game</label>
    								<select name="add-sale-gamename-id" class="form-control">
    									<option value="0" selected="selected"></option>
    									<?php
    									    foreach ($game_names as $game_name) {
    									    	echo '<option value="' . $game_name['id'] . '">' . $game_name['game_name'] . '</option>';
    									    }
    									?>
    								</select>
    							</div>
    						</div>
    					</div>
    					<div class="row">
    						<div class="col-12 col-md-6">
    							<div class="form-group">
    								<label class="form-label">Price, USD</label>
    								<input type="text" class="form-control" name="add-sale-price-usd" value="" autocomplete="off">
    							</div>
    						</div>
    						<div class="col-12 col-md-6">
    							<div class="form-group">
    								<label class="form-label">Price, NOK</label>
    								<input type="text" class="form-control" name="add-sale-price-nok" value="" autocomplete="off">
    							</div>
    						</div>
    					</div>
    					<div class="row">
    						<div class="col-12 col-md-6">
    							<div class="form-group">
    								<label class="form-label">Source</label>
    								<select name="add-sale-source-id" class="form-control">
    									<option value="0" selected="selected"></option>
    									<?php
    									    foreach ($sources as $source) {
    									    	echo '<option value="' . $source['id'] . '">' . $source['source_name'] . '</option>';
    									    }
    									?>
    								</select>
    							</div>
    						</div>
    						<div class="col-12 col-md-6">
    							<div class="form-group">
    								<label class="form-label">Code</label>
									<?php
										$count_code = 1;

										foreach ($codes as $code) {
											echo '<input type="text" class="form-control" name="add-sale-code-' . $count_code . '" value="' . $code . '" autocomplete="off"' . ($count_code > 1 ? ' style="display: none; margin-top: 5px;"' : '') . '>';

											$count_code++;
										}
									?>
    							</div>
    						</div>
    					</div>
    					<div class="row">
    						<div class="col-12 col-md-6">
    							<div class="form-group">
    								<label class="form-label">Game number</label>
    								<input type="text" class="form-control" name="add-sale-source-code" value="" autocomplete="off">
    							</div>
    						</div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Client email</label>
                                    <input type="text" class="form-control" name="add-sale-client-email" value="" autocomplete="off">
                                </div>
                            </div>
    					</div>
    					<div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Number of games (1-10)</label>
                                    <input type="number" class="form-control" name="add-sale-number-games" value="1" autocomplete="off" min="1" max="10">
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
