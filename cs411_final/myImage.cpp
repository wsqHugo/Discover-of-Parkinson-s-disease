#include "myImage.hpp"
#include <opencv2/imgproc/imgproc.hpp>
#include<opencv2/opencv.hpp>
#include <opencv2/highgui/highgui.hpp>
#include <stdio.h>
#include <stdlib.h>
#include <iostream>
#include <string>

using namespace cv;

MyImage::MyImage(){
}

MyImage::MyImage(char* filename){
	//cameraSrc=webCamera;
	cap=VideoCapture(filename);
	//frame_num = (int)cvGetCaptureProperty(cap,CV_CAP_PROP_FRAME_COUNT);
}

