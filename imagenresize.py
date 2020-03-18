# -*- coding: iso-8859-15
import sys
import os
from PIL import Image

basewidth = 300
img = Image.open(sys.argv[1])
wpercent = (basewidth/float(img.size[0]))
hsize = int((float(img.size[1])*float(wpercent)))
img = img.resize((basewidth,hsize), Image.ANTIALIAS)
img.save('sompic5.jpg') 
statinfo = os.stat('sompic5.jpg')

print '%s'  % ('sompic5.jpg')

""" if len(sys.argv) >= 2:
 print "El textos '%s' tiene %s caracteres" % (sys.argv[1],len(sys.argv[1]))
else:
 print "Necesito un parámetro" """