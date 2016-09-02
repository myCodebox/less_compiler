<?php

	if ($this->hasConfig('less_compiler_aktiv')) {

        $dir_less 	= rex_url::base($this->getConfig('less_compiler_path_less'));
        $dir_css 	= rex_url::base($this->getConfig('less_compiler_path_css'));

        if (is_dir($dir_less)) {
			rex_dir::delete($dir_less);
			rex_file::delete($dir_css.'/design.min.css');
		}
	}
