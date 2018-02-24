# File Utility

A basic PHP-based text-file parser with the ability to read/write content.

## How to use

To use this file parser, include the utility like so `include ('FileUtility.php');` in the header of your file.

Next initialize the class by passing the name of a text file:

```php
$file = new FileUtility('content.txt');
```

## Content

In order for this class to be able to parse the content, the content must be formatted like so:

```text
[header]
project: Programming Test
budget : 4.5
accessed :205

[meta data]
description : This is a tediously long description of
​ ​ ​the​ programming test that you are taking. Tedious
   isn't the right word, but it's the first word that
 comes to mind.

correction text: I meant 'moderately' not 'tediously'.

[ trailer ]
budget:all out of budget
```

The section name is surrounded by `[` and `]` brackets.

Then each section line is then defined by a key, which comes before the `:` and the content that comes after the `:`.

## Available Methods

Once the class has been constructed using a valid file name, you will have access to the following methods:

### setSectionValue

This sets a value with the associated key in a valid section. Once complete, this updates the file that was used within the constructor.

##### example

```php
$file->setSectionValue('meta data', 'description', 'This is a very basic description that totally makes sense.');
```

##### params

* sectionName - string - The name of the section that you want to set
* key - string - The key of the content that you want to set
* content - string - The content that you would like to associated with the key

##### returns

Returns the updated section

### setSectionHeader

Renames the header of the section

##### example

```php
$file->setSectionHeader('trailer', 'footer');
```

##### params

* sectionName - string - The name of the section that you want to rename
* newSectionName - string - The  new name of the section

##### returns

Returns the updated section

### getValue

Returns the content associated with the section name and content key

##### example

```php
$content = $file->getValue('trailer', 'budget');
echo $content;
```

##### params

* sectionName - string - The name of the section associated with this content
* key - string - The key of the content that you want to retrieve

##### returns

string

### printValue

Same of that of the `getValue` method, except this value is printed in the page.

##### example

```php
$file->printValue('trailer', 'budget');
```

##### params

* sectionName - string - The name of the section associated with this content
* key - string - The key of the content that you want to retrieve

##### returns

`print_r` printed string