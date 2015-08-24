<?php

require_once 'src/phpSitemap/sitemaps.inc.php';

/**
 * Copyright 2014 Michael van Vliet (Leiden University), Thomas Hankemeier 
 * (Leiden University)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *          http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/
	
	header ("Content-Type:text/xml");


    $baseUrl = 'http://'.$_SERVER['HTTP_HOST'];

	$sitemap=new \sitemaps\Sitemap($baseUrl, time());

	$sitemap->addLocation(new \sitemaps\Location($baseUrl, time(), \sitemaps\Location::FREQ_DAILY, \sitemaps\Location::PRIO_FULL));
	$sitemap->addLocation(new \sitemaps\Location($baseUrl . '/site/#/search', time(), \sitemaps\Location::FREQ_DAILY, \sitemaps\Location::PRIO_FULL));			
	$sitemap->addLocation(new \sitemaps\Location($baseUrl . '/site/#/about', time(), \sitemaps\Location::FREQ_DAILY, \sitemaps\Location::PRIO_FULL));

	foreach ($providers as $pIdx => $p){
		$sitemap->addLocation(new \sitemaps\Location($baseUrl . '/site/#/search/' . $p['shortname'], time(), \sitemaps\Location::FREQ_DAILY, \sitemaps\Location::PRIO_HIGH));
	}

	foreach ($datasets as $dIdx => $d){
		$sitemap->addLocation(new \sitemaps\Location($baseUrl . '/site/#/dataset/' . $d['provider'] . '/' . $d['accession'], time(), \sitemaps\Location::FREQ_DAILY, \sitemaps\Location::PRIO_MEDIUM));
	}			

	// Write sitemap-content into file
	$xmlArray = $sitemap->generate();
	echo $xmlArray[$baseUrl];
?>