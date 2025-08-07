<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');
	require_once(ROOT . '/admin/view/template/blocks/header.php');
    require_once(ROOT . '/admin/view/template/blocks/nav.php');
?>
	<div class="main-content">
    	<div class="container-fluid">
    		<div class="row justify-content-center">
    			<div class="col-12">
    				<div class="header">
    					<div class="header-body">
    						<div class="row align-items-center">
    							<div class="col">
                                    <!-- <h6 class="header-pretitle">Overview</h6> -->
    								<h1 class="header-title text-truncate">Sales<?php if (!empty($_COOKIE['admin_sales_tab'])) { if ($_COOKIE['admin_sales_tab'] == 'history') { echo ' (history)'; } } ?></h1>
    							</div>
                                <?php if ($this->userInfo['role_id'] == 2) { ?>
                                    <div class="col-auto">
                                        <div class="btn ms-2 sales_view_history<?php if (!empty($_COOKIE['admin_sales_tab'])) { if ($_COOKIE['admin_sales_tab'] == 'history') { echo ' bg-success'; } else { echo ' btn-secondary'; } } else { echo ' btn-secondary'; } ?>" style="min-width: 100px; margin-right: 5px;">History <span><?php if (!empty($_COOKIE['admin_sales_tab'])) { if ($_COOKIE['admin_sales_tab'] == 'history') { echo 'ON'; } else { echo 'OFF'; } } else { echo 'OFF'; } ?></span></div>
                                        <a href="/add-sale" class="btn btn-primary ms-2">Generate Game Code</a>
                                    </div>
                                <?php } ?>
    						</div>
    					</div>
    				</div>

    				<div class="card card_change card_actual<?php if (!empty($_COOKIE['admin_sales_tab'])) { if ($_COOKIE['admin_sales_tab'] == 'history') { echo ' d-none'; } } ?>">
    					<div class="table-responsive">
    						<!-- <table class="table table-sm table-hover table-nowrap card-table table-bordered text-center"> -->
    						<table class="table table-sm table-hover card-table table-bordered text-center">
    							<thead>
    								<tr>
    									<th>#</th>
                                        <th>Game number</th>
    									<th class="wf-nowrap">Date</th>
    									<th>Game</th>
    									<th>Price, USD</th>
    									<th>Price, NOK</th>
    									<th>Source</th>
    									<th>Code</th>
                                        <th><i class="far fa-envelope"></i></th>
                                        <?php if ($this->userInfo['role_id'] == 2) { ?><th><i class="far fa-trash-alt"></i></th><?php } ?>
    								</tr>
    							</thead>
    							<tbody class="list fs-base">
    								<?php
    								    if (count($sales) > 0) {
                                            if ($this->page == 1) {
                                                $sale_count = $qt_sales;
                                            } else {
                                                $sale_count = $qt_sales - $this->settings['limit'] * ($this->page - 1);
                                            }

        								    foreach ($sales as $sale) {
                                                if (!is_null($sale['datetime_sale']) && $sale['datetime_sale'] != 'null' && !empty($sale['datetime_sale']) && $sale['datetime_sale'] != '0000-00-00 00:00:00') {
    								    	        $sale_date = new DateTime($sale['datetime_sale']);
                                                    $print_sale_date = $sale_date->format('d.m.Y H:i');
                                                } else {
                                                    $print_sale_date = '';
                                                }

        								    	echo '<tr>
        								    	        <td>' . $sale_count . '</td>
                                                        <td>' . $sale['source_code'] . '</td>
        								    	        <td class="wf-nowrap">' . $print_sale_date . '</td>
        								    	        <td>' . $sale['game_name'] . '</td>
        								    	        <td>' . (float) $sale['price_dollar'] . '</td>
        								    	        <td>' . (float) $sale['price_norway_crone'] . '</td>
        								    	        <td>' . $sale['source_name'] . '</td>
                                                        <td class="wf-saletable-teamcode">' . $sale['team_code'] . '</td>
                                                        <td class="wf-saletable-send-email"><input type="hidden" class="wf-saletable-client-email" value="' . htmlspecialchars($sale['client_email'], ENT_QUOTES) . '"><i class="far fa-envelope"></i></td>
                                                        ' . ($this->userInfo['role_id'] == 2 ? '<td class="wf-remove-sale"><i class="far fa-trash-alt"></i></td>' : '') . '
        								    	    </tr>';

                                                $sale_count--;
        								    }
                                        } else {
                                            echo '<tr><td colspan="' . ($this->userInfo['role_id'] == 2 ? '10' : '9') . '">Empty Results</td></tr>';
                                        }
    								?>
    							</tbody>
    						</table>
    					</div>
                        <?php if ($qt_sales > $this->settings['limit']) { ?>
                            <div class="card-footer d-flex justify-content-center"><?php echo $this->pagination->render(); ?></div>
                        <?php } ?>
    				</div>

                    <div class="card card_change card_history<?php if (!empty($_COOKIE['admin_sales_tab'])) { if ($_COOKIE['admin_sales_tab'] == 'actual') { echo ' d-none'; } } else { echo ' d-none'; } ?>">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-nowrap card-table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Game number</th>
                                        <th>Date</th>
                                        <th>Game</th>
                                        <th>Price, USD</th>
                                        <th>Price, NOK</th>
                                        <th>Source</th>
                                        <th>Code</th>
                                        <th><i class="far fa-envelope"></i></th>
                                    </tr>
                                </thead>
                                <tbody class="list fs-base">
                                    <?php
                                        if (count($sales_removed) > 0) {
                                            if ($this->page == 1) {
                                                $sale_count = $qt_sales_removed;
                                            } else {
                                                $sale_count = $qt_sales_removed - $this->settings['limit'] * ($this->page - 1);
                                            }

                                            foreach ($sales_removed as $sale) {
                                                if (!is_null($sale['datetime_sale']) && $sale['datetime_sale'] != 'null' && !empty($sale['datetime_sale']) && $sale['datetime_sale'] != '0000-00-00 00:00:00') {
                                                    $sale_date = new DateTime($sale['datetime_sale']);
                                                    $print_sale_date = $sale_date->format('d.m.Y H:i');
                                                } else {
                                                    $print_sale_date = '';
                                                }

                                                echo '<tr>
                                                        <td>' . $sale_count . '</td>
                                                        <td>' . $sale['source_code'] . '</td>
                                                        <td>' . $print_sale_date . '</td>
                                                        <td>' . $sale['game_name'] . '</td>
                                                        <td>' . (float) $sale['price_dollar'] . '</td>
                                                        <td>' . (float) $sale['price_norway_crone'] . '</td>
                                                        <td>' . $sale['source_name'] . '</td>
                                                        <td class="wf-saletable-teamcode">' . $sale['team_code'] . '</td>
                                                        <td class="wf-saletable-send-email"><input type="hidden" class="wf-saletable-client-email" value="' . htmlspecialchars($sale['client_email'], ENT_QUOTES) . '"><i class="far fa-envelope"></i></td>
                                                    </tr>';

                                                $sale_count--;
                                            }
                                        } else {
                                            echo '<tr><td colspan="9">Empty Results</td></tr>';
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($qt_sales_removed > $this->settings['limit']) { ?>
                            <div class="card-footer d-flex justify-content-center"><?php echo $pagination_removed->render(); ?></div>
                        <?php } ?>
                    </div>
    			</div>
    		</div>
    	</div>
    </div>
<?php
	require_once(ROOT . '/admin/view/template/blocks/footer.php');
