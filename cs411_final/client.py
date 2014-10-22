import socket
import sys

HOST = '127.0.0.1'
PORT = 9999
BUFFER_SIZE = 1024
MESSAGE = sys.argv[1] + " " + sys.argv[2]
#MESSAGE = "media/parkinson2_2.mp4 media/test.txt"
   
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect((HOST, PORT))
s.send(MESSAGE)
data = s.recv(BUFFER_SIZE)
s.close()
print data