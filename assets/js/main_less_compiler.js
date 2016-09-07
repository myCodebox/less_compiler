$(document).on('rex:ready', function (event, container) {

	if(typeof cur_line !== undefined){
		var cur_line = 0;
	}
	if(typeof cur_ch !== undefined){
		var cur_ch = 0;
	}

	include_hidden_imputs = function() {
		var input_fields = {
			'less_compiler_submit': '1',
			'less_compiler_fullscreen': 'false',
			'less_compiler_line': cur_line,
			'less_compiler_ch': cur_ch,
		};
		if( $('#less_compiler_edit_form_hidden') ) {
			$('<div />', {
				'id': 'less_compiler_edit_form_hidden',
				//'style': 'position: fixed; top: 0px; left: 0px; background-color: #e34; padding:5px;'
			}).appendTo('#less_compiler_edit_form');
			for (var key in input_fields) {
				$('<input />', {
					'type': 'hidden',
					'id': key,
					'name': key,
					'value': input_fields[key],
				}).appendTo('#less_compiler_edit_form_hidden');
			}
		}
	};
	include_hidden_imputs();

	var editor = CodeMirror.fromTextArea(document.getElementById("less_compiler_edit"), {
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
				$('#less_compiler_fullscreen').val(cm.getOption("fullScreen"));
			},
			"Esc": function(cm) {
				if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
				$('#less_compiler_fullscreen').val(false);
			},
			"Ctrl-S": function(cm) {
				$('#less_compiler_fullscreen').val(cm.getOption("fullScreen"));
				save_on_key(cm);
			}
		}
	});

	editor.focus();
	editor.setOption("fullScreen", win_fullscreen);
	editor.setCursor({line: cur_line, ch: cur_ch});
	editor.on('cursorActivity', function(cm){
		var cursor = cm.getCursor();
		$('#less_compiler_line').val(cursor.line);
		$('#less_compiler_ch').val(cursor.ch);
	});

	save_on_key = function (cm) {
		cm.save();
		$('#less_compiler_submit').submit();
	};

});
