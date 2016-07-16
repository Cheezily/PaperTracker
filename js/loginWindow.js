$('#login').click(function(event) {
    $('.mainWrapper').animate({'opacity': '.3'}, 500);
    $('#loginWindow').css({'opacity': '1'}).slideDown(600); 
});

$('.loginCancel').click(function() {
    //$('.mainWrapper').animate({'opacity': '1'}, 500);
    $('#loginWindow').fadeOut(600);
});

// These buttons bring up the fields for the user registration
// and populate the top label & hidden input for role type
$('#newUser').click(function(event) {
    $('.mainWrapper').animate({'opacity': '.3'}, 500);
    $('#registrationDialog').css({'opacity': '1'}).slideDown(600); 
});

$('#newAuthorRegister').click(function() {
    $('#roleTitle').text('Registration: New Author');
    $('input[name="role"]').val('author');
    $('.roleSelection').slideUp(200, function() {
        $('#newUserForm').slideDown(200);
        $('.loginForm').animate({'height': "500px"});
    });
});

$('#newReviewerRegister').click(function() {
    $('#roleTitle').text('Registration: New Reviewer');
    $('input[name="role"]').val('reviewer');
    $('.roleSelection').slideUp(200, function() {
        $('#newUserForm').slideDown(200);
        $('.loginForm').animate({'height': "500px"});
    });
});