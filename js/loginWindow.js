$('#login').click(function(event) {
    $('.mainWrapper').animate({'opacity': '.3'}, 200);
    $('#loginWindow').css({'opacity': '1'}).slideDown(200); 
});

$('.loginCancel').click(function() {
    //$('.mainWrapper').animate({'opacity': '1'}, 500);
    $('#loginWindow').fadeOut(600);
});

// These buttons bring up the fields for the user registration
// and populate the top label & hidden input for role type
$('#newUser').click(function(event) {
    $('.mainWrapper').animate({'opacity': '.3'}, 400);
    $('#registrationDialog').css({'opacity': '1'}).slideDown(400); 
});

$('#newAuthorRegister').click(function() {
    $('#roleTitle').text('Registration: New Author');
    $('input[name="role"]').val('Author');
    $('.roleSelection').slideUp(400, function() {
        $('#newUserForm').show(0);
        $('.loginForm').animate({'height': "600px"}, 400);
    });
});

$('#newReviewerRegister').click(function() {
    $('#roleTitle').text('Registration: New Reviewer');
    $('input[name="role"]').val('Reviewer');
    $('.roleSelection').slideUp(200, function() {
        $('#newUserForm').show(0);
        $('.loginForm').animate({'height': "600px"}, 200);
    });
});