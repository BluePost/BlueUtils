# This script will initialise a basic BlueUtils project to get started with
# It includes an API directory, a basic header script and a fully working live-serve bundler installation
# It will NOT (currently) adapt the .htaccess file to your path so please set that correctly
# It assumes that the project will be in the directory ABOVE the repo (../..)
# Use with -u to update just the BlueUtils objects

while getopts "u" opt; do
	case $opt in
		u)
			echo "Running in update mode"
			update=true
			;;
		\?)
			echo "Invalid option" >&2
			;;
	esac
done

# Add the basic units of the project
mkdir ../../helpers ../../display ../../api ../../static
cp ../BlueAjax ../BlueError ../BlueUtils.php ../../helpers -r
cp ../Bundler ../../ -r
cp ../Bundler/src/live-serve/index.php ../../

if ["$update" = false]; then
	cp ../Bundler/src/live-serve/.htaccess ../../
	cp basic/config.php ../..
	
	cd ../..
	composer require twig/twig

fi
