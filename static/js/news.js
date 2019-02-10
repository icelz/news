/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

function fetchVisibleGridImages() {
    $(".grid__brick").each(function () {
        if ($(this).css("opacity") == "1") {
            $("img.newscard-img", this).attr("src", $("img.newscard-img", this).data("src"));
        }
    });
}

$("input[name=newscategory]").on("change", function () {
    window.shuffleInstance.filter($(this).val());
    $(this).button('toggle');
    setTimeout(fetchVisibleGridImages, 500);
});

window.shuffleInstance.filter("general");

$(document).ready(function () {
    fetchVisibleGridImages();
});