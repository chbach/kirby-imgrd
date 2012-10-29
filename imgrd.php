<?php
/**
 * imgrd creates a nice g+ style grid for your images.
 *
 * @author Christoph Bach <info@christoph-bach.net>
 *
**/

function imgrd ($images, $width, $margin, $images_per_row) {
	$imgrd = new Imgrd($images, $width, $margin, $images_per_row);
	$imgrd->grid();
}

class Imgrd {
	private $images = array();
	private $gridWidth = 0;
	private $gridMargin = 0;
	private $originals = array();

	private $imgWidth = 0;
	private $imgHeight = 0;

	function __construct($images, $width, $margin, $images_per_row) {
		$this->images = $images;
		$this->gridWidth = $width + $margin;
		$this->gridMargin = $margin;

		// prevent division by zero
		$images_per_row = ($images_per_row > 1)? $images_per_row : 2;

		// heuristic base image width
		$this->imgWidth = (int) ($this->gridWidth / ($images_per_row - 1));
		// assuming most pictures have a proportion of 4:3
		$this->imgHeight = (int) ($this->imgWidth / 4 * 3);
	}

	/**
	 * This function will generate the grid's rows. Therefore it uses
	 * a rough estimation generated by fitWidth() and fitHeight() to
	 * calculate the number of images per row.
	**/
	public function grid() {
		$row = array();
		$rowWidth = 0;
		$i = 0;

		$images = $this->images;

		foreach($images as $img) {
			$this->originals[$i] = clone $img;

			// scale the image to nice base size
			if ($this->getOrientation($img) < 1) {
				$img->fitWidth($this->imgWidth, true);
			} else {
				$img->fitHeight($this->imgHeight, true);
			}

			$rowWidth = $rowWidth + $img->width() + $this->gridMargin;
			$row[$i] = $img;

			// begin new row if current one is overfull
			if ($rowWidth-$this->gridMargin >= $this->gridWidth
					|| $i == $images->count()-1) {
				$this->fitRow($row, $rowWidth);
				$row = array();
				$rowWidth = 0;
			}

			$i++;
		}
	}

	/**
	 * This function will make the images of a row the same height
	 * and it will scale all images so that they exactly fit in a row
	 *
	 * @param $row
	 *        Array of image objects that will fill a row. The row's width should
	 *        be equal or larger compared to the grid's width. Otherwise it will
	 *        assume that this is the last row of the grid and will just adjust
	 *        the images' height.
	 * @param $rowWidth
	 * 				The width of all images together
	**/
	private function fitRow($row, $rowWidth) {
		$margin = (count($row)) * $this->gridMargin;

		if ($rowWidth >= 0.9 * $this->gridWidth)
			$scaleFactor = ($this->gridWidth - $margin) / ($rowWidth - $margin);
		else {
			$scaleFactor = 1;
		}

		$fixedHeight = 0;
		$realWidth = 0;
		$i = 0;

		// scale images down, so that they will fit
		foreach ($row as $key => $img) {
			$newWidth = (int) ($img->width() * $scaleFactor);
			$img->fitWidth($newWidth, true);

			// create fixed height so that every image has the same height
			if ($fixedHeight > 0)
				$img->info()->height = $fixedHeight;
			else
				$fixedHeight = $img->height();

			// last element will be cropped to pixel-perfect size
			if ($i == count($row)-1 && $scaleFactor < 1) {
				$fittedWidth = $this->gridWidth - $margin - $realWidth;
				$img->info()->width = $fittedWidth;
			}

			$realWidth = $realWidth + $img->width();
			$i++;
		}

		$this->printRow($row);
	}

	/**
	 * Prints a row.
	 *
	 * @param $row
	 *        An array of image objects
  **/
	private function printRow($row) {
		foreach ($row as $key => $img) {
			$options = array(
					'width' => $img->width(),
					'height' => $img->height(),
					'crop' => true
				);

			// modifiy markup here
			echo "<a href=\"".$this->originals[$key]->url()."\"
			      class=\"fancybox\" rel=\"gallery\">\n";
			echo thumb($this->originals[$key], $options);
			echo "</a>\n";
		}
	}

	/**
	 * @param $img
	 *        the image object
	 * @return
	 *        0 for landscape
	 *        1 for portrait
	 *
	**/
	private function getOrientation($img) {
		return ($img->width() > $img->height())? 0 : 1;
	}
}
?>
