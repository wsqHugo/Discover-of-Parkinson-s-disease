import sys
sys.path.append('/usr/local/lib/python2.7/site-packages')
import cv2
import os
import sys

import numpy
import pylab
import scipy.signal
import scipy.fftpack

import cv2.cv as cv


def eulerian_magnification(video_filename, image_processing='gaussian', freq_min=0.833, freq_max=1, amplification=50, pyramid_levels=4,lambda_c=0.4, wf_file="test.txt"):
    """Amplify subtle variation in a video and save it to disk"""
    path_to_video = os.path.join(os.path.dirname(os.path.abspath(sys.argv[0])), video_filename)
    orig_vid, fps = load_video(path_to_video)
    vidWidth, vidHeight = get_frame_dimensions(orig_vid[0])
    delta = lambda_c/8/(1+amplification)
    exaggeration_factor = 2
    lambda_a = ((vidHeight**2 + vidWidth**2)**(0.5))/3
    pyr_level = get_pyr_level(vidWidth, vidHeight)
    vid_pyr = []
    for i in range(2, pyr_level):
        vid_pyr.append(laplacian_video(orig_vid, i))

    print "Amplifying signal by factor of " + str(amplification)
    vid_pyr_flted = []
    for i in range(0, len(vid_pyr)):

        vid_data_temp = temporal_bandpass_filter(vid_pyr[i], fps, freq_min=freq_min, freq_max=freq_max)
        currAlpha = lambda_a/delta/8 - 1
        currAlpha = currAlpha*exaggeration_factor;
        if (currAlpha > amplification):
            vid_data_temp *= amplification
        else:
            vid_data_temp *= currAlpha
        vid_pyr_flted.append(vid_data_temp)

    return combine_pyramid_and_save(vid_pyr_flted, orig_vid, pyramid_levels, fps, wf_file)


def show_frequencies(video_filename, bounds=None):
    """Graph the average value of the video as well as the frequency strength"""
    original_video, fps = load_video(video_filename)
    print fps
    averages = []

    if bounds:
        for x in range(1, original_video.shape[0] - 1):
            averages.append(original_video[x, bounds[2]:bounds[3], bounds[0]:bounds[1], :].sum())
    else:
        for x in range(1, original_video.shape[0] - 1):
            averages.append(original_video[x, :, :, :].sum())

    charts_x = 1
    charts_y = 2
    pylab.figure(figsize=(charts_y, charts_x))
    pylab.subplots_adjust(hspace=.7)

    pylab.subplot(charts_y, charts_x, 1)
    pylab.title("Pixel Average")
    pylab.plot(averages)

    frequencies = scipy.fftpack.fftfreq(len(averages), d=1.0 / fps)

    pylab.subplot(charts_y, charts_x, 2)
    pylab.title("FFT")
    pylab.axis([0, 15, -50000000, 50000000])
    pylab.plot(frequencies, numpy.absolute(scipy.fftpack.fft(averages)))

    pylab.show()


def temporal_bandpass_filter(data, fps, freq_min=0.833, freq_max=1, axis=0):
    print "Applying bandpass between " + str(freq_min) + " and " + str(freq_max) + " Hz"
    fft = scipy.fftpack.fft(data, axis=axis)
    frequencies = scipy.fftpack.fftfreq(data.shape[0], d=1.0 / fps)
    bound_low = (numpy.abs(frequencies - freq_min)).argmin()
    bound_high = (numpy.abs(frequencies - freq_max)).argmin()
    fft[:bound_low] = 0
    fft[bound_high:-bound_high] = 0
    fft[-bound_low:] = 0

    return scipy.fftpack.ifft(fft, axis=0)


def load_video(video_filename):
    """Load a video into a numpy array"""
    #print "Loading " + video_filename
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
        #print str(x)
    capture.release()
    return orig_vid, fps


