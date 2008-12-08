<?php

require_once('vtlib/thirdparty/parser/feed/simplepie.inc');

class Vtiger_Feed_Parser extends SimplePie {
	var $vt_cachelocation = 'test/vtlib/feedcache';
	var $vt_fetchdone = false;

	function vt_dofetch($url, $timeout=10) {
		$this->set_timeout($timeout);
		$this->set_feed_url($url);
		$this->enable_order_by_date(false);
		$this->enable_cache(false);
		$this->init();
		$this->vt_fetchdone = true;
	}

	function vt_doparse($content) {
		$this->set_raw_data($content);
		$this->init();
		$this->vt_fetchdone = true;
	}
}
?>
