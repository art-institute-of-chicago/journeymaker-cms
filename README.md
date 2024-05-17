<img src=https://raw.githubusercontent.com/Art-Institute-of-Chicago/template/master/aic-logo.gif alt="Art Institute of Chicago" width=20% /><img src=journeymaker-logo.png alt="JourneyMaker: Your Journey Begins Here" width=75% style="float: right"/>

# JourneyMaker CMS
> A Twill CMS site to administer content

[JourneyMaker](http://journeymaker.artic.edu/) is an innovative interactive experience that empowers families to create their own personalized journeys of the Art Institute of Chicago. Designed as a web application and developed for multi-touch screens, the interactive tool allows your family to create your very own tour of the museum. Choose one of eight storylines—like Superheroes, Time Travelers, or Strange and Wild Creatures—and then select works from the museum’s collection within that story. After you’ve made your selections, print out your personalized Journey Guide, which includes information, activities, and wayfinding directions. Then head into the museum for an art-filled adventure!

JourneyMaker launched at the Art Institute of Chicago on July 1, 2016 as six multi-touch screens in our Ryan Learning Center, along with a [desktop version](http://journeymaker.artic.edu/) available online. It's still in place today and is being maintained by a team of internal developers.

## Features

* Integration with your Public API
* Bundles all CMS data to a JSON file for the frontend

## Overview

JourneyMaker consists of two parts: a content authoring system written in PHP utilizing Laravel and Twill CMS, and a public-facing frontend written in JavaScript. This repository contains the content authoring system. In order to deploy your own instance of JourneyMaker, you will also need to install the JourneyMaker Client:

https://github.com/art-institute-of-chicago/journeymaker-client

The JourneyMaker Client does not need to be installed on the same server or under the same domain as the CMS: as long as the client can access the assets stored in the CMS over HTTP ([CORS](http://stackoverflow.com/questions/20035101/no-access-control-allow-origin-header-is-present-on-the-requested-resource)), everything should work fine. For cross-domain development, you can use an Allow CORS browser extension.

For brevity, throughout the rest of this repo's documentation, "JourneyMaker" refers to the public-facing frontend client. "JourneyMaker CMS" refers to the content authoring system, i.e. this repository.

## Requirements

You can find information on Laravel 10's requirements [here](https://laravel.com/docs/10.x/installation). This package has the following requirements:

* Composer
* PHP ^8.1
* Node 20
* MySQL 8.0+

In addition to Laravel PHP extension requirements you will also need either the [GD](http://php.net/manual/en/book.image.php) or [Imagick](http://php.net/manual/en/book.imagick.php) PHP extension to support the [Glide](https://glide.thephpleague.com/) Image Rendering Service.

## Installation

The easiest way to get started locally is using [Laravel Herd](https://herd.laravel.com/).

There is some content entry required in order to get your CMS ready for the client. You'll need to add artworks and tie them to themes. Please follow the [User Documentation](USER-DOCUMENTATION.md) for further instructions.

If you install the JourneyMaker Client, you'll need to provide it the URL to the JSON document produced by the CMS. The following URL should be available with all your content bundled together:

```
http://your-journeymaker-cms.test/json/data.json
```

### Integration with your Collections API

There is some functionality built in to retrieve artwork and gallery metadata from an API. We've built scaffolding that will allow you to use Eloquent-like models that are backed by your API. You can configure the URI of your API in [`config/api.php`](config/api.php), and to dive deeper take a look at the `getArtworkApiData()` and `getGalleryApiData()` methods in the [Artwork model](app/Models/Artwork.php) and the `queryArtwork()` method on the [ArtworkController](app/Http/Controllers/Twill/ArtworkController.php).

### Caching

We rely extensively on caching to improve CMS response times and reduce the number of requests required to build the JSON output files.

The Artwork model provides a static method (`cacheArtworkApiData`) to query the API and cache API results used to populate Artwork attributes. The AIC API limits the number of IDs you can request to 100. The Artwork models are chunked into groups of 100 and the results of each Artwork model are cached individually using the API route to that specific Artwork (eg. `/api/v1/artworks/123`).

#### Hydrating Artwork with API Data

When displaying an Artwork model in the CMS or generating the JSON output we rely on data from the API for some Artwork attributes.  The Artwork model contains a `__get` method, when a requested attribute is defined as coming from the API we return the API's value. First, we check the cache for the Artwork and return the hit if one is found. When no API result is cached for the Artwork we query the API, cache the results, then return the data.

The `CacheJsonCommand` is scheduled to run every 5 minutes. This command starts by running `Artwork::cacheArtworkApiData`. The API data for each Artwork model is cached for 5 minutes. The CMS should never have to get API data on the fly. If you experience any performance issues double check the cron and `CacheJsonCommand` is running correctly.

## User Documentation

For information on creating and managing content within the CMS, please see the [User Documentation](USER-DOCUMENTATION.md).

## Contributing

We encourage your contributions. Please fork this repository and make your changes in a separate branch.

```bash
# Clone the repo to your computer
git clone https://github.com/your-github-account/journeymaker-cms.git

# Enter the folder that was created by the clone
cd journeymaker-cms

# Start a feature branch
git checkout -b feature/yourinitials-good-description-issuenumberifapplicable

# ... make some changes, commit your code

# Push your branch to GitHub
git push origin feature/yourinitials-good-description-issuenumberifapplicable
```

Then on github.com, create a Pull Request to merge your changes into our `main` branch.

This project is released with a Contributor Code of Conduct. By participating in this project you agree to abide by its [terms](CODE_OF_CONDUCT.md).

We also welcome bug reports and questions under GitHub's [Issues](issues).

## Development

When developing custom Vue.js components for Twill, we need to take control of the build step with the following commands:

`twill:build` -> Build Twill assets with custom Vue components/blocks
`twill:dev` -> Hot reload Twill assets with custom Vue components/blocks

The site also uses TailwindCSS for styling components.

Styles can be updated by running:

```bash
# Called directly:
npx tailwindcss -i ./resources/assets/css/app.css -o ./public/assets/twill/css/custom.css --watch

# Or using the NPM alias
npm run tailwind
```

### Code Style

This project uses [Laravel Pint](https://laravel.com/docs/pint) to maintain code PHP code style.

```bash
# Called directly
./vendor/bin/pint

# Or using composer alias
composer fix
```

Vue components, Blade files, JavaScript files, and CSS is formatted using Prettier:
```bash
# Called directly
npx prettier --write resources/

# Or using the NPM alias
npm run format
```

## Testing

Tests can be run using PHPUnit:

```bash
# Called directly
./vendor/bin/phpunit

# Or using composer alias
composer test
```

Tests use an in-memory SQLite database.

## Acknowledgments

Development by [Tighten Co](https://tighten.com/).

## Licensing

The code in this project is licensed under the [GNU Affero General Public
License Version 3](LICENSE).
