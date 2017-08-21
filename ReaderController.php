<?php
function generateMangaInfo ($path) {
    if (file_exists ($path)) {
        $xml_data = file_get_contents('zip://' . $path . '#' . str_replace ('.manga', '', $path) . '/info.xml');
    } else {
        echo "<span style='display: block; text-align: center; color: red;'>Could not find file specified: $path</span>";
        exit;
    }

    if (isset ($xml_data)) {
        // Read the XML file in order to organize files.
        $xml = simplexml_load_string ($xml_data);
    } else {
        echo "<span style='display: block; text-align: center; color: red;'>Failed to load info.xml for file: $path</span>";
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

function getImageFromArchive ($path, $vol, $chap, $page) {
    $base = file_get_contents('zip://' . $path . '#' . str_replace ('.manga', '', $path) . '/' . $vol . '/' . $chap . '/' . 7 . '/' . 'base.png');
    $base = base64_encode ($base);

    return ($base);
}

function getTraslationContent ($path, $vol, $chap, $page) {
    $langFile = file_get_contents('zip://' . $path . '#' . str_replace ('.manga', '', $path) . '/' . $vol . '/' . $chap . '/' . 7 . '/' . 'lang.dat');
    $langArray = explode("\n", $langFile);

    $i = 1;
    $boxList = array ();
    foreach ($langArray as $line) {
        if ($i >= 20) {
            preg_match('/([a-zA-Z][a-zA-Z]) (\d+[.]?\d*?)% (\d+[.]?\d*?)% (\d+[.]?\d*?)vw (\d+[.]?\d*?)vw \"(.*)\" \"(.*)\"/', $line, $matches);

            if (!empty ($matches)) {
                $boxList [] = new TranslationBox ($matches [1], $matches [2], $matches [3], $matches [4], $matches [5], $matches [6], $matches [7]);
            }
        }
        $i++;
    }

    return ($boxList);
}

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

class Original {
    function __construct ($n=null, $title=null) {
        $this->n = $n;
        $this->title = $title;
    }
}

class Scanlator {
    function __construct ($n=null, $title=null, $website=null) {
        $this->n = $n;
        $this->title = $title;
        $this->website = $website;
    }
}

class Volume {
    function __construct ($n=null, $name=null, $title=null, $subtitle=null, $chapter=null) {
        $this->n = $n;
        $this->name = $name;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->chapters = $chapters;
    }
}

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
