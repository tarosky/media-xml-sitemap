<?php

namespace Tarosky\MediaXmlSitemap\Hooks;

use Tarosky\MediaXmlSitemap\Pattern\Singleton;

/**
 * Setting rewrite rules.
 *
 * @package Media_Xml_Sitemap
 */
class Rules extends Singleton {

	/**
	 * Constructor
	 */
	protected function init() {
		add_filter( 'rewrite_rules_array', [ $this, 'rewrite_rules' ] );
		add_filter( 'query_vars', [ $this, 'query_vars' ] );
	}

	/**
	 * Add query vars.
	 */
	public function query_vars( $vars ) {
		$vars[] = 'mxs_type';
		$vars[] = 'mxs_post_type';

		return $vars;
	}

	/**
	 * Add rewrite rules.
	 */
	public function rewrite_rules( $rules ) {
		$new_rules = [];
		if ( ! empty( $this->options['sitemap_is_enable'] ) ) {
			$new_rules = [
				'^sitemap\.xml$'         => 'index.php?feed=rss&mxs_type=index',
				'^sitemap/([^/]+)\.xml$' => 'index.php?feed=rss&mxs_type=sitemap&mxs_post_type=$matches[1]',
			];
		}
		if ( ! empty( $this->options['news_is_enable'] ) ) {
			$new_rules = array_merge(
				[
					'^sitemap-news\.xml$' => 'index.php?feed=rss&mxs_type=newsindex',
					'^sitemap-news/([^/]+)\.xml$' => 'index.php?feed=rss&mxs_type=news&mxs_post_type=$matches[1]',
				],
				$new_rules
			);
		}
		if ( ! $new_rules ) {
			return $rules;
		}

		return array_merge( $new_rules, $rules );
	}
}
