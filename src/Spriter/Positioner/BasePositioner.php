<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@firma.o2.pl>
 *
 */

namespace Spriter\Positioner;

use Imagine\Image\Box;
use Imagine\Image\Point;

abstract class BasePositioner implements PositionerInterface
{
	/**
	 * Elementy które będą pozycjonowane
	 * @var array
	 */
	protected $elements = array();

	/**
	 * Rozmiary elementów
	 * @var array
	 */
	protected $sizes = array();

	/**
	 * Liczba elementów
	 * @var integer
	 */
	protected $count;

	/**
	 * Opcje konfiguracyjne
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
