
# drupal-packagist

Provides a composer packagist page for Drupal projects, so that every module, theme is installable on composer.

- Only proof-of-concept!
- Currently only the common projects are in, for not bothering updates.drupal.org
- Versions: 6x, 7x, 8x
- http://drugist.espend.de/packages.json

## composer.json
Just add the following lines to you composer.json and your done
```
   "repositories": [
        {
            "type": "composer",
            "url": "http://drugist.espend.de"
        }    	
    ],
```

## Examples
```
composer.phar show drupal/views
name     : drupal/views
descrip. :
keywords :
versions : 8.3-dev, * 7.3.5, 7.3.4, 7.3.3, ...
type     : drupal-module
license  :
source   : []
dist     : [tar] http://ftp.drupal.org/fil ...
names    : drupal/views
```

composer.json with version
```
{
   "repositories": [
        {
            "type": "composer",
            "url": "http://drugist.espend.de"
        }    	
    ],
    "require": {
		"drupal/views": "7.*",
		"drupal/ctools": "7.*",
        "drupal/zen": "7.5.*"
    }
}
```

composer.phar update -v
```
Loading composer repositories with package information
Updating dependencies
  - Installing drupal/zen (7.5.1)
    Loading from cache
    Unpacking archive
    Cleaning up
153 Datei(en) kopiert
100 Datei(en) kopiert
2 Datei(en) kopiert
13 Datei(en) kopiert
31 Datei(en) kopiert

Writing lock file
Generating autoload files
```

## Own Server: Symfony2
Just install a Symfony2; add lines to composer.json and change add routing for this bundle
```
    "repositories": [
        {
            "type": "vcs",
            "url": "git://github.com/Haehnchen/drupal-packagist.git"
        }
    ],

    "require": {
        "composer/composer": "1.0.*@dev",
        "kriswallsmith/buzz":"dev-master",
        "espend/drupal-packagist": "dev-master"
    },
```

run command for generate packages
```
app/console espend:drupal:packagist:generator
```