# phpImageThumbnailGenerator
php image thumbnail generator

#  Author:    Kiumars Babolhavaeji
#  Version:   1.1.2
#  Purpose:   Provide tools for image manipulation using GD library
#  Param In:  See functions.
#  Param Out: Produces a resized image
#  Requires : Requires PHP GD library.
# Compatibility: php 5.*, 7.* , 8.*
#  Usage Example:
#               require_once 'phpImageThumbnailGenerator.php';
#               $imageObj = new phpImageThumbnailGenerator('/samplePhoto.jpg');
#               $imageObj->generateThumbnail('your/real/path/to/saved/thumbnails/', 300, 300);
#
#  Supported file types include: jpg, png, gif
