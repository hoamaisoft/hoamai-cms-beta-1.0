	<nav class="topbar navbar" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#topnavbar">
                <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span><span
                    class="icon-bar"></span><span class="icon-bar"></span>
            </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="topnavbar">
            <ul class="nav navbar-nav">
                <li><a href="?run=dashboard.php"><span class="glyphicon glyphicon-home"></span><?php echo _('Tổng quan'); ?></a></li>
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span
                    class="glyphicon glyphicon-list-alt"></span><?php echo _('Cài đặt'); ?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php
						$setting_page = get_admin_setting_page();
						foreach($setting_page as $page){
						?>
						<li><a href="?run=setting.php&key=<?php echo $page['key']; ?>"><?php echo $page['label']; ?></a></li>
						<?php
						}
						?>
                    </ul>
                </li>
				<li><a href="?run=media.php"><span class="glyphicon glyphicon-picture"></span><?php echo _('Thư viện'); ?></a></li>
            </ul>
			
            <ul class="nav navbar-nav navbar-right">
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span
                    class="glyphicon glyphicon-user"></span><?php echo user_name(); ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
							<a href="?run=user.php&action=edit">
								<span class="glyphicon glyphicon-cog"></span><?php echo _('Tài khoản'); ?>
							</a>
						</li>
                        <li class="divider"></li>
                        <li>
							<a href="?run=login.php&action=logout">
								<span class="glyphicon glyphicon-off"></span><?php echo _('Đăng xuất'); ?>
							</a>
						</li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>