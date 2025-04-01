$(document).ready(function() {
    $("#darkMode").click(function() {
        if ($("body").css("background-color") === "rgb(52, 58, 64)") { 
            $("body").css("background-color", "white");
        } else {
            $("body").css("background-color", "#343a40"); 
        }
    });
});