// Set up tracking variables
var currentVolume = 0;
var currentChapter = 0;
var currentPage = 7; // Temp

$(document).ready (function () {
    renderTitleView ('main_title');
    refreshDropdownView ('select_volume', currentVolume, currentChapter, currentPage);
    refreshDropdownView ('select_chapter', currentVolume, currentChapter, currentPage);
    refreshDropdownView ('select_page', currentVolume, currentChapter, currentPage);
    refreshImage ('image', currentVolume, currentChapter, currentPage, 'n');
    refreshTranslations ('translations', currentVolume, currentChapter, currentPage, 'en');
});

$('#language').change (function () {
    var tag = $(this).val ();

    refreshTranslations ('translations', currentVolume, currentChapter, currentPage, tag);
});

$('#color').change (function () {
    var color = $(this).val ();

    refreshImage ('image', currentVolume, currentChapter, currentPage, color);
});

function refreshTranslations (view, v, c, p, tag) {
    $.ajax({
        type: 'POST',
        url: 'ReaderView.php',
        data: {
            'view' : view,
            'v' : v,
            'c' : c,
            'p' : p,
            'tag' : tag
        },
        cache: false,
        success: function(response) {
            $('#translations').html (response);
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

function refreshImage (view, v, c, p, color) {
    $.ajax({
        type: 'POST',
        url: 'ReaderView.php',
        data: {
            'view' : view,
            'v' : v,
            'c' : c,
            'p' : p,
            'color' : color
        },
        cache: false,
        success: function(response) {
            $('#image').html (response);
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

function renderTitleView (view) {
    $.ajax({
        type: 'POST',
        url: 'ReaderView.php',
        data: {
            'view' : view
        },
        cache: false,
        success: function(response) {
            $('#main_title').html (response);
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
