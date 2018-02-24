<?php

/**
 * Created by PhpStorm.
 * User: dougniccum
 * Date: 2/23/18
 * Time: 12:42 PM
 */

include ('FileUtility.php');

$file = new FileUtility('content.txt');

$file->setSectionValue('meta data', 'description', 'This is a very basic description that totally makes sense.');

$file->printValue('meta data', 'description');

$file->setSectionHeader('trailer', 'footer');
