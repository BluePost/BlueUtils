<?php
/**
 * Copy a file, or recursively copy a folder and its contents
 * @author Aidan Lister <aidan@php.net>
 * @version	1.0.1
 * @link	http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
 * @param string   $source	Source path
 * @param string   $dest	  Destination path
 * @param string   $permissions New folder creation permissions
 * @return bool	 Returns true on success, false on failure
 */
function xcopy($source, $dest, $permissions = "0777")
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, $permissions, true);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        xcopy("$source/$entry", "$dest/$entry", $permissions);
    }

    // Clean up
    $dir->close();
    return true;
}

function getFileConts ($PATH) {

    $handle = fopen($PATH, "r");
    if (!$handle) return FALSE;
    $conts = fread($handle, filesize($PATH));
    fclose($handle);
    return $conts;

}


function array_to_attributes ( $array_attributes )
{

    $attributes_str = NULL;
    foreach ( $array_attributes as $attribute => $value )
    {

        $attributes_str .= "$attribute=\"$value\", ";

    }

    return $attributes_str;
}
