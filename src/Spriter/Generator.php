<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@gmail.com>
 *
 */

namespace Spriter;

use Symfony\Component\Finder\Finder;
use Spriter\Positioner\PositionerInterface;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Assetic\Asset\StringAsset;

class Generator
{
	/**
	 * Elements that will be merged into sprite
	 * @var array
	 */
	protected $elements;

	/**
	 * Configuration options
	 * @var array
	 */
	protected $options;

	/**
	 * Sprite object
	 * @var Imagine[GD/Imagic]/Image
	 */
	protected $sprite;

	/**
	 * Array containing elements positions.
	 * @var array
	 */
	protected $positions;

	/**
	 * Avaliable image processors
	 * @var array
	 */
	protected $processors = array(
		'gd'		=> 'Imagine\Gd\Imagine',
		'imagic'	=> 'Imagine\Imagic\Imagine',
	);

	/**
	 * Contstructor
	 * @param Finder $finder  Instancja klasy finder przechowująca ścieżki do wszystkich plików, które mają zostać przerobione na sprite.
	 * @param array  $options opcje konfiguracyjne dla generatora
	 */
	public function __construct(Finder $finder, array $options = array())
	{
		$defaults = array(
			'processor' => 'gd'
		);

		$this->options = array_merge($defaults, $options);

		if (in_array($this->options['processor'], array_keys($this->processors)) === false) {
			throw new \Exception(sprintf("Unknown image processor '%s'. Expected: %s", $this->options['processor'], implode(', ', array_keys($this->processors))));
		}

		$this->processor = new $this->processors[$this->options['processor']];

		foreach ($finder as $file)
		{
			$this->elements[] = $file;
		}

	}

	/**
	 * Method that generates sprite image containning all alements.
	 *
	 * @param  PositionerInterface $positioner       Klasa pozycjonująca elementy w pliku
	 * @param  array               $additonalFilters Dodatkowe filtry Assetic
	 * @return string image content
	 */
	public function generate(PositionerInterface $positioner, $additonalFilters = array())
	{
		$positioner->setElements($this->elements);
		list($width, $height) = $positioner->calculateSize();
		$this->sprite = $this->processor->create(new Box($width, $height), new Color('fff', 100));

		$this->positions = $positioner->process();

		foreach ($this->positions as $file => $position) {
			$image = $this->processor->open($file);
			$this->sprite->paste($image, $position);
		}

		if(!empty($additonalFilters)) {
			$asset = new StringAsset($this->sprite->get('png'), $additonalFilters);
			$this->sprite = $asset->dump();
		}

		return $this->sprite;

	}

	/**
	 * Returns elements positions array.
	 * @return array
	 */
	public function getPositions()
	{
		return $this->positions;
	}

}
