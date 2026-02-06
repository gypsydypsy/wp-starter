jQuery(document).ready(function($) {
    $('#hsp_accordion').accordion({
        header: "h3",
        heightStyle: "content"
    });

    $('#hsp_dashboard_widget').find('h2').append('<a href="index.php?hsp_scan_flexible=r3Sc4n" style="float: left;">Refresh</a>');
});