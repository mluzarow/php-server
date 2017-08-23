$('#language').change (function () {
    $("#page").load("ReaderView::getPage ('" + $(this).val () + "'); #page");
});
