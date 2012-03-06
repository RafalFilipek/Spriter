<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@firma.o2.pl>
 *
 */

namespace Spriter\Positioner;

use Imagine\Image\Point;

class HorizontalPositioner extends BasePositioner
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
			$height =  $element->getHeight() > $height ?  $element->getHeight() : $height;
			$width	+= $element->getWidth() + $padding;
		}

		return array($width, $height + $padding);
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
			$position = new Point($position->getX() + $dimmensions->getWidth() + $padding, $position->getY());
		}

		return $positions;
	}
}
