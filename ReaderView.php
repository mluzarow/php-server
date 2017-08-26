<?php
require 'ReaderController.php';

// Copy var from controller
ReaderView::$mangaInfo = $mangaInfo;

if (isset($_POST ['view'])) {
    switch ($_POST ['view']) {
        case ('image'):
            ReaderView::buildImage ($_POST ['v'], $_POST ['c'], $_POST ['p']);
            break;
        case ('translations'):
            ReaderView::buildTranslations ($_POST ['v'], $_POST ['c'], $_POST ['p'], $_POST ['tag']);
            break;
        case ('select_volume'):
            ReaderView::buildDropdownVolume ($_POST ['v']);
            break;
        case ('select_chapter'):
            ReaderView::buildDropdownChapter ($_POST ['v'], $_POST ['c']);
            break;
        case ('select_page'):
            ReaderView::buildDropdownPage ($_POST ['v'], $_POST ['c'], $_POST ['p']);
            break;
        case ('main_title'):
            ReaderView::buildTitle ();
            break;
    }
}

/**
* Generates HTML dropdown options based on the amount of volumes in the file.for
*/
class ReaderView {
    public static $mangaInfo;

    public static function buildTitle () {
        echo ReaderView::$mangaInfo->title . 'Manga';
    }

    public static function buildDropdownVolume ($v) {
        $output = '';

        foreach (ReaderView::$mangaInfo->volumes as $volume) {
            $output .= "<option>$volume->title</option>";
        }

        echo $output;
    }

    public static function buildDropdownChapter ($v, $c) {
        $output = '';

        foreach (ReaderView::$mangaInfo->volumes [$v]->chapters as $chapter) {
            $output .= "<option>Chapter $chapter->name: $chapter->title</option>";
        }

        echo ($output);
    }

    public static function buildDropdownPage ($v, $c, $p) {
        $output = '';

        for ($i = 1; $i <= ReaderView::$mangaInfo->volumes [$v]->chapters [$c]->pages; $i++) {
            $output .= "<option>Page: $i</option>";
        }

        echo ($output);
    }

    public static function buildImage ($v, $c, $p) {
        $imageFile = getImageFromArchive (
            "NEEDLESS.manga",
            ReaderView::$mangaInfo->volumes [$v]->n,
            ReaderView::$mangaInfo->volumes [$v]->chapters [$c]->n,
            $p
        );

        echo "<img src='data:image/png;base64,$imageFile' alt='page'>";
    }

    public static function buildTranslations ($v, $c, $p, $tag) {
        $translations = getTraslationContent (
            "NEEDLESS.manga",
            ReaderView::$mangaInfo->volumes [$v]->n,
            ReaderView::$mangaInfo->volumes [$v]->chapters [$c]->n,
            $p,
            $tag
        );

        $output = '';

        foreach ($translations as $tran) {
            $styling = 'top:' . $tran->top . '%;left:' . $tran->left . '%;width:' . $tran->width . 'vw;font-size:' . $tran->fontSize . 'vw;' . $tran->style;
            $output .= '<div class=\'generic_trans_box\' style=\'' . $styling . '\'>' . $tran->content . '</div>';
        }

        echo $output;
    }
}
