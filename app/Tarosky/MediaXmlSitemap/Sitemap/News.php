<?php

namespace Tarosky\MediaXmlSitemap\Sitemap;

use Tarosky\MediaXmlSitemap;
use Tarosky\MediaXmlSitemap\Utility\Util;

/**
 * Google News Sitemap
 *
 * @package Media_Xml_Sitemap
 */
class News extends SitemapBase {

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
			case 'news':
				$wp_query->set( 'feed', 'sitemap' );
				add_action( 'do_feed_sitemap', [ $this, 'sitemap_news' ] );
				break;
			default:
				break;
		}
	}

	/**
	 * Response Google News Sitemap.
	 */
	public function sitemap_news() {
		$post_types = $this->options['news_post_types'];
		$news_within_days = $this->options['news_within_days'] ?: Util::default_news_within_days();

		$days_ago = new \DateTime( 'now', new \DateTimeZone( get_option( 'timezone_string' ) ) );
		$days_ago->sub( new \DateInterval( sprintf( 'P%dD', $news_within_days ) ) );

		$posts = get_posts( [
			'post_type'      => $post_types,
			'post_status'    => 'publish',
			'posts_per_page' => 1000,
			'date_query'     => [
				[
					'after' => [
						'year'  => $days_ago->format( 'Y' ),
						'month' => $days_ago->format( 'n' ),
						'day'   => $days_ago->format( 'j' ),
					],
				],
			],
		] );

		$publication_name     = get_bloginfo();
		$publication_language = get_bloginfo( 'language' );

		$this->xml_header();

		?>
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
		        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
			<?php
			foreach ( $posts as $post ):
				?>
				<url>
					<loc><?php echo esc_url( get_permalink( $post ) ); ?></loc>
					<news:news>
						<news:publication>
							<news:name><?php echo esc_html( $publication_name ); ?></news:name>
							<news:language><?php echo esc_html( $publication_language ); ?></news:language>
						</news:publication>
						<news:publication_date><?php echo mysql2date( \DateTime::W3C, $post->post_date ); ?></news:publication_date>
						<news:title><?php echo esc_html( $post->post_title ); ?></news:title>
					</news:news>
				</url>
			<?php
			endforeach;
			?>
		</urlset>
		<?php
	}
}
