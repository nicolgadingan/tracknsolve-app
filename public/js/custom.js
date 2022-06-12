$(document).ready(function() {
    $("body").on("click", "#mobile-menu", function () {
        $("#sidebar-menu").toggle("show");
    });

    $(window).resize(function() {
        var screen_width    =   window.screen.width;
        if (screen_width >= 480) {
            $("#sidebar-menu").show();    
        } else {
            $("#sidebar-menu").hide();
        }
    });
});