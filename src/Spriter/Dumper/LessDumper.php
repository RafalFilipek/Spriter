<?php

namespace Spriter\Dumper;

use Spriter\Dumper\RuleNameGeneratorInterface;
use Spriter\Dumper\DumperInterface;

class LessDumper implements DumperInterface {

	/**
	 * List of elements where key is an image path an valie if a Imaging\Image\Point instance
	 * @var array
	 */
	protected $elements;

	/**
	 * Rules container template
	 * @var string
	 */
	protected $fileTemplate = <<<EOF
#sprites {
{rules}
}

EOF;


	/**
	 * Single rule template
	 * @var string
	 */
	protected $template = <<<EOF
	.{name} {
		background-position: -{x}px -{y}px;
	}\n
EOF;

	/**
	 * Style rule name generator.
	 * @var Spriter/Dumper/RuleNameGeneratorInterface
	 */
	protected $nameGenerator;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(array $elements, RuleNameGeneratorInterface $nameGenerator)
	{
		$this->elements = $elements;
		$this->nameGenerator = $nameGenerator;
	}

	/**
	 * {@inheritdoc}
	 */
	public function dump()
	{
		$output = '';

		foreach ($this->elements as $path => $position) {
			$name = $this->nameGenerator->get($path);
			$output .= str_replace(array('{name}', '{x}', '{y}'), array($name, $position->getX(), $position->getY()), $this->template);
		}

		$output = str_replace('{rules}', $output, $this->fileTemplate);

		return $output;
	}

}
