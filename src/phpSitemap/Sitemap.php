<?php

namespace sitemaps;

// Include Helper-Tools
require_once 'HelperTools.php';


class Sitemap {

	protected $locationURL;

	protected $lastModificationTime;

	protected $maxLocationsPerFile=50000;

	protected $locations=array(array());

	public function __construct($locationURL='/sitemap.xml', $lastModificationTime=null) {
		// Set location URL
		$this->setLocationURL($locationURL);

		// Set last modification time
		$this->setLastModificationTime($lastModificationTime);
	}

	public function getLocationURL() {
		return $this->locationURL;
	}

	public function setLocationURL($locationURL) {
		$this->locationURL=trim(ltrim($locationURL, '/'));

		return $this;
	}

	public function getLastModificationTime() {
		return $this->lastModificationTime;
	}

	public function getLastModificationTimeString() {
		return HelperTools::getISO8601TimeString($this->getLastModificationTime());
	}

	public function setLastModificationTime($lastModificationTime) {
		$this->lastModificationTime=$lastModificationTime;

		return $this;
	}

	public function getMaxLocationsPerFile() {
		return $this->maxLocationsPerFile;
	}

	public function setMaxLocationsPerFile($max) {
		$this->maxLocationsPerFile=$max;

		return $this;
	}

	public function clear() {
		$this->locations=array();

		return $this;
	}

	public function splitToNewFile() {
		$this->locations[]=array();
	}

	public function addLocation(Location $location) {
		// Get count of added location URLs
		$countLocations=count($this->locations[count($this->locations)-1]);

		// If maximum number of location URLs per file, split to next file
		if ($countLocations>=$this->getMaxLocationsPerFile()) {
			$this->splitToNewFile();
		}

		// Add given location to current sitemap file
		$this->locations[count($this->locations)-1][]=$location;

		return $this;
	}

	public function getLocationURLs() {
		// If it's just one sitemap file, return the given url
		if (count($this->locations)==1) {
			return array($this->getLocationURL());
		// else ... more than one sitemap file
		} else {
			$locationsURLs=array();

			// Walk through all sitemap files, change the name
			for ($filesCounter=1; $filesCounter<=count($this->locations); $filesCounter++) {
				$locationsURLs[]=preg_replace('/^(.*)(\..*)$/', '$1.'.$filesCounter.'$2', $this->getLocationURL());
			} // End for

			return $locationsURLs;
		}
	}

	protected function getHeader() {
		return "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
	}

	protected function getItem($loc=null, $lastmod=null, $changefreq=null, $priority=null) {
		$r="\t<url>\n";

		// If location/URL
		if ($loc) {
			$r.="\t\t<loc>".$loc."</loc>\n";
		}

		// If last modification time
		if ($lastmod) {
			$r.="\t\t<lastmod>".$lastmod."</lastmod>\n";
		}

		// If change frequency
		if ($changefreq) {
			$r.="\t\t<changefreq>".$changefreq."</changefreq>\n";
		}

		// If priority
		if ($priority) {
			$r.="\t\t<priority>".$priority."</priority>\n";
		}

		$r.="\t</url>\n";

		return $r;
	}

	protected function getFooter() {
		return "</urlset>\n";
	}

	public function generate($streamHandler=null) {

		// Resource given for writing into it
		if (gettype($streamHandler)=='resource') {
			// Just write one header, multiple sitemap files cannot be written in to one handler-resource
			fwrite($streamHandler, $this->getHeader());
		} else {
			$rtn=array();
		}
		
		// Walk through all sitemap files
		foreach ($this->getLocationURLs() as $locationURLKey => $locationURLName) {

			// Use given streamhandler for writing the item
			if (gettype($streamHandler)=='resource') {
				$writeHandler=$streamHandler;
			// Use own handler for writing
			} else {
				// Open a new string-stream-handler
				$rtn[$locationURLName]=fopen('data://text/plain,', 'w+');
				$writeHandler=$rtn[$locationURLName];

				// Write header to handler
				fwrite($writeHandler, $this->getHeader());
			}

			// Write sitemap-URLs/-items to file handler
			foreach ($this->locations[$locationURLKey] as $location) {	
				// Write location item
				fwrite($writeHandler, $this->getItem(
					// Location
					$location->getValidLocationURL(),
					// Modification time string
					($location->getLastModificationTime()?$location->getLastModificationTimeString():null),
					// Change Freqency
					$location->getChangeFrequency(),
					// Priority
					$location->getPriority()
				));
			} // End foreach

			// Own file handler, rearrange written content
			if (gettype($streamHandler)!='resource') {
				// Write footer to handler
				fwrite($writeHandler, $this->getFooter());

				// Rewind
				rewind($writeHandler);
				// Get content
				$content=stream_get_contents($writeHandler);
				// Close write handler
				fclose($rtn[$locationURLName]);
				// Transfer content
				$rtn[$locationURLName]=$content;
				// Free content-var
				unset($content);
			}
		} // End foreach

		// Resource given for writing into it
		if (gettype($streamHandler)=='resource') {
			// Write just one footer
			fwrite($streamHandler, $this->getFooter());
		} else {
			return $rtn;
		}

		return null;
	}
	
}
