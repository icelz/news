/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

window.shuffleInstance = new window.Shuffle(document.getElementById('news-grid'), {
    itemSelector: '.grid__brick',
    sizer: '.sizer-element'
});

setInterval(function () {
    window.shuffleInstance.layout();
}, 500);

$("img.newscard-img").on("error", function () {
    if ($(this).data("reloaded")) {
        return;
    }
    var img = $(this);
    setTimeout(function () {
        img.attr("src", $(this).attr("src"));
        img.data("reloaded", true);
    }, 500);
});