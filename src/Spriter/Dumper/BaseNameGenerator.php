<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@gmail.com>
 *
 */


namespace Spriter\Dumper;

class BaseNameGenerator implements RuleNameGeneratorInterface {

	/**
	 * {@inheritdoc}
	 */
	public function get($path)
	{
		$object = new \SplFileInfo($path);
		$name = strtolower($object->getBasename());
		return $name;
	}

}
