# php-sitemap-classes
_Small and pretty easy to use sitemap-(index)-file generator._

## Classes

### HelperTools

The `HelperTools`-Class provides some library-functionality for e.g. getting the ISO-8601 String of time-number.

```PHP
\sitemaps\HelperTools::getISO8601TimeString(mktime());
```

### Location

The `Location`-Class stores information about a single sitemap-url-item, _location, last modification time, change frequency_ and _priority_.

```PHP
new \sitemaps\Location(
	'/myImportantSite.html', 
	mktime(), 
	\sitemaps\Location::FREQ_ALWAYS,
	\sitemaps\Location::PRIO_FULL
);
```

#### Change Frequencies

Predefined frequencies are available and must be one their values:

* FREQ_ALWAYS
* FREQ_HOURLY
* FREQ_DAILY
* FREQ_WEEKLY
* FREQ_YEARLY
* FREQ_NEVER
* FREQ_DEFAULT => FREQ_ALWAYS

#### Priorities

Predefined priorities are available, but can be every value from 0.0 to 1.0:

* PRIO_NO > 0.0
* PRIO_LOW > 0.25
* PRIO_MEDIUM > 0.5
* PRIO_HIGH > 0.75
* PRIO_FULL > 0.75
* PRIO_DEFAULT > PRIO_MEDIUM

#### Public Methods

* `get-/setLocationURL` - Returns/Sets the location url.
* `getValidLocationURL` - Returns a string-valid location url.
* `get-/setLastModificationTime` - Returns/Sets the last modification time.
* `getLastModificationTimeString` - Returns a ISO-8601 string of the last modification time, uses HelperTools::getISO8601TimeString.
* `get-/setChangeFrequency` - Returns/Sets the change frequency string.
* `get/-setPriority` - Returns/Sets the priority-value.

### Sitemap

The `Sitemap`-Class bundles `Location`-Objects to provide a single sitemap-file.

#### Public Methods

* `get-/setLocationURL` - Returns/Sets the location url for the sitemap-file.
* `get-/setLastModificationTime` - Returns/Sets the last modification time.
* `get-/setMaxLocationsPerFile` - Returns/Sets the maximum number per sitemap-file.
* `clear` - Clears the sitemap. Removes all addes `Location`s.
* `splitToNewFile` - Splits the currently sitemap-file in to a new file.
* `addLocation` - Add a `Location`-Object to the sitemap.
* `getLocationURLs` - Returns an array with all currently added/might generated sitemap-files.
* `generate` - Generates the sitemap-content.

### Sitemaps

The `Sitemaps`-Class bundles `Sitemap`-Objects to provide a sitemap-index-file. Useful for multiple, separated sitemap-files.

```PHP
$index=new \sitemaps\Sitemaps('http://www.example.com/');
$index->addSitemap(...);
```

#### Public Methods

* `get/-setLocationPrefix` - Returns/Sets the location prefix-string, so that every sitemap-file get a string prefixed.
* `generate` - Generates the sitemap-index-content.
* `addSitemap` - Adds a `Sitemap`-Object to the bundle

## Example

```PHP
include 'sitemaps.inc.php'

// Sitemap-Index-File
$index=new \sitemap\Sitemaps('http://www.example.com/');

// Single Sitemap-File
$sitemap=new \sitemap\Sitemap();

// Add website-url
$sitemap->addLocation(new \sitemaps\Location('/myImportantSite.html', mktime(), \sitemaps\Location::FREQ_ALWAYS, \sitemaps\Location::PRIO_FULL));

// Add sitemap to index
$index->addSitemap($sitemap);

// Write index-file
$index->generate(fopen('/tmp/sitemaps.xml', 'w'));

// Write sitemap-file
$sitemap->generate(fopen('/tmp/sitemap.xml', 'w'));
```


