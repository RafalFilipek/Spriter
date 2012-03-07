<?php

namespace Spriter\Dumper;

use Spriter\Dumper\RuleNameGeneratorInterface;

interface DumperInterface {

	/**
	 * Constructor
	 * @param array                      $elements      array of elements
	 * @param RuleNameGeneratorInterface $nameGenerator Style rule name generator
	 */
	public function __construct(array $elements, RuleNameGeneratorInterface $nameGenerator);

	/**
	 * This method generates css file contnet
	 * @return string file content
	 */
	public function dump();

}
