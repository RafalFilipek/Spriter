<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@gmail.com>
 *
 */

namespace Spriter\Positioner;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Spriter\Positioner\AbstractPositioner;
use Spriter\Positioner\PositionedBox;

class PackedPositioner extends AbstractPositioner
{

	protected $limit;

	protected $positions;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(array $options = array())
	{
		if (!isset($options['limit']) || !is_numeric(($options['limit']))) {
			throw new Exception('You must provide limit for width.');
		}
		$this->limit = $options['limit'];
		parent::__construct($options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function calculateSize()
	{
		uasort($this->sizes, function($a, $b) {
			$ah = $a->getHeight();
			$bh = $b->getHeight();
			if ($ah == $bh) return 0;
			return ($ah < $bh) ? 1 : -1;
		});

		$positions = array();
		$point = new Point(0,0);
		$key = array_shift(array_keys($this->sizes));
		$part = new Box($this->limit, $this->sizes[$key]->getHeight());
		$partPoint = new Point(0,0);

		foreach ($this->sizes as $file => $image) {
			if ($part->contains($image, $partPoint)) {
				$element = new PositionedBox($image, $point);
				$point = new Point($point->getX()+$image->getWidth(), $point->getY());
				$partPoint = new Point($partPoint->getX()+$image->getWidth(), 0);
			} else {
				$point = new Point(0, $point->getY() + $part->getHeight());
				$partPoint = new Point(0,0);
				$element = new PositionedBox($image, $point);
				$point = new Point($image->getWidth(), $point->getY());
				$partPoint = new Point($image->getWidth(),0);
				$part = new Box($this->limit, $image->getHeight());
			}
			$positions[$file] = $element;
		}

		$this->positions = $positions;

		$fkey = array_shift(array_keys($this->sizes));
		$lkey = array_pop(array_keys($this->sizes));
		$lastElement = $positions[$key];
		$height = $positions[$fkey]->getBox()->getHeight() + $positions[$fkey]->getPoint()->getY();
		foreach ($positions as $position) {
			$h = $position->getBox()->getHeight() + $position->getPoint()->getY();
			if ($h > $height) $height = $h;
		}

		return array($this->limit, $height);
	}
	/**
	 * {@inheritdoc}
	 */
	public function process()
	{
		$result = array();
		foreach ($this->positions as $file => $element) {
			$result[$file] = $element->getPoint();
		}
		return $result;
	}

}
