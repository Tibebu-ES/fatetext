
//jquery
$(document).ready(function () {
        centerTargetChestInFullTextViewer();
});

//focus - scroll to the target chest/guess sentence in the full text viewer

function centerTargetChestInFullTextViewer() {
        $('.full_text_viewer').animate({
                scrollTop: $("#chestDiv").offset().top - $(".full_text_viewer").offset().top -100
        }, 2000);
}