<?php

namespace sitemaps;


final class HelperTools {
	// ISO definition for datetime-string
	const ISO8601='Y-m-d\TH:i:sP';

	// HelperTools should not be avaible for anything
	protected function __construct() {}
	protected function __clone() {}

	static public function getISO8601TimeString($time) {
		return date(self::ISO8601, $time);
	}

	static public function LocationValidString($string) {
		$string=str_replace(array("&", "\'", "\"", ">", "<"), array("&amp;", "&apos;", "&quot;", "&gt;", "&lt;"), $string);
		
		return $string;			
	}
}
