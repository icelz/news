<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

header("Link: <static/img/news-placeholder.svg>; rel=preload; as=image", false);

$weatherclass = "Weather_" . $SETTINGS['sources']['weather'];
$lat = 46.595;
$lng = -112.027;
$weather = new $weatherclass($lat, $lng);
if (!$weather->setLocationByCookie()) {
    $weather->setLocationByUserIP();
}
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

News::load($SETTINGS["sources"]["news"]);

$newsitems = News::getItems();

// Sort by category
$itemsbycategory = [];
foreach ($newsitems as $item) {
    $itemsbycategory[$item->getCategory()->toString()][] = $item;
}
?>

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-around">
            <div class="mx-4 mb-2 text-center">
                <?php
                $currently = $weather->getCurrently();
                $forecast = $weather->getForecast();
                ?>
                <div class="d-flex flex-wrap justify-content-center">
                    <div class="mx-4 display-4">
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
            <div class="row">
                <?php
                $i = 0;
                foreach ($forecast as $day) {
                    if ($i == 0) {
                        // skip the first one since it's today
                        $i++;
                        continue;
                    }
                    if ($i >= 4) {
                        break;
                    }
                    $i++;
                    ?>
                    <div class="col-12 col-sm-4 text-center">
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
    <div class="card-footer d-flex justify-content-between">
        <div>
            <a class="px-2" href="./action.php?action=settempunits&source=home&unit=F">F</a> |
            <a class="px-2" href="./action.php?action=settempunits&source=home&unit=C">C</a> |
            <a class="px-2" href="./action.php?action=settempunits&source=home&unit=K">K</a>
        </div>

        <div>
            <span class="text-muted">
                <i class="fas fa-map-marker-alt"></i> <?php
                if (!empty($weather->getLocationName())) {
                    echo htmlentities($weather->getLocationName()) . " | ";
                }
                echo round($weather->getLatitude(), 2) . ", " . round($weather->getLongitude(), 2);
                ?>
            </span>
            <span id="geolocate-btn" class="btn btn-link btn-sm ml-2">
                <i class="fas fa-compass"></i> <?php $Strings->get("Geolocate"); ?>
            </span>
        </div>
    </div>
</div>

<p class="lead">
    <i class="fas fa-newspaper fa-fw"></i> <?php $Strings->get("Headlines"); ?>
</p>

<div class="row" id="news-grid">

    <?php
    $count = 0;
    foreach ($itemsbycategory["general"] as $item) {
        if ($count >= 10) {
            break;
        }
        $count++;
        echo $item->generateGridCard(true);
    }
    ?>

    <div class="col-1 sizer-element"></div>

</div>


<script nonce="<?php echo $SECURE_NONCE; ?>">
    var preload_images = <?php
    $srcs = [];
    foreach ($itemsbycategory["general"] as $item) {
        if (strpos($item->getImage(), "preview.redd.it") !== false) {
            $imgurl = $item->getImage();
        } else {
            $imgurl = Thumbnail::getThumbnailCacheURL($item->getImage(), 500);
        }
        $srcs[] = $imgurl;
    }
    echo json_encode($srcs);
    ?>;
</script>