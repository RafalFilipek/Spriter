<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@firma.o2.pl>
 *
 */

namespace Spriter\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Spriter\Generator;
use Assetic\Filter\OptiPngFilter;

class GenerateSpriteCommand extends Command {

	protected $positioners = array(
		'vertical' => 'Spriter\Positioner\VerticalPositioner',
		'horizontal' => 'Spriter\Positioner\HorizontalPositioner',
	);

	protected $path;

	protected $outputPath;

	public function __construct($name = null)
	{
		parent::__construct($name);

		$this->setDescription('Komenda generująca sprite');
		$this->addArgument('path', InputArgument::OPTIONAL, 'Ścieżka do katalogu przechowującego pliki graficzne', '.');
		$this->addOption('type', null, InputOption::VALUE_OPTIONAL, 'Sposób generowania pliku (<info>vertical</info>, <info>horizontal</info>)', 'vertical');
		$this->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Nazwa pliku wyjściowego', 'sprite.png');
		$this->addOption('output', null, InputOption::VALUE_OPTIONAL, 'Ścieżka do katalogu w którym ma zostać wygenerowany plik. Domyślnie wartość argumentu <info>path</info>', null);
		$this->addOption('no-optim', null, InputOption::VALUE_NONE, 'Jeżeli ustawiony obraz nie zostanie zoptymalizowany');
	}

	protected function initialize(InputInterface $input, OutputInterface $output)
	{
		if (($path = $input->getArgument('path')) === '.') {
			$path = getcwd();
		}

		$this->path = new \SplFileInfo($path);

		if ($this->path->isDir() === false) {
			throw new \Exception(sprintf('"%s" is not a directory.',$input->getArgument('path')));
		}

		if ($this->path->isWritable() === false) {
			throw new \Exception(sprintf('"%s" is not writeable.',$input->getArgument('path')));
		}

		if ($input->getOption('output') === null) {
			$this->outputPath = $this->path;
		} else {
			$this->outputPath = new \SplFileInfo($input->getOption('output'));

			if ($this->outputPath->isDir() === false) {
			throw new \Exception(sprintf('"%s" is not a directory.',$input->getOption('output')));
			}

			if ($this->outputPath->isWritable() === false) {
				throw new \Exception(sprintf('"%s" is not writeable.',$input->getOption('output')));
			}
		}

		if (in_array($input->getOption('type'), array_keys($this->positioners)) === false) {
			throw new \Exception(sprintf('Unknown positioner type. Expected %s.', implode(', ', array_keys($this->positioners))));
		}
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{

		$fileName = $this->outputPath->getRealpath() . DIRECTORY_SEPARATOR . $input->getOption('name');

		@unlink($fileName);

		$finder = new Finder();
		$finder->files()->in($this->path->getRealpath())->notName($fileName);

		$generator = new Generator($finder);

		 $filters = array(
			new OptiPngFilter()
		);

		if ($input->getOption('no-optim')) {
			$filters = array();
		}

		$sprite = $generator->generate(new $this->positioners[$input->getOption('type')], $filters);

		$file = new \SplFileObject($fileName, 'w');
		$file->fwrite($sprite);

	}

}
