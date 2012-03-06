<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@gmail.com>
 *
 */

namespace Spriter\Console;

use Symfony\Component\Console\Application;
use Spriter\Console\Command\GenerateSpriteCommand;

$loader = require __DIR__.'/../../../vendor/.composer/autoload.php';

$loader->add('Spriter', __DIR__.'/../../../src');

$application = new Application('Spriter', '1.0');

$application->add(new GenerateSpriteCommand('generate'));

$application->run();
