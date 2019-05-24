<?php

namespace Tarosky\MediaXmlSitemap\Utility;

use Tarosky\MediaXmlSitemap;

/**
 * Setting utility.
 *
 * @package Media_Xml_Sitemap
 */
class Util {

	/**
	 * Constructor
	 */
	public function __construct() {
		//
	}

	/**
	 * Default sitemap within days.
	 */
	public static function default_sitemap_within_days() {

		/**
		 * Filters default sitemap within days.
		 *
		 * @param int
		 */
		return apply_filters( 'mxs_default_sitemap_within_days', 7 );
	}

	/**
	 * Default news within days.
	 */
	public static function default_news_within_days() {

		/**
		 * Filters default news within days.
		 *
		 * @param int
		 */
		return apply_filters( 'mxs_default_news_within_days', 2 );
	}

	/**
	 * Get available post_types.
	 */
	public static function available_post_types() {
		$post_types = [];
		foreach (
			get_post_types( [
				'show_ui' => true
			], 'objects' ) as $post_type
		) {
			if ( in_array( $post_type->name, [ 'attachment', 'wp_block' ] ) ) {
				continue;
			}
			$post_types[] = $post_type;
		}

		/**
		 * Filters the available post types.
		 *
		 * @param array $post_types
		 */
		$post_types = apply_filters( 'mxs_available_post_types', $post_types );

		return $post_types;
	}
}
