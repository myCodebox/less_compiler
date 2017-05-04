<?php

	class less_compiler {

		protected static $package;
		protected static $dir_less;
		protected static $dir_css;
		protected static $formatter;
		protected static $demo;


		private static $lessFiles = [];


		public static function addLessFile($file) {
			if (isset(self::$lessFiles) && in_array($file, self::$lessFiles)) {
				throw new rex_exception(sprintf('The LESS file "%s" is already added".', $file));
			}
			self::$lessFiles[] = $file;
		}

		public static function getLessFiles() {
			return self::$lessFiles;
		}

		public static function combineLessFiles() {
			$arr = self::getLessFiles();
			if( count($arr) > 0 ) {
				krsort($arr);

				$content = '';
				foreach ($arr as $key => $val) {
					if( rex_file::get(rex_url::base($val)) ) {
						$file = (rex::isBackend()) ? rex_url::base($val): $val;
						$content .= '// FILE: '.$val."\n";
						$content .= rex_file::getOutput( $file );
						$content .= "\n\n";
					}
				}

				// echo '<pre>';
				// print_r($arr);
				// print_r($content);
				// echo '</pre>';

				rex_file::put(self::$dir_less.'/design_combine.less',$content);
			}
		}


		public static function set_all($package, $dir_less, $dir_css, $formatter, $demo) {
			self::$package 		= $package;
			self::$dir_less 	= $dir_less;
			self::$dir_css 		= $dir_css;
			self::$formatter 	= $formatter;
			self::$demo 		= $demo;
		}


		public static function set_package($package) {
			self::$package = $package;
		}
		public static function set_dir_less($dir_less) {
			self::$dir_less = $dir_less;
		}
		public static function set_dir_css($dir_css) {
			self::$dir_css = $dir_css;
		}
		public static function set_formater($formatter) {
			self::$formatter = $formatter;
		}
		public static function set_demo($demo) {
			self::$demo = $demo;
		}



		public static function less2css() {
			self::addLessFile(self::$dir_less.'/design.less');
			self::combineLessFiles();

			$file = rex_path::addon(self::$package, 'vendor/lessphp/lessc.inc.php');

			if (file_exists($file)) {
				include_once(rex_path::addon(self::$package, 'vendor/lessphp/lessc.inc.php'));

				if( self::$demo == '1' ) self::less_output_demo();

				$less = new lessc;
				if( self::$formatter != '' ) $less->setFormatter(self::$formatter);
				try {
					// $less->checkedCompile(self::$dir_less.'/design.less', self::$dir_css.'/design.min.css');
					$less->checkedCompile(self::$dir_less.'/design_combine.less', self::$dir_css.'/design.min.css');
					self::less_output_filter();
				}
				catch (exception $e) {
					echo "[LESS Compiler] fatal error: " . $e->getMessage();
					die();
				}
			}
		}



		private static function less_output_filter() {
			rex_extension::register('OUTPUT_FILTER', function(rex_extension_point $ep) {
				$content = $ep->getSubject();
				$insert = '<!-- Add CSS from the LESS Comiler -->'."\n";
				$insert .= '<link href="'.rex_url::base(self::$dir_css.'/design.min.css').'" rel="stylesheet">';
				if( self::$demo == '1' ) {
					$insert .= '<link href="'.rex_url::addonAssets(self::$package, 'css/demo_less_compiler.css').'" rel="stylesheet">';
				}

				$search = '</head>';
				$content = str_replace($search, $insert.$search, $content);
				$ep->setSubject($content);
			}, 'NORMAL');
		}


		private static function less_output_demo() {
			rex_extension::register('OUTPUT_FILTER', function(rex_extension_point $ep) {
				$content = $ep->getSubject();
				$insert = '<div id="less_compiler_demo">
							LESS Compiler demo works!<br />
							<pre>
								<span>LESS Path: '.self::$dir_less.'</span><br />
								<span> CSS Path: '.self::$dir_css.'</span>
							</pre>
						</div>'."\n";
				$insert = preg_replace('/\s+/S', " ", $insert);
				$search = '</body>';
				$content = str_replace($search, $insert.$search, $content);
				$ep->setSubject($content);
			});
		}


		public static function less_raw_file() {
			$date = date('d.m.Y');
			$content = <<<EOF
/* -------------------------------------------------------------------------------------------------
        __    ________________    ______                      _ __
       / /   / ____/ ___/ ___/   / ____/___  ____ ___  ____  (_) /__  _____
      / /   / __/  \__ \\__ \    / /   / __ \/ __ `__ \/ __ \/ / / _ \/ ___/
     / /___/ /___ ___/ /__/ /  / /___/ /_/ / / / / / / /_/ / / /  __/ /
    /_____/_____//____/____/   \____/\____/_/ /_/ /_/ .___/_/_/\___/_/
                                                   /_/
-- FILE: design.less -- DATE: $date -------------------------------------------------------- */
EOF;
			return $content."\n\n";
		}


	}
