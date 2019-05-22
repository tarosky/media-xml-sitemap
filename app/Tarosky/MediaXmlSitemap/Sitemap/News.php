<?php

namespace Tarosky\MediaXmlSitemap\Sitemap;

use Tarosky\MediaXmlSitemap;
use Tarosky\MediaXmlSitemap\Pattern\Singleton;

/**
 * Google News Sitemap
 *
 * @package Media_Xml_Sitemap
 */
class News extends Singleton {

	private $slug;

	private $options;

	/**
	 * Constructor
	 */
	protected function init() {
		$this->slug    = MediaXmlSitemap::get_instance()->get_slug();
		$this->options = get_option( $this->slug );

		add_action( 'pre_get_posts', [ $this, 'pre_get_posts' ] );
	}

	/**
	 * Do sitemaps.
	 */
	public function pre_get_posts( \WP_Query &$wp_query ) {
		if ( is_admin() || ! $wp_query->is_main_query() ) {
			return;
		}

		$wp_query->set( 'feed', 'sitemap' );
		$mxs_type = $wp_query->get( 'mxs_type' );

		switch ( $mxs_type ) {
			case 'index':
				add_action( 'do_feed_sitemap', [ $this, 'sitemap_index' ] );
				break;
			case 'news':
				add_action( 'do_feed_sitemap', [ $this, 'sitemap_news' ] );
				break;
			default:
				//TODO：表示可能な投稿タイプかチェック
				if ( true ) {
				} else {
					$wp_query->set_404();
				}
				break;
		}
	}

	/**
	 * Response Google News Sitemap.
	 */
	public function sitemap_news() {
		//TODO:
		echo "newssitemap";
	}
}
