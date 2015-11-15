$(document).ready(function(){
	
	$('.content_table_plugin').ready(function() { 
		var content_key = getParameterByName('key');
		var status = 'available';
		$.post( '?run=plugin_ajax.php&action=available', function( data ) {
			build_table(data);
		});
	});
	
	
	var build_table = function (data){
		var obj = jQuery.parseJSON(data);
		var html_plugin = [];
		var plugins = obj.plugins;
		$.each( plugins, function( number , value ) {
			var plugin_name = value.plugin_name;
			var plugin_description = value.plugin_description;
			var plugin_version = value.plugin_version;
			var plugin_active = value.plugin_active;
			var plugin_key = value.plugin_key;
			if (plugin_active=='1'){ 
				var addclass='bg-success'; 
				var action_btn = '<button class="btn btn-default btn-xs plugin_disable" data-key="'+plugin_key+'">Dừng kích hoạt</button>';
			}
			if (plugin_active=='0'){ 
				var action_btn = '<button class="btn btn-default btn-xs plugin_active" data-key="'+plugin_key+'">Kích hoạt</button>';
			}
			var line = '<td>'+plugin_name+'</td><td>'+plugin_description+'</td><td class="td_action td_action_'+plugin_name+'">'+action_btn+'</td>';
			html_plugin.push('<tr class="plugin_tr plugin_'+plugin_key+' '+addclass+'">'+line+'</tr>');
		});
		html_plugin = html_plugin.join(' ');
		$('.content_table .content_table_plugin').html(html_plugin);
	}
	
	$(document).on('click', '.plugin_disable', function(){
		var plugin  = $(this).attr('data-key');
		$.post( '?run=plugin_ajax.php&action=disable_plugin', { plugin:plugin }, function( data ) {
			$.notify('Đã tắt plugin', { globalPosition: 'top right',className: 'success' } );
			setTimeout(function () {
				location.reload();
			}, 1000);
		});
	});
	
	$(document).on('click', '.plugin_active', function(){
		var plugin  = $(this).attr('data-key');
		$.post( '?run=plugin_ajax.php&action=active_plugin', { plugin:plugin }, function( data ) {
			$.notify('Đã kích hoạt plugin', { globalPosition: 'top right',className: 'success' } );
			setTimeout(function () {
				location.reload();
			}, 1000);
		});
	});
	
});