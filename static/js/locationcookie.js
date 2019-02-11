/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */


function geolocateToCookie() {
    navigator.geolocation.getCurrentPosition(function (pos) {
        document.cookie = "Latitude=" + pos.coords.latitude.toFixed(2) + ";samesite=strict;max-age=" + (60 * 60 * 24 * 90);
        document.cookie = "Longitude=" + pos.coords.longitude.toFixed(2) + ";samesite=strict;max-age=" + (60 * 60 * 24 * 90);
        document.location.reload();
    }, function () {
        alert("Could not determine location.  Please note that your coordinates are rounded to approx. 1km before being sent to the server.");
    }, {
        timeout: 1000 * 60,
        enableHighAccuracy: true
    });
}

$("#geolocate-btn").click(geolocateToCookie);