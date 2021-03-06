Under development!
==================

 * No tests
 * `Packed` positioner needs some tweaks

Requirements
============

 * PHP 5.3.6
 * If you want to use OptiPng it must be avaliable in `/usr/bin/optipng`

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

PHAR or old way
---------------

Phar is the best choice if you dont wnat to download stuff and intalling thing. Just [**grab it**](https://github.com/RafalFilipek/Spriter/raw/master/spriter.phar) and use it.

For help execute

```
php spriter.phar help generate
# or
./bin/spriter help generate
```


Example
-------

### Simple `vertical` sprite
```
php spriter.phar generate ~/Php/Project/Images/ --dump-less=~/Php/Project/Styles/sprites.less
# or
./bin/spriter generate ~/Php/Project/Images/ --dump-less=~/Php/Project/Styles/sprites.less
```
### Cool `packed` sprite
```
php spriter.phar generate ~/Php/Project/Images/ --type=packed --dump-less=~/Php/Project/Styles/sprites.less --limit=700
# or
./bin/spriter generate ~/Php/Project/Images/ --type=packed --dump-less=~/Php/Project/Styles/sprites.less --limit=700
```
