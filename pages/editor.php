<?php

	$less_save_btn = $this->i18n('less_compiler_save');

	$compiler_path_less = $this->getConfig('less_compiler_path_less');
	$dir_less = rex_url::base($compiler_path_less);
	$file_less = rex_file::get($dir_less.'/design.less');

	if (rex_post('less_compiler_submit', 'boolean')) {
		$content = rex_post('less_compiler_edit', 'string');
		rex_file::put($dir_less.'/design.less', $content);
		$file_less = rex_file::get($dir_less.'/design.less');
	}
	less_compiler::addLessFile($compiler_path_less.'/design.less');
	less_compiler::combineLessFiles();
?>

<script>
	var win_fullscreen 	= <?php echo rex_post('less_compiler_fullscreen', 'string', 'false'); ?>;
	var cur_line 		= <?php echo rex_post('less_compiler_line', 'string', '8'); ?>;
	var cur_ch 			= <?php echo rex_post('less_compiler_ch', 'string', '0'); ?>;
</script>


<form id="less_compiler_edit_form" name="less_compiler_edit_form" action="<?php echo rex_url::currentBackendPage(); ?>" method="post">
	<textarea  id="less_compiler_edit" name="less_compiler_edit" style="width: 100%; height: 400px;"><?php echo $file_less; ?></textarea><br />
	<div id="info_save">
		<p class="text-muted pull-left">
			<kbd>ctrl + S</kbd> Save
			<kbd>ctrl + Z</kbd> Undo
			<kbd><kbd>ctrl + Y</kbd></kbd> Redo
			<kbd>F11</kbd> Fullscreen/Close
			<kbd>Esc</kbd> Close fullscreen
			| <a href="http://lesscss.org/" target="_blank">LESS Doc</a>
		</p>
		<button id="less_compiler_submit" name="less_compiler_submit" class="btn btn-save pull-right" type="submit" value="1" title="'.$less_save_btn.'"><?php echo $less_save_btn; ?></button>
	</div>
</form>
