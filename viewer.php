<?php require 'ReaderView.php'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="viewer.css" media="all">

        <title>Test Page</title>
    </head>
    <body>
        <div id = "navigation">
            <div id = "nav_left">
                <select id = "select_volume">
                    <?php ReaderView::generateDropdownVolume (); ?>
                </select>
                <select id = "select_chapter">
                    <?php ReaderView::generateDropdownChapter (); ?>
                </select>
                <select id = "select_page">
                    <?php ReaderView::generateDropdownPage (); ?>
                </select>
            </div>
            <div id = "nav_right">
                <div id = "scroller_container">
                    <?php //generateScrollerItems (); ?>
                </div>
            </div>
        </div>
        <div id = "page">
            <?php ReaderView::getPage (); ?>
        </div>
    </body>
</html>
