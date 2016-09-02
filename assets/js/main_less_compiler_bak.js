$(document).on('rex:ready', function (event, container) {
	
	//include_hidden_imputs = function() {
	//	var input_fields = {
	//		'less_compiler_fullscreen': 'false',
	//		'less_compiler_line': cur_line,
	//		'less_compiler_ch': cur_ch,
	//	};
	//	for (var key in input_fields) {
	//		$('<input />', {
	//			'type': 'text',
	//			'id': key,
	//			'name': key,
	//			'value': input_fields[key],
	//		}).appendTo('#less_compiler_edit_form');
	//	}
	//};	
	////include_hidden_imputs();

	var editor = CodeMirror.fromTextArea(document.getElementById("less_compiler_edit"), {
			fullScreen: fullscreen,
			lineNumbers: true,
			lineWrapping: false,
			styleActiveLine: true,
			matchBrackets: true,
			mode: 'text/x-less',
			indentUnit: 4,
			indentWithTabs: false,
			enterMode: "keep",
			tabMode: "shift",
			theme: 'paraiso-dark',
			extraKeys: {
				"F11": function(cm) {
					cm.setOption("fullScreen", !cm.getOption("fullScreen"));
					$('#less_compiler_fullscreen').val('true');
				},
				"Esc": function(cm) {
					if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
					$('#less_compiler_fullscreen').val('false');
				},
				"Ctrl-S": function(cm) {					
					save_on_key(cm);
				}
		}
	});
	
	editor.focus();
	editor.setCursor({line: cur_line, ch: cur_ch});
	editor.on('cursorActivity', function(cm){
		var cursor = cm.getCursor();
		$('#less_compiler_line').val(cursor.line);
		$('#less_compiler_ch').val(cursor.ch);	
	});

	save_on_key = function () {
		//$('#less_compiler_edit').val( cm.getValue() );
		cm.save();
		if( $('#less_compiler_key_submit').length === 0) {
			$('<input />', {
				'type': 'text',
				'id': 'less_compiler_key_submit',
				'name': 'less_compiler_submit',
				'value': '1',
			}).appendTo('#less_compiler_edit_form');
		}
		//$('#less_compiler_submit').submit();
	};
		

});
