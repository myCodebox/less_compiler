<?php

	class url_checker {
		protected static $ids = [];
		protected static $links = [];

		public static function getIds() {
			self::setIds( rex_category::getRootCategories() );
			self::getArtickle();
			return self::$links;
		}

		private static function setIds($categories) {
			foreach($categories AS $category) {
				self::$ids[] = $category->getValue('id');
				if( $childen = $category->getChildren() ) {
					self::setIds($childen);
				}
			}
		}

		public static function getArtickle() {
			foreach(self::$ids AS $id) {
				$art = new rex_article_content($id);
				$article = $art->getArticle();
				//self::$links[] = [
				//	'id' => $id,
				//	'links' => self::parseArtickle($article),
				//];
				self::parseArtickle($article, $id);
			}
			return false;
		}

		// http://www.the-art-of-web.com/php/parse-links/
		private static function parseArtickle($article, $id = NULL) {
			$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
			if(preg_match_all("/$regexp/siU", $article, $matches)) {
				self::$links[] = [
					'id' => $id,
					'links' => $matches[2],
				];
			}
			return false;
		}


	}

	/* GET all Links */
	echo '<pre>';
	print_r( url_checker::getIds() );
	echo '</pre>';






	/*
	$allLinks = [];
	foreach($allIds AS $id) {
		$art = new rex_article_content($id);
		$article = $art->getArticle();
		collect::allUrls($article);
		$allLinks[] = collect::getAllUrls();
	}

	echo '<pre>';
	print_r( $allLinks );
	echo '</pre>';
	*/
