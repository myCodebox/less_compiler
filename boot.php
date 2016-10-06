<?php

	$compiler_formatter = $this->getConfig('less_compiler_formatter');
	$compiler_path_less = $this->getConfig('less_compiler_path_less');
	$compiler_path_css 	= $this->getConfig('less_compiler_path_css');
	$compiler_demo 		= $this->getConfig('less_compiler_demo');

	$dir_less 	= $compiler_path_less;
	$dir_css 	= $compiler_path_css;

	if (!rex::isBackend()) {

		if ($this->getConfig('less_compiler_aktiv') == '1') {

			if (!is_dir($dir_less)) rex_dir::create($dir_less);
			if (!is_dir($dir_css)) 	rex_dir::create($dir_css);

			less_compiler::set_all(
				rex_package::getName(),
				$dir_less,
				$dir_css,
				$compiler_formatter,
				$compiler_demo
			);
			less_compiler::less2css();
		}

	} else {
		if( rex_get('page') == rex_package::getName().'/editor' ) {
			rex_view::addJsFile( $this->getAssetsUrl('js/main_less_compiler.js') );
		}
		rex_view::addCSSFile( $this->getAssetsUrl('css/less_compiler.css') );
	}

	if(rex_finder::factory(rex_url::base($dir_less))->count() == 0) {
		$content = less_compiler::less_raw_file();
		rex_file::put($dir_less.'/design.less',$content);
	}
