<?php

require_once('vtlib/thirdparty/parser/rss/simplepie.inc');

class Vtiger_RSS_Parser {
	var $_cachelocation = 'test/vtlib/rsscache';
	var $feedparser;
	var $fetchdone = false;

	function __construct() {
		$this->feedparser = new SimplePie();
	}

	function fetch($url, $timeout=10) {
		$this->feedparser->set_timeout($timeout);
		$this->feedparser->set_feed_url($url);
		$this->feedparser->enable_order_by_date(false);
		$this->feedparser->enable_cache(false);
		$this->feedparser->init();
		$this->fetchdone = true;
	}
	function items() {
		if(!$this->fetchdone) return Array();
		return $this->feedparser->get_items();
	}
}
?>
