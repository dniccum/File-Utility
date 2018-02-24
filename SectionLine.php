<?php
/**
 * Created by PhpStorm.
 * User: dougniccum
 * Date: 2/23/18
 * Time: 1:23 PM
 */

class SectionLine
{
    /**
     * @var string $key
     */
    public $key;

    /**
     * @var string $content
     */
    public $content;

    /**
     * SectionLine constructor.
     * @param string $line
     */
    function __construct($line)
    {
        $cleanLine = $line;
        $lineArray = str_split($cleanLine);
        $key = '';
        $content = '';

        for ($i = 0; $i < count($lineArray); $i++) {
            if (!$this->key) {
                if (!empty($lineArray[$i])) {
                    if ($this->key && $lineArray[$i] == ' ') {
                        $key .= $lineArray[$i];
                    } else {
                        if ($lineArray[$i] != ':') {
                            $key .= $lineArray[$i];
                        } else {
                            $this->key = $key;
                        }
                    }

                    // removes trailing space
                    while(substr($this->key, -1) == ' ') {
                        $this->key = substr($this->key, 0, -1);
                    }
                }
            } else {
                if (empty($content)) {
                    if ($lineArray[$i] != ' ') {
                        $content .= $lineArray[$i];
                    }
                } else {
                    if ($lineArray[$i] == ' ') {
                        if (count($lineArray) != $i + 1) {
                            $content .= $lineArray[$i];
                        }
                    } else {
                        $content .= $lineArray[$i];
                    }
                }
            }
        }

        // removes line breaks
        if (strstr($content, "\n")) {
            $content = substr($content, 0, -1);
        }

        // removes trailing space
        while(substr($content, -1) == ' ') {
            $content = substr($content, 0, -1);
        }

        $this->content = $content;
    }
}