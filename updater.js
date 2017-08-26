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
