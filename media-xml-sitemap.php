<?php
/**
 * Plugin Name:     Media Xml Sitemap
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     This is a plugin to create feeds for the XML Sitemap and the Google News Sitemap.
 * Author:          ko31
 * Author URI:      https://go-sign.info
 * Text Domain:     media-xml-sitemap
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Media_Xml_Sitemap
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );

/**
 * Initialize plugin.
 */
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain(
		'media-xml-sitemap',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);

	Tarosky\MediaXmlSitemap::get_instance()->register();
} );

/**
 * Deactivation.
 */
register_deactivation_hook( __FILE__, function () {
	Tarosky\MediaXmlSitemap::get_instance()->deactivation();
} );
