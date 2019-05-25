<?php

namespace Tarosky\MediaXmlSitemap\Sitemap;

use Tarosky\MediaXmlSitemap\Utility\Util;

/**
 * Google News Sitemap
 *
 * @package Media_Xml_Sitemap
 */
class News extends SitemapBase {

	/**
	 * Constructor
	 */
	protected function init() {
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
		if ( ! $mxs_type ) {
			return;
		}
		$mxs_post_type = $wp_query->get( 'mxs_post_type' );

		switch ( $mxs_type ) {
			case 'newsindex':
				$wp_query->set( 'feed', 'sitemap' );
				add_action( 'do_feed_sitemap', [ $this, 'news_index' ] );
				break;
			case 'news':
				if ( in_array( $mxs_post_type, $this->options['news_post_types'] ) ) {
					$wp_query->set( 'feed', 'sitemap' );
					$wp_query->set( 'post_type', $mxs_post_type );
					$wp_query->set( 'post_status', 'publish' );
					$wp_query->set( 'orderby', [ 'modified' => 'desc' ] );
					$wp_query->set( 'posts_per_rss', 50000 );
					$news_within_days = $this->options['news_within_days'] ?: Util::default_news_within_days();
					$days_ago         = new \DateTime( 'now', new \DateTimeZone( get_option( 'timezone_string' ) ) );
					$days_ago->sub( new \DateInterval( sprintf( 'P%dD', $news_within_days ) ) );
					$wp_query->set( 'date_query', [
						[
							'after' => [
								'year'  => $days_ago->format( 'Y' ),
								'month' => $days_ago->format( 'n' ),
								'day'   => $days_ago->format( 'j' ),
							],
						],
					] );
					add_action( 'do_feed_sitemap', [ $this, 'sitemap_news' ] );
				} else {
					$wp_query->set_404();
					status_header( 404 );
					exit;
				}
				break;
			default:
				break;
		}
	}

	/**
	 * Response news index.
	 */
	public function news_index() {
		$elements   = [];
		$post_types = implode( ',', array_map( function ( $type ) {
			return "'{$type}'";
		}, $this->options['news_post_types'] ) );
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
					<loc><?php echo home_url( "/sitemap-news/{$element['loc']}.xml" ); ?></loc>
					<lastmod><?php echo mysql2date( \DateTime::W3C, $element['lastmod'] ); ?></lastmod>
				</sitemap>
			<?php
			endforeach;
			?>
		</sitemapindex>
		<?php
	}

	/**
	 * Response Google News Sitemap.
	 */
	public function sitemap_news() {
		$items = [];
		while ( have_posts() ) {
			the_post();
			$item    = [
				'url'     => get_permalink(),
				'lastmod' => get_post_modified_time( 'Y-m-d H:i:s' ),
				'title'   => get_the_title(),
			];
			$items[] = $item;
		}

		$publication_name     = $this->options['news_publication_name'] ?: get_bloginfo();
		$publication_language = get_bloginfo( 'language' );

		$this->xml_header();
		?>
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
		        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
			<?php
			foreach ( $items as $item ):
				?>
				<url>
					<loc><?php echo esc_url( $item['url'] ); ?></loc>
					<news:news>
						<news:publication>
							<news:name><?php echo esc_html( $publication_name ); ?></news:name>
							<news:language><?php echo esc_html( $publication_language ); ?></news:language>
						</news:publication>
						<news:publication_date><?php echo mysql2date( \DateTime::W3C, $item['lastmod'] ); ?></news:publication_date>
						<news:title><?php echo esc_html( $item['title'] ); ?></news:title>
					</news:news>
				</url>
			<?php
			endforeach;
			?>
		</urlset>
		<?php
	}
}
