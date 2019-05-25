<?php

namespace Tarosky\MediaXmlSitemap\Pattern;

use Tarosky\MediaXmlSitemap;

/**
 * Singleton pattern
 *
 * @package Media_Xml_Sitemap
 */
abstract class Singleton {

	/**
	 * @var static[]
	 */
	private static $instances = [];

	/**
	 * Constructor
	 */
	final private function __construct() {
		$this->init();
	}

	/**
	 * Do something in constructor.
	 */
	protected function init() {

	}

	/**
	 * Get singleton instance.
	 *
	 * @return static
	 */
	final public static function get_instance() {
		$class_name = get_called_class();
		if ( ! isset( self::$instances[ $class_name ] ) ) {
			self::$instances[ $class_name ] = new $class_name();
		}

		return self::$instances[ $class_name ];
	}

	/**
	 * Getter
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'db':
				global $wpdb;

				return $wpdb;
				break;
			case 'options':
				return get_option( $this->slug );
				break;
			case 'slug':
				return MediaXmlSitemap::get_instance()->get_slug();
				break;
			default:
				return null;
				break;
		}
	}
}
