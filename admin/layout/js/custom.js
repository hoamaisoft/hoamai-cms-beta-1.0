function getParameterByName(name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function calcHeight(){
	var the_height=document.getElementById('fullheight_iframe').contentWindow.document.body.scrollHeight;
	document.getElementById('fullheight_iframe').height=the_height;
}
var reset_content = function(){
		$('input[type=text]').val('');
		$('input[type=email]').val('');
		$('input[type=number]').val('');
		$('input[type=password]').val('');
		$('input[type=checkbox]').prop('checked', false); 
		$('input[type=radio]').prop('checked', false); 
		$('textarea').val('');
		$('.note-editable').html('');
		$('.preview_media_file').html('');
	}
setTimeout(function () {
			$('[role=alert]').fadeOut(1000);
		}, 2000);	
		
		
$(document).ready(function(){
	
	$("#menu-toggle").click(function(e) {
		e.preventDefault();
		$("#wrapper").toggleClass("active");
	});
	
	$('.media_btn').click(function(){
		var this_id = $(this).attr('id');
		var multi = $(this).attr('multi');
		var imageonly = $(this).attr('imageonly');
		$('.media_box_ajax_load .col-md-9').html('Đang tải dữ liệu');
		$('.media_box').load('?run=media_ajax.php&action=ajax_media_box&use_media_file='+this_id+'&multi='+multi+'&imageonly='+imageonly, function(){
			$('.media_box_ajax_load').hide();
			$('.media_box_ajax_load').fadeIn(1000);
		});
	});
	
	$('.use_media_file').click(function(){
		var this_id = $(this).attr('id');
		var multi = $(this).attr('multi');
		var imageonly = $(this).attr('imageonly');
		$('.media_box_ajax_load .col-md-9').html('Đang tải dữ liệu');
		$('.media_box').load('?run=media_ajax.php&action=ajax_media_box&use_media_file='+this_id+'&multi='+multi+'&imageonly='+imageonly, function(){
			$('.media_box_ajax_load').hide();
			$('.media_box_ajax_load').fadeIn(1000);
		});
	});
	
	$('.wysiwyg').summernote({
		tabsize: 2,
		lang: 'vi-VN',
	});
		
	$(document).on('click', '.use_media_file_insert', function(e){
		var file_id = $('#file_id').val();
		var file_src = $('#file_src').val();
		var use_media_file = $(this).attr('use_media_file');
		var img = '';
		var img = '<img src="'+file_src+'" />';
		var olddata = $('.wysiwyg[name='+use_media_file+']').code();
		var newdata = olddata + img;
		$('.wysiwyg[name='+use_media_file+']').code(newdata);
		event.preventDefault(e);
	});
	
    $( ".list-form-input" ).sortable({
		placeholder: "list-form-input-placeholder",
		handle: ".form-group-handle",
    });
	
	$( ".admin_sidebar" ).sortable({
		placeholder: "list-form-input-placeholder",
		handle: ".admin_sidebar_box_title",
		items: ".admin_sidebar_box",
    });
	
	$(document).on('click', 'input.checkall', function(){
		var id =  $(this).attr('data-id');
		if(this.checked) {
            $('.checkall_item_'+id).each(function() {
                this.checked = true;            
            });
        }else{
            $('.checkall_item_'+id).each(function() {
                this.checked = false;                    
            });         
        }
	});
	
	$(document).on('click', '.preview_media_file_wapper', function(){
		$('.preview_media_file_wapper').removeClass('checked');
		$(this).addClass('checked');
		var fileid = $(this).attr('file-id');
		var use_media_file  = $(this).attr('use_media_file');
		$('.preview_media_file_remove').hide();
		$('.preview_media_file_remove[file-id='+fileid+'][use_media_file='+use_media_file+']').show();
	});
	
	$(document).on('click', '.preview_media_file_remove', function(){
		var fileid = $(this).attr('file-id');
		var use_media_file  = $(this).attr('use_media_file');
		$('.preview_media_file_wapper[file-id='+fileid+'][use_media_file='+use_media_file+']').remove();
		var inputval = $('input[use_media_file='+use_media_file+']').val();
		var splited = inputval.split(',');
		$(splited).each(function(key){
			if(splited[key] == fileid){
				splited.splice(key,1);
			}
		});
		var newinputval = splited.join(',');
		$('input[use_media_file='+use_media_file+']').val(newinputval);
	});

	$(document).on('dblclick', '.file_item', function(){
		if ( $('.use_media_file_insert').is(":visible") ){
			$('.use_media_file_insert').click();
		}
	});
	
	$(document).on('keyup','input.request_uri', function(){
		var val = $(this).val();
		var input = $(this).attr('data-input');
		if(val!=''){
			$.post( '?run=request_ajax.php&action=suggest', { val:val, input:input }, function( data ) {
				$('.auto_suggest_of_'+input).show();
				$('.auto_suggest_of_'+input).html(data);
			});
		}else{
			$('.auto_suggest_of_'+input).html('');
		}
	});
	
	$(document).on('click', '.auto_suggest_result > li > p', function(){
		var id = $(this).attr('data-id');
		var input = $(this).attr('data-input');
		var name = $(this).attr('data-name');
		$(this).parents('.form-group').find('input[name='+input+']').val(id);
		$(this).parents('.form-group').find('input[data-input='+input+']').val(name);
		$('.auto_suggest_result').hide();
	});
	
});
