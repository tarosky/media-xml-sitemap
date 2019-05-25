<?php

namespace Tarosky\MediaXmlSitemap\Admin;

use Tarosky\MediaXmlSitemap\Pattern\Singleton;
use Tarosky\MediaXmlSitemap\Utility\Util;

/**
 * Setting admin screen.
 *
 * @package Media_Xml_Sitemap
 */
class Admin extends Singleton {

	/**
	 * Constructor
	 */
	protected function init() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_init', [ $this, 'admin_init' ] );
	}

	/**
	 * Register admin menu.
	 */
	public function admin_menu() {
		add_options_page(
			__( 'Media Xml Sitemap', 'media-xml-sitemap' ),
			__( 'Media Xml Sitemap', 'media-xml-sitemap' ),
			'manage_options',
			$this->slug,
			[ $this, 'display' ]
		);
	}

	/**
	 * Register settings.
	 */
	public function admin_init() {
		register_setting( $this->slug, $this->slug );

		add_settings_section( 'sitemap_settings', __( 'XML Sitemap Settings', 'media-xml-sitemap' ), function () {
			printf(
				'<p class="description">%s</p>',
				esc_html__( 'Please set up settings for XML Sitemaps.', 'media-xml-sitemap' )
			);
		}, $this->slug );

		add_settings_field(
			'sitemap_is_enable',
			__( 'Enable sitemap', 'media-xml-sitemap' ),
			[ $this, 'sitemap_is_enable_callback' ],
			$this->slug,
			'sitemap_settings'
		);

		add_settings_field(
			'sitemap_post_types',
			__( 'Enable post types', 'media-xml-sitemap' ),
			[ $this, 'sitemap_post_types_callback' ],
			$this->slug,
			'sitemap_settings'
		);

		add_settings_field(
			'sitemap_within_days',
			__( 'Within days', 'media-xml-sitemap' ),
			[ $this, 'sitemap_within_days_callback' ],
			$this->slug,
			'sitemap_settings'
		);

		add_settings_section( 'news_settings', __( 'Google News Sitemap Settings', 'media-xml-sitemap' ), function () {
			printf(
				'<p class="description">%s</p>',
				esc_html__( 'Please set up settings for Google News Sitemap.', 'media-xml-sitemap' )
			);
		}, $this->slug );

		add_settings_field(
			'news_is_enable',
			__( 'Enable sitemap', 'media-xml-sitemap' ),
			[ $this, 'news_is_enable_callback' ],
			$this->slug,
			'news_settings'
		);

		add_settings_field(
			'news_post_types',
			__( 'Enable post types', 'media-xml-sitemap' ),
			[ $this, 'news_post_types_callback' ],
			$this->slug,
			'news_settings'
		);

		add_settings_field(
			'news_within_days',
			__( 'Within days', 'media-xml-sitemap' ),
			[ $this, 'news_within_days_callback' ],
			$this->slug,
			'news_settings'
		);
	}

	/**
	 * Render callback for sitemap is enable.
	 */
	public function sitemap_is_enable_callback() {
		$sitemap_is_enable = ! empty( $this->options['sitemap_is_enable'] );
		?>
		<label>
			<input
				type="checkbox"
				name="<?php echo $this->slug; ?>[sitemap_is_enable][]"
				value="1"
				<?php if ( $sitemap_is_enable ) : ?>
					checked="checked"
				<?php endif; ?>
			/>
		</label>
		<?php
	}

	/**
	 * Render callback for sitemap post types.
	 */
	public function sitemap_post_types_callback() {
		foreach ( Util::available_post_types() as $post_type ) :
			?>
			<label>
				<input
					type="checkbox"
					name="<?php echo $this->slug; ?>[sitemap_post_types][]"
					value="<?php echo $post_type->name; ?>"
					<?php if ( $this->is_post_type_checked( $post_type->name, 'sitemap_post_types' ) ) : ?>
						checked="checked"
					<?php endif; ?>
				/>
				<?php echo $post_type->labels->name; ?>
				<br />
			</label>
		<?php
		endforeach;
	}

	/**
	 * Render callback for sitemap within days.
	 */
	public function sitemap_within_days_callback() {
		$sitemap_within_days = isset( $this->options['sitemap_within_days'] ) ? $this->options['sitemap_within_days'] : '';
		?>
		<input name="<?php echo $this->slug; ?>[sitemap_within_days]" type="number" step="1" min="1"
		       id="sitemap_within_days" value="<?php echo esc_attr( $sitemap_within_days ); ?>"
		       class="small-text">
		<p class="description"><?php printf( __( 'Default is <code>%s</code>.', 'media-xml-sitemap' ), Util::default_sitemap_within_days() ); ?></p>
		<?php
	}

	/**
	 * Render callback for news sitemap is enable.
	 */
	public function news_is_enable_callback() {
		$news_is_enable = ! empty( $this->options['news_is_enable'] );
		?>
		<label>
			<input
				type="checkbox"
				name="<?php echo $this->slug; ?>[news_is_enable][]"
				value="1"
				<?php if ( $news_is_enable ) : ?>
					checked="checked"
				<?php endif; ?>
			/>
		</label>
		<?php
	}

	/**
	 * Render callback for news post types.
	 */
	public function news_post_types_callback() {
		foreach ( Util::available_post_types() as $post_type ) :
			?>
			<label>
				<input
					type="checkbox"
					name="<?php echo $this->slug; ?>[news_post_types][]"
					value="<?php echo $post_type->name; ?>"
					<?php if ( $this->is_post_type_checked( $post_type->name, 'news_post_types' ) ) : ?>
						checked="checked"
					<?php endif; ?>
				/>
				<?php echo $post_type->labels->name; ?>
			</label>
			<br />
		<?php
		endforeach;
	}

	/**
	 * Render callback for news within days.
	 */
	public function news_within_days_callback() {
		$news_within_days = isset( $this->options['news_within_days'] ) ? $this->options['news_within_days'] : '';
		?>
		<input name="<?php echo $this->slug; ?>[news_within_days]" type="number" step="1" min="1"
		       id="news_within_days" value="<?php echo esc_attr( $news_within_days ); ?>"
		       class="small-text">
		<p class="description"><?php printf( __( 'Default is <code>%s</code>.', 'media-xml-sitemap' ), Util::default_news_within_days() ); ?></p>
		<?php
	}

	/**
	 * Detect if the post type is checked.
	 */
	public function is_post_type_checked( $post_type, $option_key ) {
		if ( empty( $this->options[ $option_key ] ) ) {
			return false;
		}
		foreach ( $this->options[ $option_key ] as $enabled_post_type ) {
			if ( $enabled_post_type === $post_type ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Admin menu render callback.
	 */
	public function display() {
		$action = untrailingslashit( admin_url() ) . '/options.php';
		?>
		<div class="wrap media-xml-sitemap-settings">
			<h1 class="wp-heading-inline"><?php _e( 'Media XML Sitemap Settings', 'media-xml-sitemap' ); ?></h1>
			<form action="<?php echo esc_url( $action ); ?>" method="post">
				<?php
				settings_fields( $this->slug );
				do_settings_sections( $this->slug );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
