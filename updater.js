// Set up tracking variables
var currentVolume = 0;
var currentChapter = 0;
var currentPage = 7; // Temp

$(document).ready (function () {
    refreshDropdownView ('select_volume', currentVolume, currentChapter, currentPage);
    refreshDropdownView ('select_chapter', currentVolume, currentChapter, currentPage);
    refreshDropdownView ('select_page', currentVolume, currentChapter, currentPage);
});

function refreshDropdownView (view, v, c, p) {
    $.ajax({
        type: 'POST',
        url: 'ReaderView.php',
        data: {
            'view' : view,
            'v' : v,
            'c' : c,
            'p' : p
        },
        cache: false,
        success: function(response) {
            $('#' + view).html (response);
        },
        error: function(xhr) {
           var response = xhr.responseText;
           console.log(response);
           var statusMessage = xhr.status + ' ' + xhr.statusText;
           var message  = 'Query failed, php script returned this status: ';
           var message = message + statusMessage + ' response: ' + response;
           alert(message);
        }
    });
}

$('#language').change (function () {
    var tag = $(this).val ();
    console.log ("Language changed to " + tag + "!");

    req = new XMLHttpRequest ();

    req.onreadystatechange = function () {
    	if (this.readyState == 4 && this.status == 200) {
    	    console.log (this.responseText);
    	}
    };

    req.open ("GET", "ReaderView.php?q=" + tag, true);
    req.send ();

});
