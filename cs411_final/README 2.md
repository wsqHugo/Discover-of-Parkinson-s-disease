Python MP4 converter
====================

Converts videos to a format compatible with mobile devices and web video players using `ffmpeg`.

The converted files bear the same name as the original files with the added `.mp4` extension. For example, `movie.avi` will be converted and saved to `movie.avi.mp4`.

I didn't originally intend to redistribute that script, hence the lack of refinement. I used it to successfully convert every format under the sun to bandwidth-efficient videos that can be read by FlowPlayer across browsers and devices.

##Requirements

`ffmpeg` and `libx264` are required for this script to work.

##Usage

###Basic usage

`python convert.py -f [file]` or `python convert.py -d [directory]`

###Options:

* `-d [directory], --dir [directory]`: The directory of videos to convert. All matching files will be converted.
* `-f [file], --file [file]`: The video to convert.
* `-n, --dry-run`: List the files that would be converted or cleaned up without applying any changes.
* `-p, --purge`: Delete original videos after conversion.
* `-c, --cleanup`: Removes all non-video files from the directory, including pictures, .nfo files, text files and the rest
* `-h, --help`: Shows a pretty useless description of the script
