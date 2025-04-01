
$(document).ready(function() {
    $('.showpassword').click(function() {
        var toggle = $('.password');
        toggle = (toggle.prop('type') == 'password') ? 
        toggle.attr('type','text') : toggle.attr('type','password');
    });
});