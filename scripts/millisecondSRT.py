#!/usr/bin/env python2

import sys, re
from os.path import expanduser

args = sys.argv

def main():
    file = args[1];
    duration = args[2];
    generateSRTFile(file, duration);

def generateSRTFile(fileName, duration):
    """
    Generate SRT (subtitle) file for micro second display in video

    fileName: "Movie1.srt"
    duration: "00:12:54"

    NOTE: ignored seconds to make the program more simplified
    """
    # get the hours, min, sec from duration
    time_split = duration.split(':')
    hours = int(time_split[0])
    minutes = int(time_split[1])
    seconds = 59 # int(time_split[2])
    millisecs = [x*10 for x in range(0,100)]

    # open a file to write
    f = open(name=fileName, mode='w', buffering=1)

    # iterate to print to file
    blockNo = 1
    for h in range(hours+1):
        for m in range(minutes+1):
            for s in range(seconds+1):
                for ms in millisecs:
                    f.write(subtitle(h, m, s, ms, blockNo))
                    blockNo += 1
    # close the file
    return f.close()

def subtitle(h, m, s, ms, bn):
    """
    Returns the subtitle block for the given parametes
    h: hours, m: minutes, s: seconds, ms: milli seconds, bn: block number
    """
    block = ""
    block += formatToString(bn) + "\n"
    time_line = formatToString(h)+":"+formatToString(m)+":"+formatToString(s)+","
    block += time_line+formatToString(ms, 3) + " --> " + time_line + \
        formatToString(ms+10 if ms!=990 else 999, 3) + "\n"
    block += "time " + time_line + formatToString(ms ,3) + "\n\n"
    return block

def formatToString(num, length=2):
    """
    Format given number to given length.
    i.e num = 5 and length = 2. Result = "05"
    """
    # number of digits in num
    digits = len(str(num))

    # mathematical equivalent for finding digits
    #n = num
    #digits = 0
    #if n==0:
        #digits = 1
    #else:
        #while n:
            #n = n/10
            #digits += 1

    # find how much shorter is num than length
    if digits >= length:
        strNum = str(num)
    else:
        diff = length-digits
        strNum = ""
        for i in range(diff):
            strNum += "0"
        strNum += str(num)
    # return
    return strNum

if __name__=="__main__":
    main();