<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@gmail.com>
 *
 */

namespace Spriter\Positioner;

interface PositionerInterface
{

	/**
	 * Constructor
	 * @param array $options configuration options
	 */
	public function __construct(array $options = array());

	public function setElements(array $elements);

	/**
	 * Method that calculate sprite size
	 * @return Imagine\Image\Box
	 */
	public function calculateSize();

	/**
	 * Calcluate posion for all elements.
	 * @return array array containing elements positions. Key for each element is a corresponding key from $this->elements.
	 * Value is a Imagine\Image\Point instance
	 */
	public function process();
}
