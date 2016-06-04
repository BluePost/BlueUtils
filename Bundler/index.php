m<?php
require_once __DIR__ . '/composer/vendor/autoload.php';

//Functions
/**
 * Copy a file, or recursively copy a folder and its contents
 * @author	  Aidan Lister <aidan@php.net>
 * @version	 1.0.1
 * @link		http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
 * @param	   string   $source	Source path
 * @param	   string   $dest	  Destination path
 * @param	   string   $permissions New folder creation permissions
 * @return	  bool	 Returns true on success, false on failure
 */
function xcopy($source, $dest, $permissions = 0777)
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
		mkdir($dest, $permissions);
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
//Load Twig
$GLOBALS['TWIGLOADER'] = new Twig_Loader_Filesystem(__DIR__ . '/twig/');
$GLOBALS['TWIG'] = new Twig_Environment($GLOBALS['TWIGLOADER'], array(
	'debug' => true
));
$GLOBALS['TWIG']->addExtension(new Twig_Extension_Debug());
function twigrender($file) {
	global $GLOBALS;
	return $GLOBALS['TWIG']->render($file, $GLOBALS);
}
require_once __DIR__ . '/pages.php';


if (php_sapi_name() == "cli") { //Command line mode, this will bundle
	echo "\n\n\t\t\t\tJAMES BITHELL\n\t\t\t\tTWIG BUNDLER\n\n";
	if (!isset($argv[1])) die("You must set a version number \n\nEXAMPLE: php index.php " . '"1.0"' . "\n\n\n");
	$newreleasepath = __DIR__ . '/releases/' . $argv[1] . '/';
	if (file_exists($newreleasepath)) die("Release already exisits - Make a new one!!\n\n");
	mkdir($newreleasepath,0777,true);

	xcopy(__DIR__ . '/weblibs/', $newreleasepath. '/weblibs/'); //Copy required files

	$counter = 0;
	foreach ($PAGES as $PAGENAME => $PAGE) {
		if ($counter == 0) {
			fwrite(fopen($newreleasepath . 'index.html', "w"),twigrender($PAGE['TWIG']));
		} else {
			mkdir($newreleasepath . $PAGENAME,0777,true);
			fwrite(fopen($newreleasepath . $PAGENAME . '/index.html', "w"),twigrender($PAGE['TWIG']));
		}
		$counter++;
	}

	fwrite(fopen($newreleasepath . '.htaccess', "w"),'ErrorDocument 404 /404'); //Setup 404 Error Page

	echo "Completed - Please check " . $newreleasepath . " for your project\n\n";
} else {
	$pathfinderrequest = parse_url($_SERVER['REQUEST_URI']);
	$pathfinderpath = $pathfinderrequest["path"];

	$pathfinderresult = trim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $pathfinderpath), '/');
	$pathfinderresult = explode('/', $pathfinderresult);
	$pathfindermax_level = 5;
	while ($pathfindermax_level < count($pathfinderresult)) {
		unset($pathfinderresult[0]);
	}
	$pathfinderresult = implode('/', $pathfinderresult);

	if ($pathfinderresult == "") $PAGE = array_values($PAGES)[0];
	elseif (!isset($PAGES[$pathfinderresult])) $PAGE = $PAGES['404'];
	else $PAGE = $PAGES[$pathfinderresult];

	die(twigrender($PAGE['TWIG']));
}
?>
