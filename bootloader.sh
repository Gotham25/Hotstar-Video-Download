#!/bin/sh

FFMPEG_BUILD_NAME=ffmpeg.zip
FFPROBE_BUILD_NAME=ffprobe.zip

echo "Downloading FFMPEG static build latest release from ffbinaries.com"
wget -q https://github.com/vot/ffbinaries-prebuilt/releases/download/v4.1/ffmpeg-4.1-linux-64.zip -O $FFMPEG_BUILD_NAME
RESULT=$?
if [ $RESULT -ne 0 ]; then
	echo "Cannot download FFMPEG latest release from https://ffbinaries.com site"
	exit 11 # terminate and indicate error
fi
echo "Downloaded ffmpeg static build extract and saved it as $FFMPEG_BUILD_NAME"

echo "Downloading FFPROBE static build latest release from ffbinaries.com"
wget -q https://github.com/vot/ffbinaries-prebuilt/releases/download/v4.1/ffprobe-4.1-linux-64.zip -O $FFPROBE_BUILD_NAME
RESULT=$?
if [ $RESULT -ne 0 ]; then
	echo "Cannot download FFPROBE latest release from https://ffbinaries.com site"
	exit 12 # terminate and indicate error
fi
echo "Downloaded ffprobe static build extract and saved it as $FFPROBE_BUILD_NAME"


echo "Extracting static build extract $FFMPEG_BUILD_NAME"
unzip -q -o -j $FFMPEG_BUILD_NAME "ffmpeg"
RESULT=$?
if [ $RESULT -ne 0 ]; then
	echo "Error occurred in extracting $FFMPEG_BUILD_NAME"
	exit 21 # terminate and indicate error
fi
echo "Extraction completed successfully"

echo "Removing build extract $FFMPEG_BUILD_NAME"
rm -rf "$FFMPEG_BUILD_NAME"
echo "Removed build extract $FFMPEG_BUILD_NAME"

echo "Extracting static build extract $FFPROBE_BUILD_NAME"
unzip -q -o -j $FFPROBE_BUILD_NAME "ffprobe"
RESULT=$?
if [ $RESULT -ne 0 ]; then
	echo "Error occurred in extracting $FFPROBE_BUILD_NAME"
	exit 22 # terminate and indicate error
fi
echo "Extraction completed successfully"

echo "Removing build extract $FFPROBE_BUILD_NAME"
rm -rf "$FFPROBE_BUILD_NAME"
echo "Removed build extract $FFPROBE_BUILD_NAME"

#converting static binaries to executables
chmod +x ff*

#adding them to path variables
export PATH="$PATH:$(pwd)/ffmpeg:$(pwd)/ffprobe"
echo "FFMPEG and FFPROBE libs installed successfully"

heroku-php-apache2  #start web server