<?php

namespace Spriter\Dumper;

use Spriter\Dumper\RuleNameGeneratorInterface;

class DashNameGenerator implements RuleNameGeneratorInterface {

	/**
	 * {@inheritdoc}
	 */
	public function get($path)
	{
		$info = pathinfo($path);
		$name = strtolower($info['filename']);
		$name = preg_replace('/[^a-zA-Z0-9-]/', '-', $name);
		$name = preg_replace('/-+/', '-', $name);
		return $name;
	}

}
