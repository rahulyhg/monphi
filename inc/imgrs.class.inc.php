<?php
########################################################################
# File Name : imgrs.class.inc.php                                      #
# Author(s) :                                                          #
#   Phil Allen - phil@hilands.com                                      #
# Last Edited By :                                                     #
#   phil@hilands.com                                                   #
# Version : 2006061500                                                 #
#                                                                      #
# Copyright :                                                          #
#   Database Include                                                   #
#   Copyright (C) 2005 Philip J Allen                                  #
#                                                                      #
#   This file is free software; you can redistribute it and/or modify  #
#   it under the terms of the GNU General Public License as published  #
#   by the Free Software Foundation; either version 2 of the License,  #
#   or (at your option) any later version.                             #
#                                                                      #
#   This File is distributed in the hope that it will be useful,       #
#   but WITHOUT ANY WARRANTY; without even the implied warranty of     #
#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      #
#   GNU General Public License for more details.                       #
#                                                                      #
#   You should have received a copy of the GNU General Public License  #
#   along with This File; if not, write to the Free Software           #
#   Foundation, Inc., 51 Franklin St, Fifth Floor,                     #
#   Boston, MA  02110-1301  USA                                        #
#                                                                      #
# External Files:                                                      #
#   List of External Files all require/includes                        #
#                                                                      #
# General Information (algorithm) :                                    #
#   Image Resize class for use with GD Library.                        #
#   Resize your jpg, gif, png's with ease                              #
#   Need to add BMP support                                            #
#                                                                      #
# Functions :                                                          #
#                                                                      #
# Classes :                                                            #
#   imgrs                                                              #
#                                                                      #
# CSS :                                                                #
#                                                                      #
# JavaScript :                                                         #
#                                                                      #
# Variable Lexicon :                                                   #
#   String             - $strStringName                                #
#   Array              - $arrArrayName                                 #
#   Resource           - $resResourceName                              #
#   Reference Variable - $refReferenceVariableName  (aka object)       #
#   Integer            - $intIntegerName                               #
#   Boolean            - $boolBooleanName                              #
#   Function           - function_name (all lowercase _ as space)      #
#   Class              - class_name (all lowercase _ as space)         #
#                                                                      #
# Commenting Style :                                                   #
#   # (in boxes) denotes commenting for large blocks of code, function #
#       and classes                                                    #
#   # (single at beginning of line) denotes debugging infromation      #
#       like printing out array data to see if data has properly been  #
#       entered                                                        #
#   # (single indented) denotes commented code that may later serve    #
#       some type of purpose                                           #
#   // used for simple notes inside of code for easy follow capability #
#   /* */ is only used to comment out mass lines of code, if we follow #
#       the above way of code we will be able to comment out entire    #
#       files for major debugging                                      #
#                                                                      #
########################################################################
########################################################################
# imgrs class                                                          #
#   image resize class                                                 #
########################################################################
class imgrs
{
	// dimensions
	var $intMWidth; // max width
	var $intMHeight; // max height
	var $intCWidth; // current width
	var $intCHeight; // current height

	var $intNWidth; // new width
	var $intNHeight; // new height
	var $dblNWidth;
	var $dblNHeight;

	var $dblResizePercent;

	// resize data
	var $boolResizeWidth = false;
	var $boolResizeHeight = false;
	var $strResize;

	var $strSourceFile;
	var $strDestinationFile;

	var $strImageType; // type of image to manipulate.

	var $strError; // error message
	var $arrGDInfo; // gdinfo array

