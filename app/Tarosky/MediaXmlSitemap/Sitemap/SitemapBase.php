<?php

namespace Tarosky\MediaXmlSitemap\Sitemap;

use Tarosky\MediaXmlSitemap\Pattern\Singleton;

/**
 * Google News Sitemap Base
 *
 * @package Media_Xml_Sitemap
 */
class SitemapBase extends Singleton {

	/**
	 * Constructor
	 */
	protected function init() {
		//
	}

	/**
	 * Response XML header.
	 */
	protected function xml_header( $hours = 1 ) {
		if ( $hours ) {
			$this->expires_header( $hours );
		}
		header( 'Content-Type: text/xml; charset=utf-8', true );
		echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	}

	/**
	 * Response expires header.
	 */
	protected function expires_header( $hours = 1 ) {
		$time = current_time( 'timestamp', true ) + 60 * 60 * $hours;
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', $time ) . ' GMT' );
		header( "Cache-Control: max-age=" . ( 60 * 60 * $hours ) );
	}
}
