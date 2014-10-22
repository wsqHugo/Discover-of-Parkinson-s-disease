#!/usr/bin/python

import os
import subprocess
import sys, getopt
sys.path.append('/usr/local/lib/python2.7/site-packages')
import cv2
import numpy
import pylab
import scipy.signal
import scipy.fftpack

import cv2.cv as cv

def resize_video(input_file, output_file, dry_run=False):
    """
    Converts a file to mp4. Requires ffmpeg and libx264

    input_file -- The file to convert
    dry_run -- Whether to actually convert the file
    """
    ffmpeg_command = 'ffmpeg -loglevel quiet -i ' + input_file + ' -vf scale=iw/2:-1 ' + output_file

    #subprocess.call("rm %s" % input_file,shell=True)

    if not os.path.exists(output_file):
        if not os.path.exists(input_file):
            print "%s was queued, but does not exist" % input_file
            return

        if dry_run:
            print "%s" % input_file
            return

        print "Converting %s to MP4\n" % input_file

        #ffmpeg
        print subprocess.call(ffmpeg_command,shell=True)

        #qtfaststart so it streams
        print subprocess.call('qtfaststart "%s"' % output_file,shell=True)

        #permission fix
        print subprocess.call('chmod 777 "%s"' % output_file,shell=True)

        print "Done.\n\n"

    elif not dry_run:
        print "%s already exists. Aborting conversion." % output_file



def convert_to_mp4(input_file, output_file, dry_run=False):
    """
    Converts a file to mp4. Requires ffmpeg and libx264

    input_file -- The file to convert
    dry_run -- Whether to actually convert the file
    """
    ffmpeg_command = 'ffmpeg -loglevel quiet -i "%s" -vcodec libx264 -b 700k -s 360x240 -acodec libfaac -ab 128k -ar 48000 -f mp4 -deinterlace -y -threads 4 "%s" ' % (input_file,output_file)
    #subprocess.call("rm %s" % input_file,shell=True)

    if not os.path.exists(output_file):
        if not os.path.exists(input_file):
            print "%s was queued, but does not exist" % input_file
            return

        if dry_run:
            print "%s" % input_file
            return

        print "Converting %s to MP4\n" % input_file

        #ffmpeg
        print subprocess.call(ffmpeg_command,shell=True)

        #qtfaststart so it streams
        print subprocess.call('qtfaststart "%s"' % output_file,shell=True)

        #permission fix
        print subprocess.call('chmod 777 "%s"' % output_file,shell=True)

        print "Done.\n\n"

    elif not dry_run:
        print "%s already exists. Aborting conversion." % output_file

def convert_all_to_mp4(input_dir, allowed_extensions, dry_run=False):
    """
    Converts all files in a folder to mp4

    input_dir -- The directory in which to look for files to convert
    allowed_extensions -- The file types to convert to mp4
    dry_run -- If set to True, only outputs the file names
    """
    for root, dirs, files in os.walk(input_dir):
        for name in files:
            if name.lower().endswith(allowed_extensions):
                convert_to_mp4(os.path.join(root, name),dry_run);

def remove_converted_files(directory, allowed_extensions, dry_run=False):
    """
    Removes converted files from the directory

    directory -- The path from which to remove files
    allowed_extensions -- The file extensions to remove
    dry_run -- If set to True, only outputs the file names
    """
    for root, dirs, files in os.walk(directory):
        for name in files:
            #If a video with the same name appended with .mp4 exists, delete it
            #Please not that the converted video isn't checked, and that the file may exist
            #even though the conversion failed.
            if name.lower().endswith(allowed_extensions) and os.path.exists('%s.mp4' % os.path.join(root, name)):
                if(dry_run):
                    print "%s" % os.path.join(root, name)
                else:
                    subprocess.call("rm %s" % os.path.join(root, name),shell=True)
                    print "%s deleted" % os.path.join(root, name)

def remove_useless_files(directory, ignored_extensions, dry_run=False):
    """
    Removes files that are not videos (i.e. nfos, readmes, screenshots...), or that cannot be converted (.iso) and scripts (.py)

    directory -- The path from which to remove files
    ignored_extensions -- The file extensions to ignore
    dry_run -- If set to True, only outputs the file names
    """
    for root, dirs, files in os.walk(directory):
        for name in files:
            if not name.lower().endswith(ignored_extensions) and not name.lower().endswith(('.mp4','.py','.iso')):
                if(dry_run):
                    print "%s" % os.path.join(root, name)
                else:
                    subprocess.call("rm '%s'" % os.path.join(root, name),shell=True)
                    print "%s deleted" % os.path.join(root, name)

def flatten_directory():
    """
    Removes subdirectories after moving the files to the root dir

    dry_run -- If set to True, only outputs the file names
    """
    subprocess.call("find -L %s -mindepth 2 -type f -exec mv -t %s -i '{}' + && find -L %s -type d -empty -exec rmdir {} \;" % (video_directory,video_directory,video_directory),shell=True)


def get_capture_dimensions(capture):
    """Get the dimensions of a capture"""
    width = int(capture.get(cv.CV_CAP_PROP_FRAME_WIDTH))
    height = int(capture.get(cv.CV_CAP_PROP_FRAME_HEIGHT))
    return width, height

def get_frame_dimensions(frame):
    """Get the dimensions of a single frame"""
    height, width = frame.shape[:2]
    return width, height

def load_video(video_filename):
    """Load a video into a numpy array"""
    print "Loading " + video_filename
    # noinspection PyArgumentList
    capture = cv2.VideoCapture(video_filename)
    frame_count = int(capture.get(cv.CV_CAP_PROP_FRAME_COUNT))
    width, height = get_capture_dimensions(capture)
    fps = int(capture.get(cv.CV_CAP_PROP_FPS))
    x = 0
    orig_vid = numpy.zeros((frame_count, height, width, 3), dtype='uint8')
    while True:
        _, frame = capture.read()

        if frame == None or x >= frame_count:
            break
        
        orig_vid[x] = frame
        x += 1
        print str(x)
    capture.release()
    return orig_vid, fps


def convert2mp4(path, output_filename):
    dry_run = False
    #The files that should be converted to mp4
    allowed_extensions = ('.mkv','.avi','.mpg','.wmv','.mov','.m4v','.3gp','.mpeg','.mpe','.ogm','.flv','.divx','.mp4')
    temp_filename = output_filename+'_temp'
    convert_to_mp4(path, temp_filename, dry_run)
    resize_video(temp_filename, output_filename, dry_run)
