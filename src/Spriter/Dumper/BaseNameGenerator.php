<?php

namespace Spriter\Dumper;

class BaseNameGenerator implements RuleNameGeneratorInterface {

	public function get($path)
	{
		$object = new \SplFileInfo($path);
		$name = strtolower($object->getBasename());
		return $name;
	}

}
