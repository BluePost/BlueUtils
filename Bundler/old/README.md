# Bundler
*System to Bundle PHP/Twig Projects into html for use in PhoneGap and Element, or hosting where dynamic content is unavailable - such as in Amazon S3 or Github Pages*

##Usage

1. Download and unzip the latest <a href="https://github.com/Jbithell/Bundler/releases/latest">release</a> into a directory you use for the development area of your site *(ie `beta.yourdomain.com`)* - Composer is included to include <a href="http://twig.sensiolabs.org/">Twig</a> - You can remove it if you put your `autoload.php`'s path in the index.php file. 
1. Place all your required files (e.g. `bootstrap.css`, `jquery.min.js`, images, etc. in `weblibs/` - When referencing your weblibs from code be sure to include the weblibs file - ie `<script src="weblibs/jquery.min.js"></script>`)
1. Place all your twig files in `twig/`
	* For dymanically produced javascript, templates, etc. place them in the `twig/` directory and reference them within your twig.
	* **All twig paths are relative to `twig/`**
1. For each page you would like to create: add it to the `$PAGES` variable in `pages.php`, with its `"TWIG"` value set to the path (from within `twig/`) of your twig file, and the key to the page url *(ie for the contactus page you may set it to `"contactus"` - Only use paths a maximum of three levels deep - don't use "contact/us/today/online")*
1. **For a live view and testing** - access your directory (ie `beta.yourdomain.com`), with `/PAGENAME` for each page - **the index file is returned if no page name is passed (ie the root)**, and the 404 page if the page doesn't exist.
1. For each release call `php index.php "VERSION NUMBER (ie 0.1.1)"` from your command line - your release will be saved in `releases/VERSION NUMBER/` - and upload it to your site, S3, Github Pages, or whatever you are hosting the production site on. It should also be compatible with Atom and PhoneGap

##Support
For issues please use the <a href="https://github.com/Jbithell/Bundler/issues">Github issues tracker</a>
