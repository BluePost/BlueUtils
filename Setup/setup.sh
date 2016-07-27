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
cp ../BlueAjax ../BlueError ../BlueUtils.php ../../helpers -r
cp ../Bundler ../../ -r
cp ../Bundler/src/live-serve/index.php ../../
cp ../Loadr ../../static -r
cp ../BlueAjax/JS/BlueAjax.js ../../static/scripts/

if [ "$update" = true ] ; then
	cp ../Bundler/src/live-serve/.htaccess ../../
	cp basic/config.php ../..
	cp basic/header.php ../../helpers/
	
	cd ../..
	echo "Requiring twig"
	composer require twig/twig

fi
