<?php
/**
 * Created by PhpStorm.
 * User: dougniccum
 * Date: 2/23/18
 * Time: 12:50 PM
 */

include ('SectionLine.php');

class Section
{
    /**
     * @var string $sectionName
     */
    public $sectionName;

    /**
     * @var SectionLine[] $sectionLines
     */
    public $sectionLines;

    /**
     * Section constructor.
     */
    function __construct()
    {
        $this->sectionLines = [];
    }

    /**
     * Sets the section name
     *
     * @param string $line
     * @return void
     */
    public function setName($line)
    {
        $lineArray = str_split($line);
        $cleanHeader = '';

        $notAllowed = [
            '[',
            ']'
        ];

        foreach($lineArray as $character) {
            if (!empty($character) && !in_array($character, $notAllowed)) {
                $cleanHeader .= $character;
            }
        }

        $this->sectionName = trim($cleanHeader);
    }

    /**
     * Adds a new line
     *
     * @param string $line
     * @throws Exception
     * @return void
     */
    public function newLine($line)
    {
        $cleanedLine = new SectionLine($line);

        if ($cleanedLine->key) {

            // validates to make sure that there are no duplicate keys
            foreach ($this->sectionLines as $line) {
                if ($line->key == $cleanedLine->key) {
                    throw new Exception("A duplicate line key of '{$cleanedLine->key}' was found.");
                    break;
                }
            }

            array_push($this->sectionLines, $cleanedLine);
        }
    }

    /**
     * Continues an existing line
     *
     * @param string $line
     * @return void
     */
    public function continueLine($line)
    {
        $index = count($this->sectionLines) - 1;
        $lastLine =  $this->sectionLines[$index];
        $lastLineContent = $lastLine->content;
        $lastLineContent .= $this->cleanContinuedLine($line);

        $lastLine->content = $lastLineContent;

        $this->sectionLines[$index] = $lastLine;
    }

    /**
     * Gets the line by it's key
     *
     * @param string $key
     * @throws Exception
     * @return string
     */
    public function getLineContentByKey($key) {
        $targetedLine = $this->getLineByKey($key);

        if ($targetedLine) {
            return $targetedLine->content;
        }

        throw new Exception("The line with the key '{$key}' that you provided doesn't exist");
    }

    /**
     * Updates a line's content by it's key
     *
     * @param string $key
     * @param string $content
     * @throws Exception
     * @return null|SectionLine|string
     */
    public function updateLineContent($key, $content)
    {
        $targetedLine = $this->getLineByKey($key);

        if (!is_null($targetedLine)) {
            $targetedLine->content = $content;

            return $targetedLine;
        }

        throw new Exception("The line with the key '{$key}' that you provided doesn't exist");
    }

    /**
     * Updates a section name
     *
     * @param string $newSectionName
     * @return $this
     */
    public function updateSectionName($newSectionName)
    {
        $this->sectionName = $newSectionName;

        return $this;
    }

    /**
     * Gets the line by it's key
     *
     * @param $key
     * @return SectionLine|null
     */
    private function getLineByKey($key) {
        foreach ($this->sectionLines as $line) {
            if ($line->key == $key) {
                return $line;
            }
        }

        return null;
    }

    /**
     * Cleans an existing line so it can be appended
     *
     * @param string $line
     * @return string
     */
    private function cleanContinuedLine($line) {
        $cleanedLine = $line;

        // removes leading spaces
        while (substr($cleanedLine,0, 1)  == ' ') {
            $cleanedLine = substr($cleanedLine, 1);
        }

        // removes line breaks
        if (strstr($cleanedLine, "\n")) {
            $cleanedLine = substr($cleanedLine, 0, -1);
        }

        // re-adds leading space for readability
        $cleanedLine = ' '.$cleanedLine;

        return $cleanedLine;
    }
}