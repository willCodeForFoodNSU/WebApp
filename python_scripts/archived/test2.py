import sys
import shutil

argument1 = sys.argv[1].split( ".")

shutil.copyfile("../python_scripts/my.txt", "../embeddings/new/" + argument1[0] + ".txt")

print(argument1[0])