<?php defined('GD_ACCESS') or die('You can not access the file directly!'); ?>
	<nav class="navbar navbar-vertical fixed-start navbar-expand-md navbar-light" id="sidebar">
      	<div class="container-fluid">
        	<!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          		<span class="navbar-toggler-icon"></span>
        	</button> -->
    
        	<div class="navbar-brand">
          		<img src="/images/login/escape_logo.png" class="navbar-brand-img mx-auto" alt="">
        	</div>
    
        	<div class="collapse navbar-collapse" id="sidebarCollapse">
          		<ul class="navbar-nav">
            		<li class="nav-item">
              			<a class="nav-link<?php if (isset($_SERVER['REQUEST_URI']) && stripos($_SERVER['REQUEST_URI'], '/sales') !== false) { echo ' active'; } ?>" href="/sales"><i class="fas fa-shopping-cart"></i> Sales</a>
            		</li>
            		<li class="nav-item">
              			<a class="nav-link<?php if (isset($_SERVER['REQUEST_URI']) && stripos($_SERVER['REQUEST_URI'], '/games') !== false) { echo ' active'; } ?>" href="/games"><i class="fas fa-gamepad"></i> Games</a>
            		</li>
            		<!-- <li class="nav-item">
              			<a class="nav-link<?php if (isset($_SERVER['REQUEST_URI']) && (stripos($_SERVER['REQUEST_URI'], '/total-sales') !== false || stripos($_SERVER['REQUEST_URI'], '/totalsales-sum') !== false)) { echo ' collapsed'; } ?>" href="#sidebarStats" data-bs-toggle="collapse" role="button" aria-expanded="<?php if (isset($_SERVER['REQUEST_URI']) && (stripos($_SERVER['REQUEST_URI'], '/total-sales') !== false || stripos($_SERVER['REQUEST_URI'], '/totalsales-sum') !== false)) { echo 'true'; } else { echo 'false'; } ?>" aria-controls="sidebarStats" data-toggle="collapse">
                			<i class="fas fa-chart-pie"></i> Stats
              			</a>
              			<div class="collapse<?php if (isset($_SERVER['REQUEST_URI']) && (stripos($_SERVER['REQUEST_URI'], '/total-sales') !== false || stripos($_SERVER['REQUEST_URI'], '/totalsales-sum') !== false)) { echo ' show'; } ?>" id="sidebarStats">
                			<ul class="nav nav-sm flex-column">
                  				<li class="nav-item">
                    				<a href="/total-sales" class="nav-link<?php if (isset($_SERVER['REQUEST_URI']) && stripos($_SERVER['REQUEST_URI'], '/total-sales') !== false) { echo ' active'; } ?>">Total Sales #</a>
                  				</li>
                  				<li class="nav-item">
                    				<a href="/totalsales-sum" class="nav-link<?php if (isset($_SERVER['REQUEST_URI']) && stripos($_SERVER['REQUEST_URI'], '/totalsales-sum') !== false) { echo ' active'; } ?>">Total Sales Sum</a>
                  				</li>
                			</ul>
              			</div>
            		</li> -->
            		<li class="nav-item">
              			<a class="nav-link<?php if (isset($_SERVER['REQUEST_URI']) && stripos($_SERVER['REQUEST_URI'], '/total-sales') !== false) { echo ' active'; } ?>" href="/total-sales"><i class="fas fa-chart-pie"></i> Total Sales #</a>
            		</li>
            		<li class="nav-item">
              			<a class="nav-link<?php if (isset($_SERVER['REQUEST_URI']) && stripos($_SERVER['REQUEST_URI'], '/totalsales-sum') !== false) { echo ' active'; } ?>" href="/totalsales-sum?year=<?php echo date('Y'); ?>&currency=nok"><i class="fas fa-chart-pie"></i> Total Sales Sum</a>
            		</li>
            		<?php if ($this->userInfo['role_id'] == 2) { ?>
            		<li class="nav-item">
              			<a class="nav-link<?php if (isset($_SERVER['REQUEST_URI']) && stripos($_SERVER['REQUEST_URI'], '/manage-stages') !== false) { echo ' active'; } ?>" href="/manage-stages"><i class="fas fa-tasks"></i> Manage Stages</a>
            		</li>
            		<?php } ?>
          		</ul>

          		<?php if ($this->userInfo['role_id'] == 2) { ?>
	          		<hr class="navbar-divider my-3">

	          		<ul class="navbar-nav">
	            		<li class="nav-item">
	              			<a class="nav-link<?php if (isset($_SERVER['REQUEST_URI']) && stripos($_SERVER['REQUEST_URI'], '/users') !== false) { echo ' active'; } ?>" href="/users"><i class="fas fa-user-friends"></i> Users</a>
	            		</li>
	            		<li class="nav-item">
	              			<a class="nav-link<?php if (isset($_SERVER['REQUEST_URI']) && stripos($_SERVER['REQUEST_URI'], '/settings') !== false) { echo ' active'; } ?>" href="/settings"><i class="fas fa-cogs"></i> Settings</a>
	            		</li>
	            	</ul>
          		<?php } ?>

          		<hr class="navbar-divider my-3">

          		<ul class="navbar-nav">
            		<li class="nav-item">
              			<a class="nav-link" href="/admin-exit"><i class="fas fa-sign-out-alt"></i> Log out</a>
            		</li>
            	</ul>
        	</div>
      	</div>
    </nav>