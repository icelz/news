<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

//$weatherclass = "Weather_" . $SETTINGS['sources']['weather'];
//$weather = new $weatherclass(46.595806, -112.027031); // TODO: get user location
$weather = new Weather_DarkSky(46.595806, -112.027031);
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
        <div class="d-flex">
            <?php
            $currently = $weather->getCurrently();
            ?>
            <div class="mr-4 display-4">
                <i class="wi wi-fw <?php echo $currently->getIcon(); ?>"></i>
            </div>
            <div>
                <h2><?php echo $currently->summary; ?></h2>
                <h4><?php $Strings->build("{temp}{units}", ["temp" => round(Weather::convertDegCToUnits($currently->temperature, $tempunits), 1), "units" => " $degreesymbol$tempunits"]); ?></h4>
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <div>
            <a class="px-2" href="./action.php?action=settempunits&source=home&unit=F">F</a> |
            <a class="px-2" href="./action.php?action=settempunits&source=home&unit=C">C</a> |
            <a class="px-2" href="./action.php?action=settempunits&source=home&unit=K">K</a>
        </div>

        <div class="text-muted">
            <i class="fas fa-map-marker-alt"></i> <?php echo round($weather->getLatitude(), 2) . ", " . round($weather->getLongitude(), 2); ?>
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
        ?>
        <div class="col-12 col-sm-6 col-md-6 col-lg-4 px-1 m-0 grid__brick" data-groups='["<?php echo $item->getCategory()->toString(); ?>"]'>
            <div class="card mb-2">
                <?php if (!empty($item->getImage())) { ?>
                    <a href="<?php echo $item->getURL(); ?>" target="_BLANK">
                        <img src="<?php
                        if (strpos($item->getImage(), "preview.redd.it") !== false) {
                            echo $item->getImage();
                        } else {
                            echo Thumbnail::getThumbnailCacheURL($item->getImage(), 500);
                        }
                        ?>" class="card-img-top" alt="">
                    </a>
                <?php } ?>
                <div class="card-body">
                    <a class="text-dark" href="<?php echo $item->getURL(); ?>" target="_BLANK">
                        <h4 class="card-title">
                            <?php echo htmlentities($item->getHeadline()); ?>
                        </h4>
                    </a>
                    <p class="small"><?php echo $item->getSource(); ?></p>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="col-1 sizer-element"></div>

</div>