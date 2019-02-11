<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

use GeoIp2\Database\Reader;

abstract class Weather {

    protected $conditions = [];
    protected $currently;
    protected $low;
    protected $high;
    protected $lat = 0.0;
    protected $lng = 0.0;
    protected $locationname = "";

    public function __construct($latitude, $longitude) {
        $this->lat = $latitude;
        $this->lng = $longitude;
    }

    /**
     * Attempt to find and set the user's location based on the client IP address.
     * @global type $SETTINGS
     * @return boolean true if successful, false if not
     */
    public function setLocationByUserIP(): bool {
        global $SETTINGS;
        // Make sure we'll have a valid IP when testing on localhost
        if ($SETTINGS['debug'] && $_SERVER['REMOTE_ADDR'] == "127.0.0.1") {
            // This should geolocate to Helena, Montana, United States
            $_SERVER['REMOTE_ADDR'] = "206.127.90.1";
        }
        try {
            $reader = new Reader($SETTINGS['geoip_db']);

            // Get the user's IP address
            $clientip = $_SERVER['REMOTE_ADDR'];
            // Check if we're behind CloudFlare and adjust accordingly
            if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                $clientip = $_SERVER["HTTP_CF_CONNECTING_IP"];
            }

            $record = $reader->city($clientip);

            $country = $record->country->name;
            $region = $record->mostSpecificSubdivision->name;

            $this->locationname = $record->city->name . ", $region";
            $this->lat = $record->location->latitude;
            $this->lng = $record->location->longitude;
            return true;
        } catch (GeoIp2\Exception\AddressNotFoundException $ex) {
            return false;
        }
    }

    /**
     * Attempt to set the user's location based on sent cookies named "Latitude"
     * and "Longitude".
     * @return bool true if successful.
     */
    public function setLocationByCookie(): bool {
        if (!empty($_COOKIE['Latitude']) && !empty($_COOKIE['Longitude'])) {
            $latlngregex = "/-?[0-9]{1,3}(\.[0-9]+)?/";
            if (preg_match($latlngregex, $_COOKIE['Latitude']) && preg_match($latlngregex, $_COOKIE['Longitude'])) {
                $this->lat = $_COOKIE['Latitude'] * 1.0;
                $this->lng = $_COOKIE['Longitude'] * 1.0;
                return true;
            }
            return false;
        }
        return false;
    }

    abstract protected function loadForecast();

    // Getters

    public function getLatitude(): float {
        return $this->lat;
    }

    public function getLongitude(): float {
        return $this->lng;
    }

    public function getLocationName(): string {
        return $this->locationname;
    }

    public function getForecast(): array {
        return $this->conditions;
    }

    public function getCurrently(): Conditions {
        return $this->currently;
    }

    public function getLow() {
        return $this->low;
    }

    public function getHigh() {
        return $this->high;
    }

    // Setters

    public function setLocationName(string $name) {
        $this->locationname = $name;
    }

    public function setForecast(array $conditions) {
        $this->conditions = $conditions;
    }

    public function setCurrently(Conditions $weather) {
        $this->currently = $weather;
    }

    public function setLow(Conditions $weather) {
        $this->low = $weather;
    }

    public function setHigh(Conditions $weather) {
        $this->high = $weather;
    }

    /**
     * Convert a temperature in Celsuis to the given unit ("F" or "K").
     * @param float $temperature
     * @param string $to
     * @return float
     */
    public static function convertDegCToUnits(float $temperature, string $to): float {
        switch (strtoupper($to)) {
            case "K":
                return $temperature + 273.15;
            case "F":
                return ($temperature * (9.0 / 5.0)) + 32.0;
            default:
                return $temperature;
        }
    }

}
