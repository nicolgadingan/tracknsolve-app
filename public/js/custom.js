$(document).ready(function() {
    var screen_width    =   window.screen.width;
    
    // Mobile Screen
    if (screen_width >= 320 &&
        screen_width <= 480) {
        // $("#sidebar-admin").attr("hidden", true);
        // $("#db-ticket-chart canvas").attr("hidden", true);
    } else {
        // $("#sidebar-admin").attr("hidden", false);
        // $("#db-ticket-chart canvas").attr("hidden", false);
    }

    $("body").on("click", "#mobile-menu", function () {
        console.log("Menu Clicked");
        $("#sidebar-menu").toggle("show");
    });
});