<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

abstract class Weather {

    protected $conditions = [];
    protected $currently;
    protected $low;
    protected $high;
    protected $lat = 0.0;
    protected $lng = 0.0;

    public function __construct($latitude, $longitude) {
        $this->lat = $latitude;
        $this->lng = $longitude;
    }

    abstract protected function loadForecast();

    // Getters

    public function getLatitude(): float {
        return $this->lat;
    }

    public function getLongitude(): float {
        return $this->lng;
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
