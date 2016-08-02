# This script will initialise a basic BlueUtils project to get started with
# It includes an API directory, a basic header script and a fully working live-serve bundler installation
# It will NOT (currently) adapt the .htaccess file to your path so please set that correctly
# It assumes that the project will be in the directory ABOVE the repo (../..)
# Use with -n to create a new project
update=false
while getopts "n" opt; do
	case $opt in
		n)
			echo "Running in new mode"
			update=true
			;;
		\?)
			echo "Invalid option" >&2
			;;
	esac
done

# Add the basic units of the project
mkdir ../../helpers ../../display ../../api ../../static ../../static/scripts ../../static/templates
\cp ../BlueAjax ../BlueError ../BlueUtils.php ../../helpers -fr
\cp ../Bundler ../../ -fr
\cp ../Bundler/src/live-serve/index.php ../../ -f
\cp ../Loadr ../../static -fr
\cp ../BlueAjax/JS/BlueAjax.js ../../static/scripts/ -f
\cp ../BlueAuth/ ../../helpers/BlueAuth -fr

if [ "$update" = true ] ; then
	\cp ../Bundler/src/live-serve/.htaccess ../../ -f
	\cp basic/config.php ../.. -f
	\cp basic/header.php ../../helpers/ -f
	\cp basic/api_config.php ../../helpers/ -f

	\cp basic/auth_api/* ../../api -f

	cd ../..
	echo "Requiring twig"
	composer require twig/twig
	composer require joshcam/mysqli-database-class

fi
