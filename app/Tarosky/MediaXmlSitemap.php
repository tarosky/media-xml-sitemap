<?php

namespace Tarosky;

use Tarosky\MediaXmlSitemap\Admin\Admin;
use Tarosky\MediaXmlSitemap\Hooks\Rules;
use Tarosky\MediaXmlSitemap\Sitemap\News;
use Tarosky\MediaXmlSitemap\Pattern\Singleton;

/**
 * Run this plugin.
 *
 * @package Media_Xml_Sitemap
 */
class MediaXmlSitemap extends Singleton {

	private $slug = 'media-xml-sitemap';

	/**
	 * Register
	 */
	public function register() {
		if ( is_admin() ) {
			Admin::get_instance();
		}

		Rules::get_instance();

		// TODO: sitemap
		News::get_instance();
//		Sitemap::get_instance();
	}

	/**
	 * Get plugin slug.
	 */
	public function get_slug() {
		return $this->slug;
	}
}
