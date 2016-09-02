<?php

    if (!$this->hasConfig('less_compiler_aktiv')) {
        $this->setConfig('less_compiler_aktiv', '1');
        $this->setConfig('less_compiler_formatter', 'lessjs');
        $this->setConfig('less_compiler_path_less', 'resources/less');
        $this->setConfig('less_compiler_path_css', 'resources/css');
        $this->setConfig('less_compiler_demo', '0');

        $dir_less 	= rex_url::base($this->getConfig('less_compiler_path_less'));
        $dir_css 	= rex_url::base($this->getConfig('less_compiler_path_css'));

        if (!is_dir($dir_less)) rex_dir::create($dir_less);
		if (!is_dir($dir_css)) 	rex_dir::create($dir_css);		

		$content = less_compiler::less_raw_file();
        rex_file::put($dir_less.'/design.less',$content);
    }