def save_video(video, fps, save_filename='media/output.avi'):
    """Save a video to disk"""
    fourcc = cv.CV_FOURCC('M', 'J', 'P', 'G')
    writer = cv2.VideoWriter(save_filename, fourcc, fps, (video.shape[2], video.shape[1]), 1)
    for x in range(0, video.shape[0]):
        res = cv2.convertScaleAbs(video[x])
        writer.write(res)


def gaussian_video(video, shrink_multiple):
    """Create a gaussian representation of a video"""
    vid_data = None
    for x in range(0, video.shape[0]):
        frame = video[x]
        gauss_copy = numpy.ndarray(shape=frame.shape, dtype="float")
        gauss_copy[:] = frame
        for i in range(shrink_multiple):
            gauss_copy = cv2.pyrDown(gauss_copy)

        if x == 0:
            vid_data = numpy.zeros((video.shape[0], gauss_copy.shape[0], gauss_copy.shape[1], 3))
        vid_data[x] = gauss_copy
    return vid_data


def laplacian_video(video, shrink_multiple):
    vid_data = None
    for x in range(0, video.shape[0]):
        frame = video[x]
        frame = cv2.cvtColor(frame,cv2.COLOR_BGR2YUV)
        #print "frame " + str(frame.shape[1]) + " x " + str(frame.shape[0])

        gauss_copy = numpy.ndarray(shape=frame.shape, dtype="float")
        pre_copy = numpy.ndarray(shape=frame.shape, dtype="float")
        gauss_copy[:] = frame

        for i in range(shrink_multiple):
            prev_copy = gauss_copy[:]
            gauss_copy = cv2.pyrDown(gauss_copy)
        
        dim = (frame.shape[1], frame.shape[0])
        gauss_copy = cv2.resize(gauss_copy, dim, interpolation=cv2.INTER_CUBIC)
        prev_copy = cv2.resize(prev_copy, dim, interpolation=cv2.INTER_CUBIC)

        #print "level " + str(shrink_multiple)
        #print "pre_copy " + str(pre_copy.shape[1]) + " x " + str(pre_copy.shape[0]) + " x " + str(pre_copy.shape[2])
        #print "gauss_copy " + str(gauss_copy.shape[1]) + " x " + str(gauss_copy.shape[0])+ " x " + str(gauss_copy.shape[2])
        laplacian = numpy.subtract(pre_copy, gauss_copy)

        if x == 0:
            vid_data = numpy.zeros((video.shape[0], laplacian.shape[0], laplacian.shape[1], 3))
        vid_data[x] = laplacian
    return vid_data


def combine_pyramid_and_save(vid_pyr_flted, orig_video, enlarge_multiple, fps, save_filename):
    """Combine a gaussian video representation with the original and save to file"""
    width, height = get_frame_dimensions(orig_video[0])
    pulse = []
    """
    averages = []
    for x in range(1, orig_video.shape[0] - 1):
        averages.append(orig_video[x, :, :, :].sum())
    pylab.plot(averages)
    pylab.show()
    """
    #cv2.imshow('dst_rt', orig_video[10])
    #cv2.waitKey(0)
    for x in range(1, orig_video.shape[0]-1):
        img = numpy.ndarray(shape=orig_video[x].shape, dtype='float')
        orig_img = orig_video[x]
        orig_img = cv2.cvtColor(orig_img,cv2.COLOR_BGR2YUV)
        img =  0.25*orig_img
        for i in range(0, len(vid_pyr_flted)):
            g_img = vid_pyr_flted[i]
            img += g_img[x] 

        # for i in range(1):
        #     img_2 = cv2.pyrUp(img_2)
        # img_2 = cv2.resize(img_2, (img.shape[1],img.shape[0]))
        # for i in range(2):
        #     img_3 = cv2.pyrUp(img_3)
        # img_3 = cv2.resize(img_3, (img.shape[1],img.shape[0]))
        # for i in range(3):
        #     img_4 = cv2.pyrUp(img_4)
        # img_4 = cv2.resize(img_4, (img.shape[1],img.shape[0]))

        #cvtColor(src1, src1Gray, CV_RGB2GRAY);
        #img[:height, :width] = img[:height, :width] + 0.25*orig_video[x]
        
        #img =  orig_img
        pulse.append(img[:,:,0].sum())
        #pylab.plot(numpy.average(img))
        #pylab.show()
        
        #print str(img[:, :, :].sum())
    pulse = pulse[len(pulse)/8:len(pulse)*7/8]
    pulse = (pulse - (sum(pulse)/len(pulse)))/(sum(pulse)/len(pulse))
    
    target = open(save_filename, 'w')
    for i in range(0, len(pulse)):
        target.write(str(i*1.0/fps) + " " + str(pulse[i]) + "\n")
    target.close()

    #pylab.plot(pulse)
    #pylab.show()
    frequencies = scipy.fftpack.fftfreq(len(pulse), d=1.0 / fps)
    frequencies = frequencies[:frequencies.shape[0]/2]
    FFT = numpy.absolute(scipy.fftpack.fft(pulse))
    FFT = FFT[:FFT.shape[0]/2]
    index = FFT.argmax()
    #print str(frequencies[index])

    return str(frequencies[index])
    #pylab.plot(frequencies, FFT)
    #pylab.show()
    """
    freqs = scipy.fftpack.fftfreq(pulse_nparray.size, 1/fps)
    for x in range(0, FFT.shape[0]):
        print str(FFT)
    """

