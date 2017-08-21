<?php
require 'ReaderController.php';

/**
* Generates HTML dropdown options based on the amount of volumes in the file.for
*/
class ReaderView {
    public static $mangaInfo;
    public static $currentVolume;
    public static $currentChapter;
    public static $currentPage;
    public static $translations;

    public static function generateDropdownVolume () {
        $output = '';

        foreach (ReaderView::$mangaInfo->volumes as $volume) {
            $output .= "<option>$volume->title</option>";
        }

        echo ($output);
    }

    public static function generateDropdownChapter () {
        $output = '';

        foreach (ReaderView::$currentVolume->chapters as $chapter) {
            $output .= "<option>Chapter $chapter->name: $chapter->title</option>";
        }

        echo ($output);
    }

    public static function generateDropdownPage () {
        $output = '';

        for ($i = 1; $i <= ReaderView::$currentChapter->pages; $i++) {
            $output .= "<option>Page: $i</option>";
        }

        echo ($output);
    }

    public static function generateScrollerItems () {
        // echo ('TBD');
    }

    public static function getPage () {
        $imageFile = getImageFromArchive (
            "NEEDLESS.manga",
            ReaderView::$currentVolume->n,
            ReaderView::$currentChapter->n,
            ReaderView::$currentPage
        );

        ReaderView::$translations = getTraslationContent (
            "NEEDLESS.manga",
            ReaderView::$currentVolume->n,
            ReaderView::$currentChapter->n,
            ReaderView::$currentPage
        );

        echo '<div id=\'translation_section\'>' .
                '<?php ReaderView::getPageTranslations (\'en\'); ?>' .
            '</div>';

        echo "<img class=\'page_output\' src='data:image/png;base64,$imageFile' alt='page'>";
    }

    public static function getPageTranslations ($tag) {
        foreach (ReaderView::$translations as $tran) {
            $styling = 'top:' . $tran->top . '%;left:' . $tran->left . '%;width:' . $tran->width . 'vw;font-size:' . $tran->fontSize . 'vw;' . $tran->style;
            echo '<div class=\'generic_trans_box\' style=\'' . $styling . '\'>' . $tran->content . '</div>';
        }
    }
}

// Set up the manga info
ReaderView::$mangaInfo = generateMangaInfo ("NEEDLESS.manga");

// Default volume to the first available volume
ReaderView::$currentVolume = ReaderView::$mangaInfo->volumes [0];
// Default chapter to the first available chapter of currentVolume
ReaderView::$currentChapter = ReaderView::$currentVolume->chapters [0];
// Default page to the first available page of the currentChapter
ReaderView::$currentPage = 1;
