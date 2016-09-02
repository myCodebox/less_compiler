<?php

	if ($this->hasConfig('less_compiler_aktiv')) {

        $dir_css = rex_url::base($this->getConfig('less_compiler_path_css'));

        if (is_dir($dir_css)) {
			rex_file::delete($dir_css.'/design.min.css');
		}
	}
