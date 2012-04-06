<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@gmail.com>
 *
 */

namespace Spriter\Positioner;

class PositionedBox {

	protected $box;

	protected $point;

	public function __construct($box, $point)
	{
		$this->box = $box;
		$this->point = $point;
	}

	public function getBox()
	{
		return $this->box;
	}

	public function getPoint()
	{
		return $this->point;
	}

	public function __toString()
	{
		return sprintf("[%s,%s](%s,%s)", $this->box->getWidth(), $this->box->getHeight(), $this->point->getX(), $this->point->getY());
	}

}
