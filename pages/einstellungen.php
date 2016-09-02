<?php


	$content = '';

	if (rex_post('config-submit', 'boolean')) {
		$this->setConfig(
			rex_post('config', [
				['less_compiler_aktiv', 'string'],
				['less_compiler_formatter', 'string'],
				['less_compiler_path_css', 'string'],
				['less_compiler_path_less', 'string'],
				['less_compiler_demo', 'string'],
			]
		));

		$content .= rex_view::info('Änderung gespeichert');
	}

	$less_save_btn	= $this->i18n('less_compiler_save');
	$less_checked 	= ($this->getConfig('less_compiler_aktiv')=="1") ? 'checked="checked"' : '';
	$less_formatter = $this->getConfig('less_compiler_formatter');
	$less_path_css 	= $this->getConfig('less_compiler_path_css');
	$less_path_less = $this->getConfig('less_compiler_path_less');
	$less_demo 		= ($this->getConfig('less_compiler_demo')=="1") ? 'checked="checked"' : '';

	if (rex_post('config-submit', 'boolean')) {
		$dir_css = rex_url::base($less_path_css);
		rex_file::delete($dir_css.'/design.min.css');
	}

	$less_formatter_array = array(
		array('lessjs','LessJs (default)'),
		array('compressed','Compressed'),
		array('classic','Classic')
	);

	$less_formatter_options = '';
	foreach ( $less_formatter_array AS $val) {
		$less_formatter_selected = ($less_formatter == $val[0]) ? 'selected' : '';
		$less_formatter_options .= '<option value="'.$val[0].'" '.$less_formatter_selected.'>'.$val[1].'</option>';
	}

	//echo rex_path::addonAssets(rex_package::getName(), 'css/demo_less_compiler.css');


	$content .= '
		<form action="'.rex_url::currentBackendPage().'" method="post" id="less_compiler_setting">
			<div class="container-fluid">

				<div class="col-xs-12">
					<input class="rex-form-chk" type="checkbox" id="rex-form-less_compiler_aktiv" name="config[less_compiler_aktiv]" value="1" '.$less_checked.' />
					<label for="rex-form-less_compiler_aktiv">'.$this->i18n("less_compiler_aktiv").'</label>
					<br />
					<br />

					<label for="rex-form-less_compiler_aktiv">'.$this->i18n("less_compiler_formatter").'</label>
					<select class="form-control rex-form-sel" id="rex-form-less_compiler_formatter" name="config[less_compiler_formatter]">
						'.$less_formatter_options.'
					</select>
					<br />

					<label for="rex-form-less_compiler_path_less">'.$this->i18n("less_compiler_path_less").'</label>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">./</span>
						<input type="text" class="form-control" id="rex-form-less_compiler_path_less" name="config[less_compiler_path_less]" value="'.$less_path_less.'" autocomplete="off" aria-describedby="less_compiler_path_less">
						<div class="input-group-addon">/design.less</div>
					</div>
					<br />

					<label for="rex-form-less_compiler_path_css">'.$this->i18n("less_compiler_path_css").'</label>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon3">./</span>
						<input type="text" class="form-control" id="rex-form-less_compiler_path_css" name="config[less_compiler_path_css]" value="'.$less_path_css.'" autocomplete="off" aria-describedby="less_compiler_path_css">
						<div class="input-group-addon">/design.min.css</div>
					</div>
					<br />

					<input class="rex-form-chk" type="checkbox" id="rex-form-less_compiler_demo" name="config[less_compiler_demo]" value="1" '.$less_demo.' />
					<label for="rex-form-less_compiler_demo">'.$this->i18n("less_compiler_demo").'</label>
				</div>


				<div class="col-xs-12">
					<hr />
					<button class="btn btn-save" type="submit" name="config-submit" value="1" title="'.$less_save_btn.'">'.$less_save_btn.'</button>
				</div>

			</div>
		</form>
	';

	$fragment = new rex_fragment();
	$fragment->setVar('class', 'edit');
	$fragment->setVar('title', $this->i18n('less_compiler_setting'));
	$fragment->setVar('body', $content, false);
	echo $fragment->parse('core/page/section.php');




	$content = '
		<p>Der Compiler benötigt folgende Ordner:</p>

		<code>./'.$less_path_less.'/</code><br/>
		<code>./'.$less_path_css.'/</code><br/><br/>

		<p>Im <code>./'.$less_path_less.'/</code> Ordner werden die Dateien <code>styles.less</code> erwartet die dann zu <code>design.min.css</code> kompiliert und in den Ordner <code>./'.$less_path_less.'/</code> gespeichert wird.</p>
	';

	$fragment = new rex_fragment();
	$fragment->setVar('title', $this->i18n('less_compiler_erklaerung_title'));
	$fragment->setVar('body', $content, false);
	echo $fragment->parse('core/page/section.php');

?>
<style>
#less_compiler_setting label {
    font-weight: normal;
}

#less_compiler_setting input[type=checkbox] {
    display: none;
}

#less_compiler_setting input[type=checkbox] + label:before {
    font-family: FontAwesome;
    font-size: 20px;
    width: 30px;
    text-align: center;
    border-radius: 3px;
    background: #E9ECF2;
    border: 1px solid #c3c9d4;
    display: inline-block;
    margin-right: 10px;
}

#less_compiler_setting input[type=checkbox] + label:before {
    padding-left: 2px;
    color: #c3c9d4;
    content: "\f00d";
}

#less_compiler_setting input[type=checkbox] + label:before {
}

#less_compiler_setting input[type=checkbox]:checked + label:before {
    padding-left: 2px;
    color: #3CB594;
    content: "\f00c";
}

#less_compiler_setting input[type=checkbox]:checked + label:before {
}
/*
#less_compiler_setting select {
	padding: 4px;
	margin-bottom: 10px;
}
#less_compiler_setting input[type=text] {
	margin-bottom: 10px;
	min-width: 300px;
}
#less_compiler_setting label {
	min-width: 180px;
}
*/
</style>
