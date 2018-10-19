wget -q https://github.com/vot/ffbinaries-prebuilt/releases/download/v4.0/ffmpeg-4.0.1-linux-64.zip -O ffmpeg.zip
find . -depth -name 'ffmpeg.zip' -exec unzip -o {} ffmpeg \; -exec rm {} \;
chmod +x ffmpeg

wget -q https://github.com/vot/ffbinaries-prebuilt/releases/download/v3.2/ffprobe-3.2-linux-64.zip -O ffprobe.zip
find . -depth -name 'ffprobe.zip' -exec unzip -o {} ffprobe \; -exec rm {} \;
chmod +x ffprobe
