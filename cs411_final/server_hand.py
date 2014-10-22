import sys
import subprocess

sys.path.append('/usr/local/lib/python2.7/site-packages')

from socket import *
import thread

BUFF = 1024
HOST = '127.0.0.1'# must be input parameter @TODO
PORT = 8866 # must be input parameter @TODO

def handler(clientsock, addr):
	filename = clientsock.recv(BUFF)
	print repr(addr) + ' recv:' + repr(filename)
	process = subprocess.Popen(["./server " + filename], shell=True,
                           stdout=subprocess.PIPE, 
                           stderr=subprocess.PIPE)
	out, err = process.communicate()
	print out
	#freq = str(subprocess.PIPE)
	freq = str(out)
	clientsock.send(freq)
	print repr(addr) + ' sent:' + repr(freq)
	clientsock.close()
	print addr, "- closed connection" #log on console

if __name__=='__main__':
	ADDR = (HOST, PORT)
	serversock = socket(AF_INET, SOCK_STREAM)
	serversock.setsockopt(SOL_SOCKET, SO_REUSEADDR, 1)
	serversock.bind(ADDR)
	serversock.listen(5)
	while 1:
		print 'waiting for connection... listening on port', PORT
		clientsock, addr = serversock.accept()
		print '...connected from:', addr
		thread.start_new_thread(handler, (clientsock, addr))




#show_frequencies('media/face.mp4')
#eulerian_magnification('media/face.mp4', image_processing='gaussian', pyramid_levels=3, freq_min=50.0 / 60.0, freq_max=1.0, amplification=50)
#eulerian_magnification(video_filename, image_processing='gaussian', pyramid_levels=4, freq_min=0.833, freq_max=1, amplification=50, pyramid_levels=4,lambda_c=0.4, wf_file)
#show_frequencies('media/parkinson_1.mp4')
#eulerian_magnification('media/parkinson2_2.mp4', image_processing='laplacian', pyramid_levels=4, freq_min=2, freq_max=10, amplification=15)
