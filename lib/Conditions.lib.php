<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Represents the weather conditions at a specific time and place.
 */
class Conditions {

    /**
     * @var float Latitude of these conditions.
     */
    public $lat = 0.0;

    /**
     * @var float Longitude of these conditions.
     */
    public $lng = 0.0;

    /**
     * @var int UNIX timestamp of when these conditions occur.
     */
    public $time = 0;

    /**
     * @var string Human readable summary of conditions.
     */
    public $summary = "";

    /**
     * @var bool True if daytime, false if nighttime.  Set with setDayOrNight().
     */
    private $daytime = true;

    /**
     * @var float Temperature in degrees Celsius
     */
    public $temperature = 0.0;
    public $tempHigh = 0.0;
    public $tempLow = 0.0;

    /**
     * @var float The "feel like" temperature.
     */
    public $tempFeels = 0.0;

    /**
     * @var float The probability of precipitation, between 0 and 1 inclusive.
     */
    public $precipProbability = 0.0;

    /**
     * @var \PrecipType The precipitation type.
     */
    public $precipType = PrecipType::NONE;

    /**
     * @var float Millimeters per hour of precipitation
     */
    public $precipIntensity = 0.0;

    /**
     * @var float Columnar density of total atmospheric ozone in Dobson units.
     */
    public $ozone = 0.0;

    /**
     * @var float Dewpoint in degrees Celsius.
     */
    public $dewpoint = 0.0;

    /**
     * @var float Percentage of sky covered in clouds, between 0 and 1 inclusive.
     */
    public $cloudCover = 0.0;

    /**
     * @var float Relative humidity, between 0 and 1 inclusive.
     */
    public $humidity = 0.0;

    /**
     * @var float Visibility in kilometers
     */
    public $visibility = 10.0;

    /**
     * @var int The UV index.
     */
    public $uvindex = 0;

    /**
     * @var float Wind speed in km/h
     */
    public $windSpeed = 0.0;

    /**
     * @var float Wind gust speed in km/h
     */
    public $windGust = 0.0;

    /**
     * @var int Wind direction in degrees
     */
    public $windBearing = 0;

    /**
     * Set the value of $daytime based on the $time, $lat, and $lng variables.
     * @return bool True for daytime, False for nighttime.
     */
    public function setDayorNight(): bool {
        $sunrise = date_sunrise($this->time, SUNFUNCS_RET_TIMESTAMP, $this->lat, $this->lng);
        $sunset = date_sunset($this->time, SUNFUNCS_RET_TIMESTAMP, $this->lat, $this->lng);

        $this->daytime = ($this->time >= $sunrise && $this->time < $sunset);

        return $this->daytime;
    }

    /**
     * Check if it's day or night, based on location and time.
     * @return bool true if daytime.
     */
    public function isDay(): bool {
        return $this->setDayorNight();
    }

    /**
     * Check if it's windy (6 or higher on the Beaufort scale).
     *
     * A 6 is described as large branches in motion, whistling heard in power
     * lines, and umbrellas used with difficulty.
     *
     * https://en.wikipedia.org/wiki/Beaufort_scale
     *
     * @return bool true if it's windy.
     */
    public function isWindy(): bool {
        return ($this->windSpeed >= 39);
    }

    /**
     * Check if it's cloudy out (greater than 50% cloud cover).
     * @return bool
     */
    public function isCloudy(): bool {
        return ($this->cloudCover > 0.5);
    }

    /**
     * Check if it's overcast(greater than 80% cloud cover).
     * @return bool
     */
    public function isOvercast(): bool {
        return ($this->cloudCover > 0.8);
    }

    /**
     * Check if it's really hot out (> 32C/90F)
     * @return bool true if it's hotter than my mixtape outside
     */
    public function isHot(): bool {
        return ($this->temperature > 32);
    }

    /**
     * Check if it's really cold out (< -6C/20F)
     * @return bool true if it's cold af
     */
    public function isCold(): bool {
        return ($this->temperature < -6);
    }

    /**
     * Get a suitable icon to show for the weather conditions.
     *
     * https://erikflowers.github.io/weather-icons/
     *
     * @return string
     */
    public function getIcon(): string {
        $downfall = "";

        if ($this->precipProbability > 0.5) {
            switch ($this->precipType) {
                case PrecipType::RAIN:
                    $downfall = "rain";
                    break;
                case PrecipType::SNOW:
                    $downfall = "snow";
                    break;
                case PrecipType::SLEET:
                    $downfall = "sleet";
                    break;
                case PrecipType::HAIL:
                    $downfall = "hail";
                    break;
            }
        }

        // Handle precipitation icons for day/night, windy/not, and overcast
        if ($downfall != "") {
            $wind = $this->isWindy() ? "-wind" : "";
            if ($this->isOvercast()) {
                return "wi-$downfall$wind";
            } else {
                $daynight = $this->isDay() ? "day" : "night-alt";
                return "wi-$daynight-$downfall$wind";
            }
        }

        if ($this->isOvercast()) {
            return "wi-cloudy";
        }

        if ($this->isCloudy()) {
            return $this->isDay() ? "wi-day-cloudy" : "wi-night-alt-cloudy";
        }

        if ($this->isCold()) {
            return "wi-snowflake-cold";
        }

        if ($this->isDay()) {
            if ($this->isHot()) {
                return "wi-hot";
            }
            return "wi-day-sunny";
        }

        // Pick one for variety
        return ["wi-night-clear", "wi-night-clear", "wi-stars"][random_int(0, 2)];
    }

}

class PrecipType {

    const NONE = 1;
    const RAIN = 2;
    const SNOW = 4;
    const SLEET = 8;
    const HAIL = 16;

}
