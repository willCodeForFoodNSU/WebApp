f = open(r'C:\Users\B\Desktop\BE Project\K\filelist.txt', 'r')
for i in f.readlines():
    print i
    copy(i.strip(),r"E:\Images")    