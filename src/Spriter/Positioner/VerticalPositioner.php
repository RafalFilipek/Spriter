<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@gmail.com>
 *
 */

namespace Spriter\Positioner;

use Imagine\Image\Point;

class VerticalPositioner extends BasePositioner
{

	/**
	 * {@inheritdoc}
	 */
	public function calculateSize()
	{
		$height = 0;
		$width = 0;

		$padding = $this->options['padding'] * 2;

		foreach ($this->sizes as $element) {
			$width =  $element->getWidth() > $width ?  $element->getWidth() : $width;
			$height	+= $element->getHeight() + $padding;
		}

		return array($width  + $padding, $height);
	}
	/**
	 * {@inheritdoc}
	 */
	public function process()
	{
		$position = new Point($this->options['padding'], $this->options['padding']);

		$positions = array();

		$padding = $this->options['padding'] * 2;

		foreach ($this->sizes as $file => $dimmensions) {
			$positions[$file] = $position;
			$position = new Point($position->getX(), $position->getY()  + $dimmensions->getHeight() + $padding);
		}

		return $positions;
	}

}
