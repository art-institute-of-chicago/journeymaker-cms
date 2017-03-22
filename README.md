<img src=https://raw.githubusercontent.com/Art-Institute-of-Chicago/template/master/aic-logo.gif alt="Art Institute of Chicago" width=20% /><img src=journeymaker-logo.png alt="JourneyMaker: Your Journey Begins Here" width=75% style="float: right"/>

# JourneyMaker CMS
> A Drupal 7 site to administer content

[JourneyMaker](http://www.artic.edu/journeymaker/) is an innovative new interactive experience that empowers families to create
their own personalized journeys of the Art Institute of Chicago. Designed as a web application
and developed for multi-touch screens, the interactive is allows your family to create your
very own tour of the museum. Choose one of eight storylines—like Superheroes, Time Travelers, or Strange
and Wild Creatures—and then select works from the museum’s collection within that story.
After you’ve made your selections, print out your personalized Journey Guide, which includes
information, activities, and wayfinding directions. Then head into the museum for an art-filled
adventure!

JourneyMaker launched at the Art Institute of Chicago on July 1, 2016 as six multi-touchscreens in our Ryan 
Learning Center, along with a [desktop version](http://journeymaker.artic.edu/) available online. It's 
still in place today and is being maintained by a team of internal developers.


## Features

* Integration with your Collections API
* Bundles all CMS data to a JSON file for the frontend


## Overview

JourneyMaker consists of two parts: a content authoring system written in Drupal, and a public-facing
frontend written in JavaScript. This repository contains the content authoring system. In order to deploy
your own instance of JourneyMaker, you will also need to install the JourneyMaker Client:

https://github.com/art-institute-of-chicago/journeymaker-client

The JourneyMaker Client does not need to be installed on the same server or under the same domain as the CMS:
as long as the client can access the assets stored in the CMS over HTTP ([CORS](http://stackoverflow.com/questions/20035101/no-access-control-allow-origin-header-is-present-on-the-requested-resource)),
everything should work fine. For cross-domain development, you can use the [Allow-Control-Allow-Origin: \*](https://chrome.google.com/webstore/detail/allow-control-allow-origi/nlfbmbojpeacfghkpbjhddihlkkiljbi)
Chrome extension.

For brevity, throughout the rest of this repo's documentation, "JourneyMaker" refers to the public-facing
frontend client. "JourneyMaker CMS" refers to the content authoring system, i.e. this repository.


## Requirements

You can find information on Drupal 7's requirements [here](https://www.drupal.org/docs/7/system-requirements).
This package has the following requirements:

* Composer - to install Drush to this project
* MySQL 5.7 or greater - to automatically create a database and user if it doesn't already exist


## Installation 

This repo contains only the Drupal `sites` directory--the place where all the customizations and contributions
to a base Drupal install are kept. We've included an `install.sh` script that will download and
install Drupal for you, to make getting the CMS up and running easier. 

Enter these commands from the command line to get started:

```shell
# Clone the repo to your computer
git clone https://github.com/art-institute-of-chicago/journeymaker-cms.git

# Enter the folder that was created by the clone
cd journeymaker-cms

# Install Drush to this project
composer install

# Run the install script
./install.sh
```

The script will download Drupal and extract its contents, then prompt you for database login info.
If the database and user specified doesn't already exist, the script will create them for you. It then 
uses Drush to install Drupal, enable modules, set permissions and some configurations. In the output,
look for the Drupal admin password to log into your Drupal site. After the script completes, point the 
document root of your web server to the `journeymaker-cms` folder and your admin site should be up and 
running. 

There is some addition content required in order for to get your CMS ready for the client. You'll need
to add artworks and tie them to themes. You may see errors until you do this. Please follow the 
[User Documentation](USER-DOCUMENTATION.md) for further instructions.

If you install the JourneyMaker Client, you'll need to provide it the URL to the JSON document produced 
by the CMS. To produce the document, click the "Publish Data" menu option in the CMS. You'll then need to 
wait for Drupal's cron to run, or go to `/admin/reports/status` for the option to run the cron manually.
Then the following URL should be availabe with all your content bundled together:

```
http://your-journeymaker-cms.artic.edu/sites/default/files/json/json.json
```


### Integration with your Collections API

There is some functionality built in to retrieve artwork and gallery metadata from a collections API. 
You can go to `/admin/settings/aic-api` in your CMS to set the URLs for various queries to your API, 
but the parsing of the results is currently hardcoded. Take a look at the functions in 
[`aic_api.module`](sites/all/modules/custom/aic_api/aic_api.module) to make changes to reflect your API.
Here are some examples of what your URLs might look like:

```
# Artwork query by ID URL
http://api.yourmuseum.org/solr/select?fq=document_type:artwork-webcoll&q=object_id:{{pk}}&wt=json

# Gallery query by name URL
http://api.yourmuseum.org/solr/select?fq=document_type:gallery&wt=json&q=title_s:{{name}}

# General artwork query URL
http://api.yourmuseum.org/solr/select?fq=document_type:artwork-webcoll&q={{type}}:{{term}}&wt=json
```


## User Documentation

For information on create and managing content within the CMS, please see the [User Documentation](USER-DOCUMENTATION.md).

## Contributing

We encourage your contributions. Please fork this repository and make your changes in a separate branch. 
We like to use [git-flow](https://github.com/nvie/gitflow) to make this process easier.

```bash
# Clone the repo to your computer
git clone https://github.com/your-github-account/journeymaker-cms.git

# Enter the folder that was created by the clone
cd journeymaker-cms

# Run the install script
./install.sh

# Start a feature branch
git flow start feature yourinitials-good-description-issuenumberifapplicable

# ... make some changes, commit your code

# Push your branch to GitHub
git push origin yourinitials-good-description-issuenumberifapplicable
```

Then on github.com, create a Pull Request to merge your changes into our 
`develop` branch. 

This project is released with a Contributor Code of Conduct. By participating in 
this project you agree to abide by its [terms](CODE_OF_CONDUCT.md).

We also welcome bug reports and questions under GitHub's [Issues](issues).

## Acknowledgments

Design and Development by [Belle & Wissel Co](http://www.bwco.info/).

## Licensing

The code in this project is licensed under the [GNU Affero General Public 
License Version 3](LICENSE).

