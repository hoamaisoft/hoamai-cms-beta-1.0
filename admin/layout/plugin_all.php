<!-- custom js page -->
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/plugin.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/plugin.css">

<div class="row" >
	<div class="col-md-12">
	
		<table class="table content_table bg-white sortable">
			<tr>
				<th><?php echo _('Tên'); ?></th>
				<th><?php echo _('Mô tả'); ?></th>
				<th><?php echo _('Tác vụ'); ?></th>
			</tr>
			<tbody class="content_table_plugin">
				<tr><td colspan="3"><?php win8_loading(); ?></td></tr>	
			</tbody>
		</table>

	</div>
</div>