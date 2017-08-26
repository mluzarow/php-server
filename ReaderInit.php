<?php
require 'ReaderController.php';

/**
 * Initialization object which maintains viewer meta data relating to the
 * current manga as well as the position within that manga.
 */
class Reader {
    /**
     * @var  Manga  Instance of Manga containing meta information regarding the
     *              currently selected manga archive.
     */
    public $mangaInfo;

    /**
     * @var  Volume  Instance of Volume container meta information regarding the
     *               volume which the user is currently reading.
     */
    public $currentVolume;

    /**
     * @var  Chapter  Instance of Chapter container meta information regarding
     *                the chapter which the user is currently reading.
     */
    public $currentChapter;

    /**
     * @var  int  Index value of the page the user is currently reading.
     */
    public $currentPage;

    public function __construct () {
        // Source meta data from archive
        $this->mangaInfo = generateMangaInfo ('NEEDLESS.manga');
        // Default volume to the first available volume
        $this->currentVolume = $this->mangaInfo->volumes [0];
        // Default chapter to the first available chapter of currentVolume
        $this->currentChapter = $this->currentVolume->chapters [0];
        // Default page to the first available page of the currentChapter
        $this->currentPage = 1;
    }
}

// Create a new public Reader
$reader = new Reader ();
