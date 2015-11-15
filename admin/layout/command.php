<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/command/js/jquery.mousewheel-min.js"></script>
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/command/js/jquery.terminal-min.js"></script>
<link href="<?php echo ADMIN_LAYOUT_PATH; ?>/command/css/jquery.terminal.css" rel="stylesheet"/>
<script>
jQuery(document).ready(function($) {
    $('#command-box').terminal("?run=command_ajax.php", {
			login: false,
			greetings: "Dùng lệnh help để xem các lệnh được hỗ trợ"});
});

</script>

<div id="command-box" class="terminal"></div>