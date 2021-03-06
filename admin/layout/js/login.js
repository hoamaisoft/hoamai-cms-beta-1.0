function getParameterByName(name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

$(document).ready(function(){
	
	var reset_content = function(){
		$('input[type=text]').val('');
		$('input[type=email]').val('');
		$('input[type=number]').val('');
		$('input[type=password]').val('');
		$('input[type=checkbox]').prop('checked', false); 
		$('textarea').val('');
		$('.note-editable').html('');
		$('.preview_media_file').html('');
	}
	
	$('.ajaxFormAdminCpLogin').ajaxForm(function(data) {
		var obj = jQuery.parseJSON(data);
		var status = obj.status;
		var mes = obj.mes;
		if(status == 'error'){
			$.notify(mes, { className: 'danger' });
		}
		if(status == 'success'){
			$.notify(mes, { globalPosition: 'top right',className: 'success' } );
			var back = getParameterByName('back');
			window.location.href = back;
		}
	}); 
	
	$('.ajaxFormAdminCpLostpw').ajaxForm(function(data) {
		var obj = jQuery.parseJSON(data);
		var status = obj.status;
		var mes = obj.mes;
		if(status == 'error'){
			$.notify(mes, { className: 'danger' });
		}
		if(status == 'success'){
			$.notify(mes, { globalPosition: 'top right',className: 'success' } );
		}
	}); 
	
	$('.ajaxFormAdminCpNewpw').ajaxForm(function(data) {
		var obj = jQuery.parseJSON(data);
		var status = obj.status;
		var mes = obj.mes;
		if(status == 'error'){
			$.notify(mes, { className: 'danger' });
		}
		if(status == 'success'){
			$.notify(mes, { globalPosition: 'top right',className: 'success' } );
		}
	}); 
	
}); 