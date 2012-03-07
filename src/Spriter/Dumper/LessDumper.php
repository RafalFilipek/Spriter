<?php

namespace Spriter\Dumper;

use Spriter\Dumper\RuleNameGeneratorInterface;

class LessDumper {

	protected $elements;

	protected $fileTemplate = <<<EOF
#sprites {
{rules}
}

EOF;

	protected $template = <<<EOF
	.{name} {
		background-position: -{x}px -{y}px;
	}\n
EOF;

	protected $nameGenerator;

	public function __construct(array $elements, RuleNameGeneratorInterface $nameGenerator)
	{
		$this->elements = $elements;
		$this->nameGenerator = $nameGenerator;
	}

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
