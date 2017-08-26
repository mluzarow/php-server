<?php
/**
 * Creates a new Manga object holding meta information regarding the current
 * selected manga archive pointed to by $path.
 *
 * @uses  ReaderController::Manga    Container for all manga meta information.
 * @uses  ReaderController::Volume   Container for volume-level meta information.
 * @uses  ReaderController::Chapter  Container for chapter-level meta information.
 *
 * @param  string  $path  The archive filename. E.G. "Title.manga".
 *
 * @return  Manga  $thisManga  Instance of Manga containing meta information
 *                             regarding the currently selected manga archive
 *                             pointed to by $path.
 */
function generateMangaInfo ($path) {
    if (file_exists ($path)) {
        $manga_title = str_replace ('.manga', '', $path);
        $xml_data = file_get_contents("zip://$path#$manga_title/info.xml");
    } else {
        echo "<span style='display: block; text-align: center; color: red;'>
                Could not find file specified: $path
            </span>";
        exit;
    }

    if (isset ($xml_data)) {
        // Read the XML file in order to organize files.
        $xml = simplexml_load_string ($xml_data);
    } else {
        echo "<span style='display: block; text-align: center; color: red;'>
                Failed to load info.xml for file: $path
            </span>";
        exit;
    }

    $thisManga = new Manga (
        (string) $xml->top->name,
        (string) $xml->top->author,
        (string) $xml->top->year,
        (string) $xml->top->indie == "1" ? true : false,
        (int) $xml->top->originals,
        (int) $xml->top->scanlators,
        true,
        (int) $xml->top->totalVol,
        array ()
    );

    foreach ($xml->content->volume as $volume) {
        $volumeIndex = 0;

        $thisVolume = new Volume (
            (int) $volume->thisVolume,
            (string) $volume->volumeName,
            (string) $volume->name,
            (string) $volume->subtitle,
            array ()
        );

        foreach ($volume->chapter as $chapter) {
            $chapterIndex = 0;

            $thisChapter = new Chapter (
                (int) $chapter->thisChapter,
                (string) $chapter->chapterName,
                (int) $chapter->pageCount,
                (string) $chapter->name,
                (string) $chapter->subtitle
            );

            $thisVolume->chapters [$chapterIndex] = $thisChapter;
            $chapterIndex++;
        }

        $thisManga->volumes [$volumeIndex] = $thisVolume;
        $volumeIndex++;
    }

    return ($thisManga);
}

/**
 * Extracts raw image data from the manga archive and encodes in base 64 for
 * rendering onto page.
 *
 * @param  string  $path  The archive filename. E.G. "Title.manga".
 * @param  int     $vol   Archive's index of the targeted volume.
 * @param  int     $chap  Archive's index of the targeted chapter.
 * @param  int     $page  Archive's index of the targeted page.
 *
 * @return  string  $base  Image data converted to base 64 rendered as a string.
 */
function getImageFromArchive (string $path, $vol, $chap, $page) {
    // Temporary
    $page = 7;

    $manga_title = str_replace ('.manga', '', $path);
    $base = file_get_contents("zip://$path#$manga_title/$vol/$chap/$page/base.png");
    $base = base64_encode ($base);

    return ($base);
}

/**
 * Creates translation objects from lang.dat translation files found in each
 * page's index directory.
 *
 * @uses ReaderController::TranslationBox  Container for translation data and
 *                                         CSS styling.
 *
 * @param  string  $path  The archive filename. E.G. "Title.manga".
 * @param  int     $vol   Archive's index of the targeted volume.
 * @param  int     $chap  Archive's index of the targeted chapter.
 * @param  int     $page  Archive's index of the targeted page.
 * @param  string  $tag   Language tag used for selecting translation content.
 *
 * @return  array  $boxList  Collection of TranslationBox objects containing
 *                           translation and render data for each textbox.
 */
