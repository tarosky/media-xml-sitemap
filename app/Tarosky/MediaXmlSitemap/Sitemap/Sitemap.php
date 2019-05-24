<?php

namespace Tarosky\MediaXmlSitemap\Sitemap;

use Tarosky\MediaXmlSitemap;
use Tarosky\MediaXmlSitemap\Utility\Util;

/**
 * XML Sitemap
 *
 * @package Media_Xml_Sitemap
 */
class Sitemap extends SitemapBase {

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

		$mxs_type = $wp_query->get( 'mxs_type' );

		switch ( $mxs_type ) {
			case 'index':
				$wp_query->set( 'feed', 'sitemap' );
				add_action( 'do_feed_sitemap', [ $this, 'sitemap_index' ] );
				break;
			default:
				//TODO：表示可能な投稿タイプかチェック
				if ( true ) {
					$wp_query->set( 'feed', 'sitemap' );
				} else {
					$wp_query->set_404();
				}
				break;
		}
	}

	/**
	 * Response sitemap index.
	 */
	public function sitemap_index() {
		$elements = [];

		$post_types = implode( ',', array_map( function ( $type ) {
			return "'{$type}'";
		}, $this->options['sitemap_post_types'] ) );
		$query      = <<<SQL
            SELECT post_type, MAX(post_modified) AS max_modified
              FROM {$this->db->posts}
             WHERE post_type IN ({$post_types})
              AND post_status = 'publish'
             GROUP BY post_type
SQL;

		foreach ( $this->db->get_results( $query ) as $result ) {
			$elements[] = [
				'loc'     => $result->post_type,
				'lastmod' => $result->max_modified,
			];
		}

		$this->xml_header();
		?>
		<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
			<?php
			foreach ( $elements as $element ):
				?>
				<sitemap>
					<loc><?php echo home_url( "/sitemap/{$element['loc']}.xml" ); ?></loc>
					<lastmod><?php echo mysql2date( \DateTime::W3C, $element['lastmod'] ); ?></lastmod>
				</sitemap>
			<?php
			endforeach;
			?>
		</sitemapindex>
		<?php
	}

	/**
	 * Getter
	 *
	 * @param string $name
	 *
	 * @return null|\wpdb
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'db':
				global $wpdb;

				return $wpdb;
				break;
			default:
				return null;
				break;
		}
	}
}
