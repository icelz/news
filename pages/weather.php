<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

$weatherclass = "Weather_" . $SETTINGS['sources']['weather'];
$weather = new $weatherclass(46.595, -112.027); // TODO: get user location
$weather->loadForecast();

$tempunits = "C";
$degreesymbol = "&#176;";
if (!empty($_COOKIE['TemperatureUnitsPref']) && preg_match("/[FCK]/", $_COOKIE['TemperatureUnitsPref'])) {
    $tempunits = $_COOKIE['TemperatureUnitsPref'];
    // No degree symbol for Kelvins
    if ($tempunits == "K") {
        $degreesymbol = "";
    }
}
?>
<div class="row">
    <div class="col-12 col-xl-3">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-around">
                    <div class="mr-4 mb-2 text-center">
                        <?php
                        $currently = $weather->getCurrently();
                        $forecast = $weather->getForecast();
                        ?>
                        <div class="d-flex flex-wrap">
                            <div class="mr-4 display-4">
                                <i class="wi wi-fw <?php echo $currently->getIcon(); ?>"></i>
                            </div>
                            <div>
                                <h2><?php echo $currently->summary; ?></h2>
                                <h4><?php $Strings->build("{temp}{units}", ["temp" => round(Weather::convertDegCToUnits($currently->temperature, $tempunits), 1), "units" => " $degreesymbol$tempunits"]); ?></h4>
                            </div>
                        </div>
                        <div>
                            <p class="font-weight-bold"><?php
                                $Strings->build("Low: {tempLow}{units} High: {tempHigh}{units}", [
                                    "tempLow" => round(Weather::convertDegCToUnits($forecast[0]->tempLow, $tempunits), 1),
                                    "tempHigh" => round(Weather::convertDegCToUnits($forecast[0]->tempHigh, $tempunits), 1),
                                    "units" => " $degreesymbol$tempunits"
                                ]);
                                ?></p>
                            <p>
                                <?php
                                echo $forecast[0]->summary;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-9">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <?php
                    $i = 0;
                    foreach ($forecast as $day) {
                        if ($i == 0) {
                            // skip the first one since it's today
                            $i++;
                            continue;
                        }
                        if ($i >= 7) {
                            break;
                        }
                        $i++;
                        ?>
                        <div class="col-12 col-sm-4 col-lg-2 text-center">
                            <div class="h1">
                                <i class="wi wi-fw <?php echo $day->getIcon(); ?>"></i>
                            </div>
                            <div class="h5">
                                <?php echo date("l", $day->time); ?>
                            </div>
                            <p class="font-weight-bold">
                                <?php
                                $Strings->build("{tempLow} to {tempHigh}{units}", [
                                    "tempLow" => round(Weather::convertDegCToUnits($day->tempLow, $tempunits), 1),
                                    "tempHigh" => round(Weather::convertDegCToUnits($day->tempHigh, $tempunits), 1),
                                    "units" => " $degreesymbol$tempunits"
                                ]);
                                ?>
                            </p>
                            <p>
                                <?php echo $day->summary; ?>
                            </p>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="d-flex justify-content-between mt-4">
            <div>
                <a class="px-2" href="./action.php?action=settempunits&source=weather&unit=F">F</a> |
                <a class="px-2" href="./action.php?action=settempunits&source=weather&unit=C">C</a> |
                <a class="px-2" href="./action.php?action=settempunits&source=weather&unit=K">K</a>
            </div>

            <div class="text-muted">
                <i class="fas fa-map-marker-alt"></i> <?php echo round($weather->getLatitude(), 2) . ", " . round($weather->getLongitude(), 2); ?>
            </div>
        </div>
    </div>
</div>