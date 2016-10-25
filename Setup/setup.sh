#!/usr/bin/env bash
# This script will initialise a basic BlueUtils project to get started with
# It includes an API directory, a basic header script and a fully working live-serve bundler installation
# It will NOT (currently) adapt the .htaccess file to your path so please set that correctly
# It assumes that the project will be in the directory ABOVE the repo (../..)
# Use with -n to create a new project
new=false
while getopts "n" opt; do
	case $opt in
		n)
			echo "Running in new mode"
			new=true
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
\cp ../BlueAuth/ ../../helpers/ -fr
\cp ../BlueFiller/src/lib.js ./../../static/scripts/BlueFiller.js -fr
\cp ../BlueEMail ../../helpers/ -fr
\cp ../BlueUtilsFunctions.php ../../helpers/

# If we are building a new project add settings etc
if [ "$new" = true ] ; then
	# Settings class
	\cp ../BlueUtilsSettings ../../helpers/ -rf

	# Live serve
	\cp ../Bundler/src/live-serve/.htaccess ../../ -f

	# Bundler Config
	\cp basic/config.php ../.. -f

	# API Header file
	\cp basic/header.php ../../helpers/ -f

	# Auth API endpoints
	\cp basic/auth_api/* ../../api -f

	cd ../..
	echo "Requiring composer packages"
	composer require twig/twig
	composer require sendgrid/sendgrid
	composer require joshcam/mysqli-database-class

fi
