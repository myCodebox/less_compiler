<?php

	class less_compiler {

		protected static $package;
		protected static $dir_less;
		protected static $dir_css;
		protected static $formatter;
		protected static $demo;


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
			include_once(rex_path::addon(self::$package, 'vendor/lessphp/lessc.inc.php'));

			if( self::$demo == '1' ) self::less_output_demo();

			$less = new lessc;
			if( self::$formatter != '' ) $less->setFormatter(self::$formatter);
			try {
				$less->checkedCompile(self::$dir_less.'/design.less', self::$dir_css.'/design.min.css');
				self::less_output_filter();
			}
			catch (exception $e) {
				echo "[LESS Compiler] fatal error: " . $e->getMessage();
				die();
			}

		}



		private static function less_output_filter() {
			rex_extension::register('OUTPUT_FILTER', function(rex_extension_point $ep) {
				$content = $ep->getSubject();
				$insert = '<!-- Add CSS from the LESS Comiler -->'."\n";
				$insert .= '<link href="'.self::$dir_css.'/design.min.css" rel="stylesheet">';
				if( self::$demo == '1' ) {
					$insert .= '<link href="'.rex_url::addonAssets(self::$package, 'css/demo_less_compiler.css').'" rel="stylesheet">';
				}

				$search = '</head>';
				$content = str_replace($search, $insert.$search, $content);
				$ep->setSubject($content);
			}, 'NORMAL');
		}



		public static function less_page_header($package) {
			rex_extension::register('PAGE_HEADER', function(rex_extension_point $ep) {
				$content = $ep->getSubject();
				$content = '<script src="'.rex_url::addonAssets($ep->getParam('package'), 'js/main_less_compiler.js').'" type="text/javascript"></script>';
				$ep->setSubject($content);
			}, 'NORMAL', array('package' => $package));
		}


		private static function less_output_demo() {
			rex_extension::register('OUTPUT_FILTER', function(rex_extension_point $ep) {
				$content = $ep->getSubject();
				$insert = '<div id="less_compiler_demo">
							LESS Compiler demo active<br />
							<pre>
								<span>LESS Path: '.self::$dir_less.'</span>
								<span> CSS Path: '.self::$dir_css.'</span>
							</pre>
						</div>'."\n";

				$search = '</body>';
				$content = str_replace($search, $insert.$search, $content);
				$ep->setSubject($content);
			});
		}


		public static function less_raw_file() {
			$date = date('d.m.Y');
			$content = <<<EOF
/* -------------------------------------------------------------------------------------------------
       __    ________________    ______                           __
      / /   / ____/ ___/ ___/   / ____/___  ____ _   _____  _____/ /____  _____
     / /   / __/  \__ \\__ \    / /   / __ \/ __ \ | / / _ \/ ___/ __/ _ \/ ___/
    / /___/ /___ ___/ /__/ /  / /___/ /_/ / / / / |/ /  __/ /  / /_/  __/ /
   /_____/_____//____/____/   \____/\____/_/ /_/|___/\___/_/   \__/\___/_/

-- FILE: design.less -- DATE: $date -------------------------------------------------------- */
EOF;
			return $content."\n\n";
		}


	}
