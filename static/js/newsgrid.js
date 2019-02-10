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

// Show the images using JavaScript, to make sure we don't see double
// when JS is disabled
$("img.newscard-img.d-none").removeClass("d-none");


$("img.newscard-img").on("error", function () {
    $(this).attr("src", "static/img/news-placeholder.svg");
});