	####################################################################
	# Constructor                                                      #
	####################################################################
	#function imgrs()
	public function __construct()
	{
		$this->arrGDInfo = $this->get_gd_info();
	}
	####################################################################
	# get all data                                                     #
	####################################################################
	function get_all_data()
	{
		$arrReturn = array(
			'max_width' => $this->intMWidth,
			'max_height' => $this->intMHeight,
			'current_width' => $this->intCWidth,
			'current_height' => $this->intCHeight,
			'new_width' => $this->intNWidth,
			'new_height' => $this->intNHeight,
			'bool_resize_width' => $this->boolResizeWidth,
			'bool_resize_height' => $this->boolResizeHeight,
			'resize_percent' => $this->dblResizePercent,
			'resize' => $this->strResize,
			'source_file' => $this->strSourceFile,
			'destination_file' => $this->strDestinationFile,
			'image_type' => $this->strImageType,
			'strError' => $this->strError,
			'gd_info' => $this->arrGDInfo
		);
		return($arrReturn);
	}
	####################################################################
	# Set                                                              #
	####################################################################
	function set_data($arrData)
	{
		$this->intMWidth = $arrData['max_width'];
		$this->intMHeight = $arrData['max_height'];
		$this->strSourceFile = $arrData['source_file'];
		$this->strDestinationFile = $arrData['destination_file'];
		list($this->intCWidth, $this->intCHeight) = getimagesize($this->strSourceFile);
	}
	####################################################################
	# Check Image Type                                                 #
	#   Input Mime type from (upload), and file location to run exif   #
	#   to veryify that it is that type of file                        #
	# boolExifVal use exif_imagetype to validate images default false.. if true use getimage size
	####################################################################
	// $_FILES['upload_file']['type'] == strFileName
	function set_type($strMimeType, $strFilePath, $boolExifVal=false)
	{
		switch($strMimeType)
		{
			//case "image/gif": $strFileType = 'gif'; break;
			case "image/jpeg":
			case "image/pjpeg":
				$this->strImageType = 'jpg';
				if ($this->arrGDInfo['JPG Support'])
				{
					if ($boolExifVal)
					{
						if (exif_imagetype($strFilePath) == IMAGETYPE_JPEG)
						{
							return 1;
						}
						else
						{
							$this->strError = 'Cannot determine file type based off of your browsers mime type.<br />';
							return 0;
						}
					}
					else
					{
						$arrCheckMe = getimagesize($strFilePath);
						if (is_array($arrCheckMe))
						{
							return 1;
						}
						else
						{
							$this->strError = 'Cannot determine file type based off of your browsers mime type.<br />';
							return 0;
						}
					}
				}
				else
				{
					$this->strError = 'GD Library does not support JPEG\'s<br />';
					return 0;
				}
			break;
			case "image/png":
				$this->strImageType = 'png';
				if ($this->arrGDInfo['PNG Support'])
				{
					if ($boolExifVal)
					{
						if (exif_imagetype($strFilePath) == IMAGETYPE_PNG)
						{
							return 1;
						}
						else
						{
							$this->strError = 'Cannot determine file type based off of your browsers mime type.<br />';
							return 0;
						}
					}
					else
					{
						$arrCheckMe = getimagesize($strFilePath);
						if (is_array($arrCheckMe))
						{
							return 1;
						}
						else
						{
							$this->strError = 'Cannot determine file type based off of your browsers mime type.<br />';
							return 0;
						}
					}
				}
				else
				{
					$this->strError = 'GD Library does not support PNG\'s<br />';
					return 0;
				}
			break;
			case "image/gif":
				$this->strImageType = 'gif';
				if ($this->arrGDInfo['GIF Read Support'] && $this->arrGDInfo['GIF Create Support'])
				{
					if ($boolExifVal)
					{
						if (exif_imagetype($strFilePath) == IMAGETYPE_GIF)
						{
							return 1;
						}
						else
						{
							$this->strError = 'Cannot determine file type based off of your browsers mime type.<br />';
							return 0;
						}
					}
					else
					{
						$arrCheckMe = getimagesize($strFilePath);
						if (is_array($arrCheckMe))
						{
							return 1;
						}
						else
						{
							$this->strError = 'Cannot determine file type based off of your browsers mime type.<br />';
							return 0;
						}
					}
				}
				else
				{
					$this->strError = 'GD Library does not support GIF\'s<br />';
					return 0;
				}
			break;
			default: 
				$this->strError = 'File type not supported<br />';
				return 0;
			break;
		}
	}
	########################################################################
	# Function shrink_to_fit($arrDimensions)                               #
	#                                                                      #
	#   Input                                                              #
	#       $arrDimensions                                                 #
	#           max_width -                                                #
	#           max_height -                                               #
	#           current_width -                                            #
	#           current_height -                                           #
	#                                                                      #
	#   Returns                                                            #
	#       $arrResize                                                     #
	#           width - booleon for resize width                           #
	#           height - boolean for resize height                         #
	#           resize - what dimension we sent to routine                 #
	#           percent - dbl of resize percent.                           #
	#           new_height_dbl - double of new height incase you want      #
	#               more accuracy                                          #
	#           new_width_dbl - double of new width incase you want        #
	#               more accuracy                                          #
	#           new_width - new width as integer                           #
	#           new_height - new height as integer                         #
	#                                                                      #
	########################################################################
	#function shrink_to_fit($arrDimensions)
	function shrink_to_fit()
	{
		$arrResize = array();
		// check for 0 values 0 is used as infinint
		if ($this->intMWidth != 0 && $this->intCWidth > $this->intMWidth)
			$this->boolResizeWidth = true;
		else
			$this->boolResizeWidth = false;

		if ($this->intMHeight != 0 && $this->intCHeight >$this->intMHeight)
			$this->boolResizeHeight = true;
		else
			$this->boolResizeHeight = false;
#Debug
#	print_r($arrResize);
		// find out how t resize
		if ($this->boolResizeHeight && $this->boolResizeWidth)
		{
#debug
#	echo 'here';
			// set ratio to decimal format for easier computation.
			$dblSetImgRatio = $this->intMWidth / $this->intMHeight;
			$dblUploadImgRatio = $this->intCWidth / $this->intCHeight;
#Debug
#	echo '<br />'.$dblSetImgRatio.' '.$dblUploadImgRatio.'<br />';
			if ($dblSetImgRatio == $dblUploadImgRatio)
			{
				$this->strResize = 'width';
			}
			else if ($dblSetImgRatio < $dblUploadImgRatio)
			{
				$this->strResize = 'width';
			}
			else if ($dblSetImgRatio > $dblUploadImgRatio)
			{
				$this->strResize = 'height';
			}
		}
		else if ($this->boolResizeHeight)
		{
			$this->strResize = 'height';
		}
		else if ($this->boolResizeWidth)
		{
			$this->strResize = 'width';
		}
		else
		{
			$this->strResize = 'none';
		}
# Debug
#	print_r($arrResize);
		// calculate new size
		if ($this->strResize == 'width')
		{
			$this->dblResizePercent = $this->intMWidth / $this->intCWidth;
			$this->dblNWidth = $this->intMWidth;
			$this->dblNHeight = $this->intCHeight * $this->dblResizePercent;
			$this->intNWidth = (int)$this->dblNWidth;
			$this->intNHeight = (int)$this->dblNHeight;
		}
		else if ($this->strResize == 'height')
		{
			$this->dblResizePercent = $this->intMHeight / $this->intCHeight;
			$this->dblNHeight = $this->intMHeight;
			$this->dblNWidth = $this->intCWidth * $this->dblResizePercent;
			$this->intNHeight = (int)$this->dblNHeight;
			$this->intNWidth = (int)$this->dblNWidth;
		}
		else // this will be of course $arrResize['none']
		{
			$this->intNHeight = $this->intCHeight;
			$this->intNWidth = $this->intCWidth;
		}
		// see if result is 0 if so replace 0 with 1
		
		if ($this->intNWidth == 0) $this->intNWidth = 1;
		if ($this->intNHeight == 0) $this->intNHeight = 1;
		return;
	}
	########################################################################
	# Function resize_image($arrImageResizeData)                           #
	#                                                                      #
	#   Input                                                              #
	#       $arrImageResizeData                                            #
	#           source_file - source image file to resize                  #
	#           source_height - height of source file                      #
	#           source_width - width of source file                        #
	#           new_file - the newly outputted file                        #
	#           new_width - desired width of new file                      #
	#           new_height - desired height of new file                    #
	#           type - type of image jpg, gif, or png                      #
	#                                                                      #
	#   Returns                                                            #
	#       Currently no data returned should have error checking returned #
	#                                                                      #
	#                                                                      #
	#                                                                      #
	########################################################################
	#function resize_image($arrImageResizeData)
	function resize_image()
	{
		// is this a resource/????? gd resource??
		#$resNewImage = imagecreatetruecolor($arrImageResizeData['new_width'], $arrImageResizeData['new_height']);
		$resNewImage = imagecreatetruecolor($this->intNWidth, $this->intNHeight);
		#switch ($arrImageResizeData['type'])
		switch ($this->strImageType)
		{
			case 'gif':
				$resSourceImage = imagecreatefromgif($this->strSourceFile);
				#imagecopyresized($resNewImage, $resSourceImage, 0, 0, 0, 0, $this->intNWidth, $this->intNHeight, $this->intCWidth, $this->intCHeight);
				imagecopyresampled($resNewImage, $resSourceImage, 0, 0, 0, 0, $this->intNWidth, $this->intNHeight, $this->intCWidth, $this->intCHeight);
				imagegif($resNewImage, $this->strDestinationFile);
			break;
			case 'jpg':
				$resSourceImage = imagecreatefromjpeg($this->strSourceFile);
				#imagecopyresized($resNewImage, $resSourceImage, 0, 0, 0, 0, $this->intNWidth, $this->intNHeight, $this->intCWidth, $this->intCHeight);
				imagecopyresampled($resNewImage, $resSourceImage, 0, 0, 0, 0, $this->intNWidth, $this->intNHeight, $this->intCWidth, $this->intCHeight);
				imagejpeg($resNewImage, $this->strDestinationFile);
			break;
			case 'png':
				$resSourceImage = imagecreatefrompng($this->strSourceFile);
				#imagecopyresized($resNewImage, $resSourceImage, 0, 0, 0, 0, $this->intNWidth, $this->intNHeight, $this->intCWidth, $this->intCHeight);
				imagecopyresampled($resNewImage, $resSourceImage, 0, 0, 0, 0, $this->intNWidth, $this->intNHeight, $this->intCWidth, $this->intCHeight);
				imagepng($resNewImage, $this->strDestinationFile);
			break;
/*
			case 'gif':
				$resSourceImage = imagecreatefromgif($arrImageResizeData['source_file']);
				imagecopyresized($resNewImage, $resSourceImage, 0, 0, 0, 0, $arrImageResizeData['new_width'], $arrImageResizeData['new_height'], $arrImageResizeData['source_width'], $arrImageResizeData['source_height']);
				imagegif($resNewImage, $arrImageResizeData['new_file']);
			break;
			case 'jpg':
				$resSourceImage = imagecreatefromjpeg($arrImageResizeData['source_file']);
				imagecopyresized($resNewImage, $resSourceImage, 0, 0, 0, 0, $arrImageResizeData['new_width'], $arrImageResizeData['new_height'], $arrImageResizeData['source_width'], $arrImageResizeData['source_height']);
				imagejpeg($resNewImage, $arrImageResizeData['new_file']);
			break;
			case 'png':
				$resSourceImage = imagecreatefrompng($arrImageResizeData['source_file']);
				imagecopyresized($resNewImage, $resSourceImage, 0, 0, 0, 0, $arrImageResizeData['new_width'], $arrImageResizeData['new_height'], $arrImageResizeData['source_width'], $arrImageResizeData['source_height']);
				imagepng($resNewImage, $arrImageResizeData['new_file']);
			break;
*/
		}
	}
	########################################################################
	# Function get_gd_info                                                 #
	#                                                                      #
	#   Input                                                              #
	#       None                                                           #
	#                                                                      #
	#   Returns                                                            #
	#       Returns same as gd_info with the exception of "not loaded"     #
	#       When the extension cannot be loaded.                           #
	#                                                                      #
	########################################################################
	function get_gd_info()
	{
		if (!extension_loaded('gd'))
		{
			// use @ to hide warning not found for gd.so
			if (!@dl('gd.so'))
			{
				$arrGDInfo = array(
					'GD Version' => 'not loaded',
					'FreeType Support' => null,
					'T1Lib Support' => null,
					'GIF Read Support' => null,
					'GIF Create Support' => null,
					'JPG Support' => null,
					'PNG Support' => null,
					'WBMP Support' => null,
					'XPM Support' => null,
					'XBM Support' => null,
					'JIS-mapped Japanese Font Support' => null
				);
			}
		}
		else
		{
			$arrGDInfo = gd_info();
		}
		return $arrGDInfo;
	}

}
# END class imgrs ######################################################

?>