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
    								<h1 class="header-title text-truncate">Total Sales Sum</h1>
    							</div>
                                <div class="col-auto" style="display: flex; width: 210px;">
                                    <select name="wf-total-sales-sum-change-currency" class="form-control custom-select">
                                        <option value="usd"<?php if ($currency == 'usd') { echo ' selected="selected"'; } ?>>usd</option>
                                        <option value="nok"<?php if ($currency == 'nok') { echo ' selected="selected"'; } ?>>nok</option>
                                    </select>

                                    <select name="wf-total-sales-sum-change-year" class="form-control custom-select" style="margin: 0 0 0 15px;">
                                        <?php
                                            foreach ($years as $year) {
                                                echo '<option value="' . $year . '"' . ($year == $cur_year ? ' selected="selected"' : '') . '>' . $year . '</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
    						</div>
    					</div>
    				</div>

    				<?php if ($this->userInfo['role_id'] == 2) { ?>
                        <h2 class="mb-2">Total Sales Sum</h2>
                        <div class="card">
                            <div class="card-body">
                                <div class="chart wf-total-sale-sum-chart wf-total-sale-sum-chart1">
                                    <canvas class="chart-canvas" id="wfTotalSaleSumChart1"></canvas>
                                </div>
                            </div>
                        </div>
                        <script>
                            new Chart('wfTotalSaleSumChart1', {
                                type: 'bar',
                                /*options: {
                                    scales: {
                                        y: {
                                            ticks: {
                                                callback: function(value) {
                                                    return value + " <?php echo $currency_symbol; ?>";
                                                }
                                            }
                                        }
                                    }
                                },*/
                                data: {
                                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                    datasets: [{
                                        label: 'Sales',
                                        // data: [25, 20, 30, 22, 17, 10, 18, 26, 28, 26, 20, 32]
                                        data: [<?php echo implode(',', $sales_all); ?>]
                                    }]
                                }
                            });
                        </script>
                    <?php } ?>

                    <?php
                        $source_count = 2;
                        foreach ($sales_sources as $source_name => $sales_source) {
                            if ($this->userInfo['role_id'] == 2) {
                                echo '<hr class="my-5">';
                            }

                            echo '<h2 class="mb-2">Sales Sum ' . (!empty($source_name) ? $source_name : ' (empty source)') . '</h2>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="chart wf-total-sale-chart wf-total-sale-sum-chart' . $source_count . '">
                                            <canvas class="chart-canvas" id="wfTotalSaleSumChart' . $source_count . '"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    new Chart("wfTotalSaleSumChart' . $source_count . '", {
                                        type: "bar",
                                        data: {
                                            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                                            datasets: [{
                                                label: "Sales",
                                                data: [' . implode(',', $sales_source) . ']
                                            }]
                                        }
                                    });
                                </script>';

                            $source_count++;
                        }
                    ?>
    			</div>
    		</div>
    	</div>
    </div>
<?php
	require_once(ROOT . '/admin/view/template/blocks/footer.php');
