<?php

namespace Tarosky\MediaXmlSitemap\Pattern;

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
	private static $count;

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

        error_log($class_name);
        self::$count++;
	    error_log(self::$count);

        if ( ! isset( self::$instances[ $class_name ] ) ) {
            self::$instances[ $class_name ] = new $class_name();
        }

	    error_log(print_r(self::$instances,true));

        return self::$instances[ $class_name ];
    }
}
