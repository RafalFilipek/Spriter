Under development!
==================

 * Some messages are not translated.
 * No tests

Installation
============

```
git clone https://RafalFilipek@github.com/RafalFilipek/Spriter.git
cd Spriter
curl -s http://getcomposer.org/installer | php
php composer.phar install
```

Usage
=====

In project
----------

```
<?php
	use Symfony\Component\Finder\Finder;
	use Spriter\Generator;
	use Spriter\Positioner\VerticalPositioner;
	use Assetic\Filter\OptiPngFilter;

	$finder = new Finder();
	$finder->files()->in('./images');

	$generator = new Generator($finder);
	 $filters = array(
		new OptiPngFilter(),
	);

	$sprite = $generator->generate(new VerticalPositioner(), $filters);

	$file = new \SplFileObject('./images/sprite.png', 'w');
	$file->fwrite($sprite);
```

Console
-------

```
./bin/spriter generate
```

For help

```
./bin/spriter help generate
```
