<?php

namespace sitemaps;

// Include Helper-Tools
require_once 'HelperTools.php';


class Location {

	/* Frequencies */
	const FREQ_ALWAYS = 'always';
	const FREQ_HOURLY = 'hourly';
	const FREQ_DAILY = 'daily';
	const FREQ_WEEKLY = 'weekly';
	const FREQ_YEARLY = 'yearly';
	const FREQ_NEVER = 'never';
	const FREQ_DEFAULT = self::FREQ_ALWAYS;

	/* Priorities */
	const PRIO_NO = 0.0;
	const PRIO_LOW = 0.25;
	const PRIO_MEDIUM = 0.5;
	const PRIO_HIGH = 0.75;
	const PRIO_FULL = 0.75;
	const PRIO_DEFAULT = self::PRIO_MEDIUM;

	/*  Vars */

	protected $locationURL;

	protected $lastModificationTime;

	protected $changeFreqency;

	protected $priority;

	public function __construct($locationURL=null, $lastModificationTime=null, $changeFreqency=null, $priority=null) {
		// Set location-URL
		$this->setLocationURL($locationURL);

		// Set last modification time
		$this->setLastModificationTime($lastModificationTime);

		// Set change frequency
		$this->setChangeFrequency($changeFreqency);

		// Set priority
		$this->setPriority($priority);
	}

	public function getLocationURL() {
		return $this->locationURL;
	}

	public function getValidLocationURL() {
		return HelperTools::LocationValidString($this->getLocationURL());
	}

	public function setLocationURL($locationURL) {
		$this->locationURL=$locationURL;

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

	public function getChangeFrequency() {
		return $this->changeFreqency;
	}

	public function setChangeFrequency($changeFreqency) {
		$this->changeFreqency=$changeFreqency;

		return $this;
	}

	public function getPriority() {
		return $this->priority;
	}

	public function setPriority($priority) {
		$this->priority=$priority;

		return $this;
	}
}

