#!/usr/bin/python

# Module: Habitats
# File: /modules/habitats/maps.py
# Developer: Indy

import sys , os , Image , ImageDraw

def imageToPNG( image ) :
	image.save( 'temp.png' )
	fp = open( 'temp.png' )
	binary = fp.read()
	fp.close()
	os.remove( 'temp.png' )
	return binary

def createMap( filename ) :
	return Image.open( filename )

class Coordinates( object ) :
	
	def __init__( self , x , y ) :
		self.x = x
		self.y = y

class Circle( object ) :
	
	def __init__( self , center , radius , color ) :
		self.center = center
		self.radius = radius
		self.color = color

def transformCircle( circle , first , second ) :
	return Circle( Coordinates( circle.center.x * second.x + first.x , circle.center.y * second.y + first.y ) , circle.radius + ( second.x + second.y ) / 2 , circle.color )

def habitats( filename , circles , first , second ) :
	for index , circle in enumerate( circles ) :
		circles[ index ] = transformCircle( circle , first , second )
	image = createMap( filename )
	draw = ImageDraw.Draw( image )
	for circle in circles :
		draw.ellipse( ( circle.center.x - circle.radius , circle.center.y - circle.radius , circle.center.x + circle.radius , circle.center.y + circle.radius ) , circle.color )
		# TODO: opacity
	return imageToPNG( image )

c = True
filename = raw_input()
if filename :
	if not os.path.isfile( filename ) :
		sys.stderr.write( 'filename %s could not be found\n' % filename )
		sys.exit( 1 )
	c = False
coordinates = raw_input().split( ',' )
coordinates = map( float , coordinates )
first = Coordinates( *coordinates[ : 2 ] )
second = Coordinates( *coordinates[ 2 : ] )
N = input()
circles = []
for i in range( N ) :
	sl = raw_input().split( ',' )
	x , y , radius = map( int , sl[ : 3 ] )
	if len( sl ) == 4 :
		color = tuple( [ int( num ) for num in sl[ 3 ].split( '.' ) ] )
	circles.append( Circle( Coordinates( x , y ) , radius , color ) )
if c :
	for circle in circles :
		sys.stdout.write( '%d,%d,%d\n' % ( circle.center.x , circle.center.y , circle.radius ) )
else :
	sys.stdout.write( habitats( filename , circles , first , second ) )
