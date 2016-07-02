# Bundler
A utility that allows development of twig files that can then be compiled to static HTML files

## The idea
The idea behind bundler is that a website should be able to be hosted as static HTML files for normal users but that developers should not have to sacrifice the clarity of includes and templates. Bundler solves this problem in two ways: first, it provides a "live-serve" ability that will automatically serve up your twig files at the endpoints you want. This works but using a `.htaccess` file and a 404 `ErrorDocument`. Bundler also has a commmand line utility that gives the ability to turn your TWIG files into static HTML. This produces a number of directories, each whith an `index.html` file to preserve the links.

## Live-Serve
### Installation
From the downloaded source, copy `live-serve/index.php` to the root of your project and set it as the 404 `ErrorDocument`. Next create a file called `config.php`. It should look like this:
```php
$CONFIG = Array("NAME" => "TEST");

$BUNDLER_CONFIG_ARRAY = Array (
    "files" => Array (
        "index" => "main.twig",
        "test"   => "test.twig",
		"404"	=> "404.twig"
    ),
    "pageopts_file" => "config.php",
	"static_file_dir" => "/static",
	"twig_dir" => "/twig",
    "base_url" => "working/BlueUtils/Bundler/testing"
);
```

The `$CONFIG` array is given to all twig files as the CONFIG environment variable. The `$BUNDLER_CONFIG_ARRAY` array is used to configure both live-serve and bundler. Here the files array points each endpoint to a file. This is in the style of `"<endpoint-name>"=>"<twig-file>"`. The first entry in the array should ALWAYS be the index, used for requests to `/`.

### Other Options
* `pageopts_file` = The file to be included that contsains the page's $CONFIG variable
* `twig_dir` = The directory of twig files
* `base_url` = Only used for live serve, this is removed from all request URIs to find the correct page
* `pageopts_var` = The vaiable name of the page's $CONFIG variable [Defaults to `CONFIG`] (Do not include a $ in front of it)

## Bundler (Static)
### Installation
This uses the same config file as live-serve, so you simply invoke it from the root of your project like so:
```bash
$ php /path/to/bundler.php -r 1.0
```

### Commandline Options
* `-r <release-string>` = The release string of the bundle - required for every call
* `-y` = Skips the confirmation step
* `-f` = Will overwrite previous releases of the same name
* `-c <config-file>` = The path to the configuration PHP file. Defaults to config.php

### Config Options
Bundler uses the same options as live-serve as well as:

* `static_file_dir` = The directory of static resources to be copied (i.e. JS scripts) [Defaults to `/static`]

## Trying it out
To try this out simply go to the testing folder inside the source. Update the `.htaccess` file to point to the correct file, as well as the `config.php` base_url option and then you can use live serve. To Create a static release use:
```bash
$ php ../src/bundler.php -r 1.0
```

### Directories
You may want to store your twig files in separate directories for organisation. Bundler can support this as long as the correct path is included in the config file. 