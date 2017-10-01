// Set up tracking variables
var currentVolume = 0;
var currentChapter = 0;
var currentPage = 7; // Temp
var currentLang = 'en';
var currentColor = 'n';

$(document).ready (function () {
    createAJAXRequest ({'view' : 'main_title'});
    createAJAXRequest ({'view' : 'select_volume'});
    createAJAXRequest ({'view' : 'select_chapter', 'v' : currentVolume});
    createAJAXRequest ({
        'view' : 'select_page',
        'v' : currentVolume,
        'c' : currentChapter
    });
    createAJAXRequest ({
        'view' : 'image',
        'v' : currentVolume,
        'c' : currentChapter,
        'p' : currentPage,
        'color' : currentColor
    });
    createAJAXRequest ({
        'view' : 'translations',
        'v' : currentVolume,
        'c' : currentChapter,
        'p' : currentPage,
        'tag' : currentLang
    });
});

$('#language').change (function () {
    currentLang = $(this).val ();

    createAJAXRequest ({
        'view' : 'translations',
        'v' : currentVolume,
        'c' : currentChapter,
        'p' : currentPage,
        'tag' : currentLang
    });
});

$('#color').change (function () {
    currentColor = $(this).val ();

    createAJAXRequest ({
        'view' : 'image',
        'v' : currentVolume,
        'c' : currentChapter,
        'p' : currentPage,
        'color' : currentColor
    });
});

$("#arrow_left").click(function () {
    if (currentPage == 0) {
        if (currentChapter == 0) {
            if (currentVolume != 0) {
                currentVolume--;
                //currentChapter = getNumIndexedChapters (currentVolume);
                //currentPage = getNumIndexedPages (currentVolume, currentChapter);
            }
        } else {
            currentChapter--;
            //currentPage = getNumIndexedPages (currentVolume, currentChapter);
        }
    } else {
        currentPage--;
    }

    //refreshImage ('image', currentVolume, currentChapter, currentPage, currentColor);
    //refreshTranslations ('translations', currentVolume, currentChapter, currentPage, currentLang);
});

$("#arrow_right").click(function () {
    //if (currentPage == )
});

function getNumIndexedChapters (v) {

}

function getNumIndexedPages (v, c) {

}

function createAJAXRequest (variables) {
    $.ajax ({
        type: 'POST',
        url: 'ReaderView.php',
        data: variables,
        cache: false,
        success: function (response) {
            $('#' + variables['view']).html (response);
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
