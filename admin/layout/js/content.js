$(document).ready(function(){
	
	$('.ajaxFormcontentAdd').ajaxForm(function(data) {
		var obj = jQuery.parseJSON(data);
		var latest = obj.latest;
		var id = latest.id;
		var name = latest.name;
		reset_content();
		var content_key = getParameterByName('key');
		window.location.href = '?run=content.php&key='+content_key+'&action=edit&id='+id+'&mes=add_success';
	}); 
	

	$('.ajaxFormcontentEdit').ajaxForm(function(data) {
		var id = getParameterByName('id');
		window.location.href = '?run=content.php&action=edit&id='+id+'&mes=edit_success';
	});
	
	$('.content_table_content').ready(function() { 
		var content_key = getParameterByName('key');
		var status = 'public';
		$.post( '?run=content_ajax.php&key='+content_key+'&status='+status+'&action=data', function( data ) {
			build_table(data,status);
		});
	});
	
	$('.content_status').click(function() { 
		var content_key = getParameterByName('key');
		var status = $(this).attr('data-status');
		var perpage = $('.select_perpage').val();
		$.post( '?run=content_ajax.php&key='+content_key+'&status='+status+'&perpage='+perpage+'&action=data', function( data ) {
			$('select.select_perpage').attr('data-status',status);
			build_table(data,status);
		});
	});
	
	var build_table = function (data,status){
		$('.content_table .content_table_content').html('<tr><td colspan="4">Đang tải ...</td></tr>');
		var obj = jQuery.parseJSON(data);
		var html_content = [];
		var content = obj.content;
		var chapter = obj.chapter;
		$.each( content, function( number , value ) {
			var id = value.id;
			var name = value.name;
			var slug = value.slug;
			if(chapter == true){
				var add_chapter_btn = '<a class="btn btn-info btn-xs" href="?run=content.php&action=add_chapter&id='+id+'">Thêm chapter</a><a class="btn btn-info btn-xs" href="?run=chapter.php&id='+id+'">Các chapter</a>';
			}else{
				var add_chapter_btn = '';
			}
			if(status == 'public'){
				var line = '<td><input name="content_ids[]" value="'+id+'" type="checkbox" class="checkall_item_1"></td><td><a href="?run=content.php&action=edit&id='+id+'">'+name+'</a></td><td><a href="?run=content.php&action=view&id='+id+'">'+slug+'</a></td><td class="td_action td_action_'+id+'"><div class="action_btn_wap"><span class="action_btn_wap_button"><i class="glyphicon glyphicon-cog"></i></span><a class="btn btn-default btn-xs" href="?run=content.php&action=edit&id='+id+'">Sửa</a><button type="button" data-id="'+id+'" class="quick_delete_content_button btn btn-danger btn-xs">Xóa</button>'+add_chapter_btn+'</div></td>';
			}
			if(status == 'draft'){
				var line = '<td><input name="content_ids[]" value="'+id+'" type="checkbox" class="checkall_item_1"></td><td><a href="?run=content.php&action=edit&id='+id+'">'+name+'</a></td><td><a href="?run=content.php&action=view&id='+id+'">'+slug+'</a></td><td class="td_action td_action_'+id+'"><div class="action_btn_wap"><span class="action_btn_wap_button"><i class="glyphicon glyphicon-cog"></i></span><a class="btn btn-default btn-xs" href="?run=content.php&action=edit&id='+id+'">Sửa</a><button type="button" data-id="'+id+'" class="quick_public_content_button btn btn-info btn-xs">Phục hồi</button>'+add_chapter_btn+'<button type="button" data-id="'+id+'" class="quick_delete_permanently_content_button btn btn-danger btn-xs">Xóa vĩnh viễn</button></div></td>';
			}
			html_content.push('<tr class="content_tr content_'+id+'">'+line+'</tr>');
		});
		html_content = html_content.join(' ');
		$('.content_table .content_table_content').html(html_content);
		
		var pagination = obj.pagination;
		if ("first" in pagination){
				
			var first = pagination.first;
			var previous = pagination.previous;
			var next = pagination.next;
			var last = pagination.last;
			var total = pagination.total;
			var paged = pagination.paged;
			if(previous == paged){
				var previous_link = '<a disabled="disabled" data-paged="'+previous+'" class="btn btn-default btn-xs">Trang trước</a>';
			}else{
				var previous_link = '<a data-paged="'+previous+'" class="btn btn-default btn-xs">Trang trước</a>';
			}
			if(next == paged){
				var next_link = '<a disabled="disabled" data-paged="'+next+'" class="btn btn-default btn-xs">Trang sau</a>';
			}else{
				var next_link = '<a data-paged="'+next+'" class="btn btn-default btn-xs">Trang sau</a>';
			}
			
			var pagination_bar = '<a data-paged="'+first+'" class="btn btn-default btn-xs">Trang đầu</a>'+previous_link+'<a data-paged="'+paged+'" class="btn btn-default btn-xs">'+paged+'</a>'+next_link+'<a data-paged="'+last+'" class="btn btn-default btn-xs">Trang cuối</a><span class="total_page">( Trang '+paged+' trong tổng số '+last+' trang, '+total+' kết quả )</span>';
			$('.pagination_bar').html(pagination_bar);	
				
		}else{
			$('.pagination_bar').html('');	
		}
		
	};
	
	$(document).on('change', '.input_have_slug', function(){
		var action = getParameterByName('action');
		if(action == 'add'){
			var val = $(this).val();
			var name = $(this).attr('name');
			var object = $(this).attr('object-id');
			var input_slug = 'slug_of_'+name+'_'+object;
			var input_accented = $(this).attr('slug-accented');
			var accented = $('input[name='+input_accented+']:checked').val();
			if(accented == undefined){accented = 'false';}
			ajax_slug(val,input_slug,accented,object);
		}
	});
		

	$(document).on('change', '.accented', function(){
		var object = $(this).attr('data-field-object');
		var name = $(this).attr('data-field-name');
		var val = $('input[object-id='+object+'][name='+name+']').val();
		var input_slug = 'slug_of_'+name+'_'+object;
		var accented = $(this).val();
		ajax_slug(val,input_slug,accented,object);
	});
	
	var ajax_slug = function (val,input_slug,accented,object){
		$.post( '?run=content_ajax.php&action=ajax_slug', { val:val , accented:accented , object:object }, function( data ) {
			$('.'+input_slug).val(data);
		});
	};

	
	$('select[name=status]').change(function(){
		var value = $(this).val();
		if(value == 'password'){
			$('.input_password_content').slideDown();
		}else{
			$('.input_password_content').slideUp();
		}
	}); 
	
	$(document).on('click', '.quick_delete_content_button', function(){
		var id = $(this).attr('data-id');
		$.post( '?run=content_ajax.php&action=draft', { id:id }, function( data ) {
			$('.content_tr.content_'+id).addClass('danger');
			$('.td_action_'+id+' .quick_delete_content_button').remove();
			$('.td_action_'+id+' .action_btn_wap').append('<button type="button" data-id="'+id+'" class="quick_public_content_button btn btn-info btn-xs">Phục hồi</button>');
			$.notify('Đã cho vào thùng rác', { globalPosition: 'top right',className: 'success' } );
		});
	});
	
	$(document).on('click', '.quick_delete_permanently_content_button', function(){
		var id = $(this).attr('data-id');
		$('.content_tr.content_'+id).addClass('warning');
		$('.td_action_'+id+' .quick_delete_permanently_content_button').remove();
		$('.td_action_'+id+' .action_btn_wap').append('<button type="button" data-id="'+id+'" class="confirm_delete_permanently_content_button btn btn-danger btn-xs">Xác nhận xóa vĩnh viễn</button>');
		$.notify('Vui lòng xác nhận', { globalPosition: 'top right',className: 'success' } );
	});
	
	$(document).on('click', '.confirm_delete_permanently_content_button', function(){
		var id = $(this).attr('data-id');
		var status = 'draft';
		var content_key = getParameterByName('key');
		$.post( '?run=content_ajax.php&action=delete_permanently', { id:id }, function( data ) {
			$.notify('Đã xóa vĩnh viễn', { globalPosition: 'top right',className: 'success' } );
			$.post( '?run=content_ajax.php&key='+content_key+'&status='+status+'&action=data', function( data ) {
				build_table(data,status);
			});
		});
	});
	
	$(document).on('click', '.quick_public_content_button', function(){
		var id = $(this).attr('data-id');
		$.post( '?run=content_ajax.php&action=public', { id:id }, function( data ) {
			$('.content_tr.content_'+id).removeClass('danger');
			$('.td_action_'+id+' .quick_public_content_button').remove();
			$('.td_action_'+id+' .confirm_delete_permanently_content_button').remove();
			$('.td_action_'+id+' .action_btn_wap').append('<button type="button" data-id="'+id+'" class="quick_delete_content_button btn btn-danger btn-xs">Xóa</button>');
			$.notify('Đã phục hồi lại nội dung', { globalPosition: 'top right',className: 'success' } );
		});
	});
	
	$(document).on('change', 'select.select_perpage', function(){
		var perpage = $(this).val();
		var content_key = getParameterByName('key');
		var status = $(this).attr('data-status');
		$.post( '?run=content_ajax.php&key='+content_key+'&status='+status+'&perpage='+perpage+'&action=data', function( data ) {
			build_table(data,status);
		});
	});
	
	$(document).on('click', '.pagination_bar .btn', function(){
		var paged = $(this).attr('data-paged');
		var content_key = getParameterByName('key');
		var status = $('select.select_perpage').attr('data-status');
		var perpage = $('select.select_perpage').val();
		$.post( '?run=content_ajax.php&key='+content_key+'&status='+status+'&perpage='+perpage+'&paged='+paged+'&action=data', function( data ) {
			build_table(data,status);
		});
	});
	
});