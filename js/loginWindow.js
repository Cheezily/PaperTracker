$('#login').click(function(event) {
    $('.mainWrapper').animate({'opacity': '.3'}, 500);
    $('#loginWindow').css({'opacity': '1'}).slideDown(600); 
});

$('.loginCancel').click(function() {
    //$('.mainWrapper').animate({'opacity': '1'}, 500);
    $('#loginWindow').fadeOut(600);
});
