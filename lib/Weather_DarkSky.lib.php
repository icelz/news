<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

class Weather_DarkSky extends Weather {

    public function loadForecast() {
        global $SETTINGS;
        $apikey = $SETTINGS['apikeys']['darksky.net'];
        $url = "https://api.darksky.net/forecast/$apikey/" . $this->lat . ',' . $this->lng;
        $json = ApiFetcher::get($url, ["exclude" => "minutely,hourly", "units" => "ca", "lang" => $SETTINGS['language']]);
        $resp = json_decode($json);

        $currently = new Conditions();

        $currently->lat = $this->lat;
        $currently->lng = $this->lng;
        $currently->time = $resp->currently->time;

        $currently->summary = $resp->currently->summary;
        $currently->setDayorNight();

        $currently->temperature = $resp->currently->temperature;
        $currently->tempFeels = $resp->currently->apparentTemperature;

        $currently->precipProbability = $resp->currently->precipProbability;

        $currently->ozone = $resp->currently->ozone;
        $currently->dewpoint = $resp->currently->dewPoint;
        $currently->cloudCover = $resp->currently->cloudCover;
        $currently->humidity = $resp->currently->humidity;
        $currently->visibility = $resp->currently->visibility;
        $currently->uvindex = $resp->currently->uvIndex;

        $currently->windSpeed = $resp->currently->windSpeed;
        $currently->windGust = $resp->currently->windGust;
        $currently->windBearing = $resp->currently->windBearing;

        $this->setCurrently($currently);
    }

}