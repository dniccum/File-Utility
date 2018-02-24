<?php
/**
 * Created by PhpStorm.
 * User: dougniccum
 * Date: 2/24/18
 * Time: 12:29 PM
 */

include ('Section.php');

class FileUtility {

    /**
     * @var $file
     */
    private $file;

    /**
     * @var string $file
     */
    private $filePath;

    /**
     * @var Section[] $sections
     */
    private $sections;

    /**
     * FileParse constructor.
     *
     * @param string $filePath
     * @return void
     */
    function __construct($filePath)
    {
        $this->file = fopen($filePath, "r+") or die("Unable to open file!");

        $this->filePath = $filePath;
        $this->sections = [];

        try {
            $this->setSections();
        } catch (Exception $e) {
            exit('Exception: ' .$e->getMessage());
        }

        fclose($this->file);
    }

    /**
     * Gets the value of the section and key pair
     *
     * @param string $sectionName
     * @param string $key
     * @return string
     */
    public function getValue($sectionName, $key)
    {
        try {
            $targetSection = $this->getSectionByName($sectionName);
            return $targetSection->getLineContentByKey($key);
        } catch (Exception $e) {
            return exit('Exception: ' .$e->getMessage());
        }
    }

    /**
     * Prints a section and key pair
     *
     * @param string $sectionName
     * @param string $key
     * @return void
     */
    public function printValue($sectionName, $key)
    {
        echo $this->getValue($sectionName, $key);
    }

    /**
     * Retrieves a section by a name and updates the content of that key
     *
     * @param string $sectionName
     * @param string $key
     * @param string $content
     * @return string|Section
     */
    public function setSectionValue($sectionName, $key, $content)
    {
        try {
            $targetSection = $this->getSectionByName($sectionName);
            $targetSection->updateLineContent($key, $content);

            $this->updateFile();

            return $targetSection;
        } catch (Exception $e) {
            return exit('Exception: ' .$e->getMessage());
        }
    }

    /**
     * Updates a section header name
     *
     * @param $sectionName
     * @param $newSectionName
     * @return string|Section
     */
    public function setSectionHeader($sectionName, $newSectionName)
    {
        try {
            $targetSection = $this->getSectionByName($sectionName);
            $targetSection->updateSectionName($newSectionName);

            $this->updateFile();

            return $targetSection;
        } catch (Exception $e) {
            return exit('Exception: ' .$e->getMessage());
        }
    }

    /**
     * Builds the file's sections
     * @throws Exception
     * @return void
     */
    private function setSections() {
        $runningSection = new Section();

        $sectionNames = [];

        while (($line = fgets($this->file)) !== false) {
            $firstCharacter = substr($line,0, 1);

            if ($firstCharacter == "[") {
                if ($runningSection->sectionName) {
                    array_push($this->sections, $runningSection);
                }

                $runningSection = new Section();
                $runningSection->setName($line);

                // validates to make sure no duplicate section names exist
                if (in_array($runningSection->sectionName, $sectionNames)) {
                    throw new Exception("A duplicate section name of '{$runningSection->sectionName}' was found.");
                } else {
                    array_push($sectionNames, $runningSection->sectionName);
                }
            } else {
                // removes unknown tabs/characters
                $line = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $line);

                if (substr($line,0, 1) == ' ') {
                    $runningSection->continueLine($line);
                } else {
                    $runningSection->newLine($line);
                }
            }
        }

        // add the last line
        array_push($this->sections, $runningSection);
    }

    /**
     * Returns the section by its name
     *
     * @param $sectionName
     * @throws Exception
     * @return null|Section
     */
    private function getSectionByName($sectionName)
    {
        $targetSection = null;

        foreach ($this->sections as $section) {
            if ($section->sectionName == $sectionName) {
                $targetSection = $section;
                break;
            }
        }

        if (!is_null($targetSection)) {
            return $targetSection;
        }

        throw new Exception("The section with the name '{$sectionName}' that you provided doesn't exist");
    }

    /**
     * Re-writes the file with the updated section content and values.
     */
    private function updateFile()
    {
        $filePath = $this->filePath;

        // clears file
        $file = fopen($filePath, "w");

        for ($i = 0; $i < count($this->sections); $i++) {
            $section = $this->sections[$i];

            // write header
            fwrite($file, "[".$section->sectionName."]\n");

            // write sections
            foreach ($section->sectionLines as $line) {
                fwrite($file, $line->key." : ".$line->content."\n");
            }

            if ($i + 1 < count($this->sections)) {
                // adds additional line break
                fwrite($file, "\n");
            }
        }

        fclose($file);
    }
}