TodayStream
===========

TodayStream is a news/headlines reader and weather app.  It is designed in a
modular fashion, to make adding data sources easy.

TodayStream ships with support for NewsAPI.org, Reddit, and DarkSky APIs.
With the exception of Reddit, you'll need to obtain and set API keys in
`settings.php` for TodayStream to be useful.

It currently determines the user's location for weather information based on IP
address.  You'll need to download the (free) MaxMind city-level geoIP database
and keep it up-to-date.  Other methods of obtaining location, such as with
JavaScript APIs and user-configurable settings, are coming soon.

This application does not and will not store user location on the server.

API responses are cached in the database for a reasonable amount of time, to
decrease latency and reduce (or eliminate) API costs.  News item thumbnails are
resized and stored in `cache/thumb`.



Setup Tips
----------

* Run composer install (or composer.phar install) to install dependency libraries
* If you don't have any color in the navbar, run `git submodule init` and `git submodule update`.


-----


Required attribution: This product includes GeoLite2 data created by
MaxMind, available from http://www.maxmind.com