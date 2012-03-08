<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@gmail.com>
 *
 */

namespace Spriter\Tests\Positioner;

use Symfony\Component\Finder\Finder;
use Spriter\Positioner\VerticalPositioner;

class  VerticalPositionerTest extends \PHPUnit_Framework_TestCase {

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

	public function testCalculateSize()
	{
		$positioner = new VerticalPositioner(array());
		$positioner->setElements($this->images);
		$spriteSize = $positioner->calculateSize();

		$expectedHeight = 0;
		$expectedWidth = 0;

		foreach ($this->images as $image) {
			$size = getimagesize($image);
			$expectedHeight += $size[1] + 20; // default padding
			$expectedWidth = $size[0] > $expectedWidth ? $size[0] : $expectedWidth;
		}
		$expectedWidth += 20; // default padding
		$this->assertEquals($expectedWidth, $spriteSize[0]);
		$this->assertEquals($expectedHeight, $spriteSize[1]);
	}

	public function testCalculateSizeWithCustomPadding()
	{
		$positioner = new VerticalPositioner(array('padding' => 0));
		$positioner->setElements($this->images);
		$spriteSize = $positioner->calculateSize();

		$expectedHeight = 0;
		$expectedWidth = 0;

		foreach ($this->images as $image) {
			$size = getimagesize($image);
			$expectedHeight += $size[1];
			$expectedWidth = $size[0] > $expectedWidth ? $size[0] : $expectedWidth;
		}

		$this->assertEquals($expectedWidth, $spriteSize[0]);
		$this->assertEquals($expectedHeight, $spriteSize[1]);
	}

}
