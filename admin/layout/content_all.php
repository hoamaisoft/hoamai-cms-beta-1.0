<!-- custom js page -->
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/content.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/content.css">

<div class="row" >
	<div class="col-md-12">
		<h1 class="page_title"><?php echo $args['content_name']; ?></h1>
	</div>
	<div class="col-md-12">
		<p class="page_action">
			<span><?php echo $args['all_items']; ?></span>
			<button class="btn btn-default btn-xs content_status" data-status="public"><?php echo _('Đã đăng'); ?></button>
			<button class="btn btn-default btn-xs content_status" data-status="draft"><?php echo _('Thùng rác'); ?></button>
			<a href="?run=content.php&key=<?php echo hm_get('key'); ?>&action=add" class="btn btn-default btn-xs media_btn"><?php echo $args['add_new_item']; ?></a>
		</p>
		<div class="table_action">
			<div class="table_action_left">
				<select name="action" class="css_selectbox btn btn-default btn-xs">
					<option value=""><?php echo _('Lựa chọn'); ?></option>
					<option value="draft"><?php echo _('Bỏ vào thùng rác'); ?></option>
				</select>
				<button class="btn btn-default btn-xs"><?php echo _('Thực hiện'); ?></button>
			</div>
			<div class="table_action_right">
				<select data-status="public" name="perpage" class="select_perpage css_selectbox btn btn-default btn-xs">
					<option value="5"><?php echo _('5'); ?></option>
					<option value="10"><?php echo _('10'); ?></option>
					<option value="30" selected="selected"><?php echo _('30'); ?></option>
					<option value="50"><?php echo _('50'); ?></option>
					<option value="100"><?php echo _('100'); ?></option>
					<option value="500"><?php echo _('500'); ?></option>
					<option value="1000"><?php echo _('1000'); ?></option>
				</select>
			</div>
		</div>
		<div class="table_content_all">
			<table class="table table-striped content_table">
				<tr>
					<th class="th_checkall"><input type="checkbox" class="checkall" data-id="1"></th>
					<th><?php echo _('Tên'); ?></th>
					<th><?php echo _('Slug'); ?></th>
					<th class="th_action"><?php echo _('Hành động'); ?></th>
				</tr>
				<tbody class="content_table_content">
					<tr><td colspan="4"><?php win8_loading(); ?></td></tr>
				</tbody>
			</table>
		</div>
		<div class="pagination_bar"></div>
	</div>
</div>

