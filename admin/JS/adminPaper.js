//click the button to open the dialog box to submit a note for a specific paper
$('.adminNoteButton').click(function() {
    
    var paperID = $(this).attr('paperID');
    
    var textFor = "#textFor" + paperID;
    var paperText = $(textFor).text();
    
    var noteBox = "#makeNoteFor" + paperID;
    $("body").animate({'scrollTop': 0}, 500);
    $("#textAreaFor" + paperID).val(paperText);
    $(noteBox).slideDown(500);
});

//click to close the dialog box for adding a note to a specific paper
$('.adminNoteCancel').click(function() {
    var paperID = $(this).attr('paperID');
    var noteBox = "#makeNoteFor" + paperID;
    var top = $(this).offset();
    $(noteBox).slideUp(300, function() {
        $("body").animate({'scrollTop': top.top}, 300);
    });

});