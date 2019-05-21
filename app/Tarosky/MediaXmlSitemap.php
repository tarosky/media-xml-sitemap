<?php

namespace Tarosky;

use Tarosky\MediaXmlSitemap\Admin;
use Tarosky\MediaXmlSitemap\Pattern\Singleton;

/**
 * Run this plugin.
 *
 * @package Media_Xml_Sitemap
 */
class MediaXmlSitemap extends Singleton {

	private $slug = 'media-xml-sitemap';

	/**
	 * Constructor
	 */
	protected function init() {
		//
	}

	/**
	 * Register
	 */
	public function register() {
		if ( is_admin() ) {
			Admin::get_instance();
		}

		// TODO: sitemap
	}

	/**
	 * Get plugin slug.
	 */
	public function get_slug() {
		return $this->slug;
	}
}
