<?php
class DropdownVolumeView {
    private $volumes;

    public function __construct ($volumes) {
        $this->$volumes = $volumes;
    }

    public function getOutput () {
        $output = '';

        foreach ($this->volumes as $volume) {
            $output .= "<option>$volume->title</option>";
        }

        return ($output);
    }
}
