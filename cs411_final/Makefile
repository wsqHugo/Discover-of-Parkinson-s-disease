

#PKG_CONFIG_PATH=/usr/local/lib/pkgconfig:${PKG_CONFIG_PATH}
#export PKG_CONFIG_PATH

all:
	g++ -o server get_skin_ratio.cpp myImage.cpp handGesture.cpp roi.cpp  `pkg-config --cflags --libs opencv` 

