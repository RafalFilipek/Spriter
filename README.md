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
	use Spriter\Dumper\LessDumper;
	use Spriter\Dumper\DashNameGenerator;
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

	//Also you can dump CSS/LESS styles for your sprite.
	$dumper = new LessDumper($generator->getPositions(), new DashNameGenerator());
	$file = new \SplFileObject('./less/sprites.less', 'w');
	$file->fwrite($dumper->dump());
```

Console
-------

For help execute

```
./bin/spriter help generate
```

Example

```
./bin/spriter generate ~/Php/Project/Images/ --dump-less ~/Php/Project/Styles/sprites.less
```
