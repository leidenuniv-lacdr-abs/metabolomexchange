<?php

// Include sitemap-files
require('sitemaps.inc.php');

// -----

// Create a new single sitemap
$sitemap=new \sitemaps\Sitemap('/sitemap.xml', mktime());

$sitemap->addLocation(new \sitemaps\Location('http://www.example.com/site1.html', mktime(), 'hourly', 0.75));
$sitemap->addLocation(new \sitemaps\Location('http://www.example.com/site2.html'));

// Separate into new sitemap-file
$sitemap->splitToNewFile();
$sitemap->addLocation(new \sitemaps\Location('http://www.example.com/site3.html'));

// Separate into new sitemap-file
$sitemap->splitToNewFile();
$sitemap->addLocation(new \sitemaps\Location('http://www.example.com/site4.html'));
$sitemap->addLocation(new \sitemaps\Location('http://www.example.com/site5.html'));

// Output sitemap-content
print_r($sitemap->generate());

// Write sitemap-content into file
$sitemap->generate(fopen('/tmp/sitemap-example.xml', 'w'));

// -----

// Create a sitemap-index
$sitemapIndex=new \sitemaps\Sitemaps('http://www.example.com');

// Add single sitemap to index
$sitemapIndex->addSitemap($sitemap);

// Output sitemap-index content
print_r($sitemapIndex->generate());

// Write sitemap-index into file
$sitemapIndex->generate(fopen('/tmp/sitemap-index.xml', 'w'));
