//click the button to open the dialog box to submit a note for a specific paper
$('.viewNoteButton').click(function() {
    
    var paperID = $(this).attr('paperID');
    
    var textFor = "#textFor" + paperID;
    var paperText = $(textFor).text();
    
    $("#textAreaFor" + paperID).val(paperText);
    var noteBox = "#makeNoteFor" + paperID;
    $("body").animate({'scrollTop': 0}, 500, function() {
        $(noteBox).slideDown(500);
    });
});

//click to close the dialog box for adding a note to a specific paper
$('.closeNoteButton').click(function() {
    var paperID = $(this).attr('paperID');
    var noteBox = "#makeNoteFor" + paperID;
    var top = $(this).offset();
    $(noteBox).slideUp(300);
});