function getTraslationContent ($path, $vol, $chap, $page, $tag) {
    // Temporary
    $page = 7;

    $manga_title = str_replace ('.manga', '', $path);
    $langFile = file_get_contents("zip://$path#$manga_title/$vol/$chap/$page/lang.dat");
    $langArray = explode("\n", $langFile);

    $i = 1;
    $boxList = array ();
    foreach ($langArray as $line) {
        // Hardcoded file header skip. Not really necessary anymore.
        if ($i >= 20) {
            preg_match('/([a-zA-Z][a-zA-Z]) (\d+[.]?\d*?)% (\d+[.]?\d*?)% (\d+[.]?\d*?)vw (\d+[.]?\d*?)vw \"(.*)\" \"(.*)\"/', $line, $matches);

            if (!empty ($matches)) {
                // Tag check
                if ($matches[1] == $tag) {
                    $boxList [] = new TranslationBox (
                        $matches [1],
                        $matches [2],
                        $matches [3],
                        $matches [4],
                        $matches [5],
                        $matches [6],
                        $matches [7]
                    );
                }
            }
        }
        $i++;
    }

    return ($boxList);
}

/**
 * Holds translation data and CSS styling for a single textbox.
 *
 * @usedby ReaderController::getTraslationContent()
 */
class TranslationBox {
    function __construct ($tag, $top, $left, $width, $fontSize, $style, $content) {
        $this->tag = $tag;
        $this->top = $top;
        $this->left = $left;
        $this->width = $width;
        $this->fontSize = $fontSize;
        $this->style = $style;
        $this->content = $content;
    }
}

/**
 * Contains meta information regarding the entirety of a .manga archive. Will be
 * generated based on the information provided in the info.xml file found at the
 * root of the archive. E.G. MangaTitle/info.xml.
 *
 * @usedby ReaderController::generateMangaInfo()
 */
class Manga {
    function __construct ($title=null, $author=null, $year=null, $isIndependent=null, $numOriginals=null, $scanlators=null, $isFinished=null, $numVolumes=null, $volumes=null) {
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->isIndependent = $isIndependent;
        $this->numOriginals = $numOriginals;
        $this->scanlators = $scanlators;
        $this->isFinished = $isFinished;
        $this->numVolumes = $numVolumes;
        $this->volumes = $volumes;
    }
}

/**
 * Contains meta information regarding a single volume of a .manga archive. Will
 * be generated based on the information provided in the info.xml file found at
 * the root of the archive. E.G. MangaTitle/info.xml.
 *
 * @usedby ReaderController::generateMangaInfo()
 */
class Volume {
    function __construct ($n=null, $name=null, $title=null, $subtitle=null, $chapter=null) {
        $this->n = $n;
        $this->name = $name;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->chapters = $chapters;
    }
}

/**
 * Contains meta information regarding a single chapter of a .manga archive. Will
 * be generated based on the information provided in the info.xml file found at
 * the root of the archive. E.G. MangaTitle/info.xml.
 *
 * @usedby ReaderController::generateMangaInfo()
 */
class Chapter {
    function __construct ($n=-1, $name='', $pages=0, $title='', $subtitle='',
                        $hasOriginal=false, $originalN=0, $originalVolume=null,
                        $originalDay='', $originalMonth='', $originalYear='',
                        $scanN=0, $scanDay='', $scanMonth='', $scanYear='') {
        $this->n = $n;
        $this->name = $name;
        $this->pages = $pages;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->hasOriginal = $hasOriginal;
        $this->originalN = $originalN;
        $this->originalVolume = $originalVolume;
        $this->originalDay = $originalDay;
        $this->originalMonth = $originalMonth;
        $this->originalYear = $originalYear;
        $this->scanN = $scanN;
        $this->scanDay = $scanDay;
        $this->scanMonth = $scanMonth;
        $this->scanYear = $scanYear;
    }
}

/**
 * Contains meta information regarding an original source, such as a composite
 * magazine, in which the manga was originaly hosted.
 */
class Original {
    function __construct ($n=null, $title=null) {
        $this->n = $n;
        $this->title = $title;
    }
}

/**
 * Contains meta information regarding a scanlation group.
 */
class Scanlator {
    function __construct ($n=null, $title=null, $website=null) {
        $this->n = $n;
        $this->title = $title;
        $this->website = $website;
    }
}