def write_file(string, filename):
    target = open(filename, 'w')
    target.write(string)
    target.close()

def plotSpectrum(y,Fs):
    """
    Plots a Single-Sided Amplitude Spectrum of y(t)
    """
    n = len(y) # length of the signal
    k = scipy.arange(n)
    T = n/Fs
    frq = k/T # two sides frequency range
    frq = frq[range(n/2)] # one side frequency range

    Y = scipy.fft(y)/n # fft computing and normalization
    Y = Y[range(n/2)]
 
    pylab.plot(frq,abs(Y),'r') # plotting the spectrum
    pylab.xlabel('Freq (Hz)')
    pylab.ylabel('|Y(freq)|')

def get_pyr_level(width, height):
    minEdge = min(width, height)
    pyr_level = 0
    while (minEdge > 2):
        minEdge = minEdge/2
        pyr_level += 1
    return pyr_level


def get_capture_dimensions(capture):
    """Get the dimensions of a capture"""
    width = int(capture.get(cv.CV_CAP_PROP_FRAME_WIDTH))
    height = int(capture.get(cv.CV_CAP_PROP_FRAME_HEIGHT))
    return width, height


def get_frame_dimensions(frame):
    """Get the dimensions of a single frame"""
    height, width = frame.shape[:2]
    return width, height

def RGB2YIQ(frame):
    #convertionMatrix = numpy.matrix("0.299 0.587 0.114; 0.595716 -0.274453 0.321263; 0.211456 -0.533591 0.311135")
    yiq_frame = frame
    for i in range(0, frame.shape[0]):
        for j in range(0, frame.shape[0]):
            yiq_frame[i,j,0] = 0.299*frame[i,j,0] + 0.587*frame[i,j,1] + 0.114*frame[i,j,2]
            yiq_frame[i,j,1] = 0.595716*frame[i,j,0] - 0.274453*frame[i,j,1] + 0.321263*frame[i,j,2]
            yiq_frame[i,j,2] = 0.211456*frame[i,j,0] - 0.533591*frame[i,j,1] + 0.311135*frame[i,j,2]
    return yiq_frame


def butter_bandpass(lowcut, highcut, fs, order=5):
    nyq = 0.5 * fs
    low = lowcut / nyq
    high = highcut / nyq
    b, a = scipy.signal.butter(order, [low, high], btype='band')
    return b, a


def butter_bandpass_filter(data, lowcut, highcut, fs, order=5):
    b, a = butter_bandpass(lowcut, highcut, fs, order=order)
    y = scipy.signal.lfilter(b, a, data, axis=0)
    return y