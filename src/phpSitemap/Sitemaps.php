<?php

namespace sitemaps;

// Include Helper Tools
require_once 'HelperTools.php';


class Sitemaps {

	protected $locationPrefix;

	protected $sitemaps=array();
	
	public function __construct($locationPrefix='') {
		// Set sitemap-location-prefix (URL-Prefix)
		$this->setLocationPrefix($locationPrefix);
	}

	public function setLocationPrefix($locationPrefix='') {
		// Make sure that it trails with a slash
		$this->locationPrefix=trim(rtrim($locationPrefix, '/')).'/';

		return $this;
	}

	public function getLocationPrefix() {
		return $this->locationPrefix;
	}
	
	protected function getHeader() {
		return "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">\n<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
	}

	protected function getFooter() {
		return "</sitemapindex>\n";
	}
	
	public function generate($streamHandler=null) {
		// Resource Handler
		$fh=null;

		// Handler not given, create data-handler
		if (gettype($streamHandler)=='resource') {
			$fh=$streamHandler;
		} else {
			// Open a new string stream handler
			$fh=fopen('data://text/plain,', 'w+');
		}

		// If valid handler
		if ($fh) {
			// Write sitemap-XML header
			fwrite($fh, $this->getHeader());

			// Write added sitemap-files
			foreach ($this->sitemaps as $sitemap) {

				foreach ($sitemap->getLocationURLs() as $locationURL) {
					fwrite($fh, "\t<sitemap>\n");
					fwrite($fh, "\t\t<loc>".HelperTools::LocationValidString($this->getLocationPrefix().$locationURL)."</loc>\n");

					if ($sitemap->getLastModificationTime()) {
						fwrite($fh, "\t\t<lastmod>".$sitemap->getLastModificationTimeString()."</lastmod>\n");
					}

					fwrite($fh, "\t</sitemap>\n");
				} //  End foreach
			} // End foreach

			// Write sitemap-XML footer
			fwrite($fh, $this->getFooter());

			// No stream handler
			if (!$streamHandler) {
				// Rewind stream
				rewind($fh);
				// Get content from stream
				$output=stream_get_contents($fh);
				
				// Close stream
				fclose($fh);

				// Return content
				return $output;
			}

			// Close sitemap-file
			fclose($fh);
		}

		return null;
	}
	
	public function addSitemap(Sitemap $sitemap) {
		$this->sitemaps[]=$sitemap;
	}
	
}
