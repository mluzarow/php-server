<?php
    require 'ReaderInit.php';
    //require 'ReaderView.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="viewer.css" media="all">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <title><?php echo $reader->mangaInfo->title; ?> Manga</title>
    </head>
    <body>
        <div id = "navigation">
            <div id = "nav_left">
                <select id = "select_volume">
                    <?php //$this->buildDropdownVolume();//ReaderView::generateDropdownVolume (); ?>
                </select>
                <select id = "select_chapter">
                    <?php //ReaderView::generateDropdownChapter (); ?>
                </select>
                <select id = "select_page">
                    <?php //ReaderView::generateDropdownPage (); ?>
                </select>
            </div>
            <div id = "nav_right">
                <div id = "scroller_container">
                    <?php //generateScrollerItems (); ?>
                </div>
            </div>
        </div>
        <div id = "page">
            <?php //ReaderView::getPage (); ?>
        </div>
        <select id = "language">
            <option value="en">English</option>
            <option value="jp">日本語</option>
        </select>
    </body>

    <script type="text/javascript" src="updater.js"></script>
</html>
