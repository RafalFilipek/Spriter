<?php

namespace Spriter\Dumper;

interface RuleNameGeneratorInterface {

	/**
	 * Generates name for style rule
	 * @param  [type] $path image path
	 * @return string rule name
	 */
	public function get($path);

}
