<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@gmail.com>
 *
 */

namespace Spriter\Tests\Positioner;

use Symfony\Component\Finder\Finder;
use Spriter\Positioner\AbstractPositioner;

class DummyPositioner extends AbstractPositioner {
	public function calculateSize(){}
	public function process(){}
}

class  AbstractPositionerTest extends \PHPUnit_Framework_TestCase {

	protected $images;

	public function setUp()
	{
		$finder = new Finder();
		$finder->files()->in(__DIR__.'/../Fixtures')->sortByName();

		$elements = array();

		foreach ($finder as $file) {
			$elements[] = $file;
		}

		$this->images = $elements;
	}

	public function testSetElements()
	{

		$positioner = new DummyPositioner(array());
		$positioner->setElements($this->images);

		$reflection = new \ReflectionClass($positioner);
		$elementsProperty = $reflection->getProperty('sizes');
		$elementsProperty->setAccessible(true);
		$sizes = $elementsProperty->getValue($positioner);

		$expected = array(
			'e.png' => array(512, 512),
			'i.png' => array(128, 128),
			'p.png' => array(32, 32),
			'r.png' => array(48, 48),
			's.png' => array(16, 16),
			't.png' => array(256, 256),
		);

		foreach ($sizes as $path => $box) {
			$info = pathinfo($path);
			$this->assertEquals($box->getWidth(), $expected[$info['basename']][0]);
			$this->assertEquals($box->getHeight(), $expected[$info['basename']][1]);
		}
	}

}
