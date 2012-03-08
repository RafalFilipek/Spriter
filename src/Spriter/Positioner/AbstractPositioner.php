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

abstract class AbstractPositioner implements PositionerInterface
{
	/**
	 * Sprite elements
	 * @var array
	 */
	protected $elements = array();

	/**
	 * Elements dimensions
	 * @var array
	 */
	protected $sizes = array();

	/**
	 * Number of elements
	 * @var integer
	 */
	protected $count;

	/**
	 * Configuration options
	 * @var array
	 */
	protected $options;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(array $options = array())
	{
		$defaults = array(
			'padding' => 10
		);
		$this->options = array_merge($defaults, $options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setElements(array $elements)
	{
		$this->elements = $elements;
		$this->count = count($elements);

		foreach ($elements as $element)
		{
			list($width, $height) = getimagesize($element->getRealpath());
			$this->sizes[$element->getRealpath()] = new Box($width, $height);
		}
	}

}
