/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */


function loadPreloadedImage(index) {
    console.log("Loading cached image ", preload_images[index]);
    $("img.newscard-img[data-src=\"" + preload_images[index] + "\"]").attr("src", preload_images[index]);
}

// Load images into cache and replace placeholders
$(document).ready(function () {
    if (typeof preload_images != "undefined") {
        var preloadCache = [];
        for (var i = 0; i < preload_images.length; i++) {
            console.log("Caching thumbnail: " + preload_images[i]);
            img = new Image();
            img.src = preload_images[i];
            img.onload = loadPreloadedImage(i);
            preloadCache.push(img);
        }
    }
});