<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@gmail.com>
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

	/**
	 * Supported formats
	 * @var array
	 */
	protected $supported = array('gif', 'jpeg', 'png', 'jpg', 'GIF', 'jpeg', 'PNG', 'JPG');

	/**
	 * Avaliable positioners
	 * @var array
	 */
	protected $positioners = array(
		'vertical' => 'Spriter\Positioner\VerticalPositioner',
		'horizontal' => 'Spriter\Positioner\HorizontalPositioner',
	);

	/**
	 * Avaliable dumpers
	 * @var array
	 */
	protected $dumpers = array(
		'css' => 'Spriter\Dumper\CssDumper',
		'less' => 'Spriter\Dumper\LessDumper'
	);

	/**
	 * Avaliable name generators
	 * @var array
	 */
	protected $nameGenerators = array(
		'dash' => 'Spriter\Dumper\DashNameGenerator'
	);

	/**
	 * Path where images ar stored
	 * @var string
	 */
	protected $path;

	/**
	 * Path where sprite image will be generated
	 * @var string
	 */
	protected $outputPath;

	/**
	 * Constructor
	 * @param string $name command name
	 */
	public function __construct($name = null)
	{
		parent::__construct($name);

		$this->setDescription('Komenda generująca sprite');
		$this->addArgument('path', InputArgument::OPTIONAL, 'Path containing images', '.');
		$this->addOption('type', null, InputOption::VALUE_OPTIONAL, 'Positioner type (<info>vertical</info>, <info>horizontal</info>)', 'vertical');
		$this->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Sprite file name', 'sprite.png');
		$this->addOption('output', null, InputOption::VALUE_OPTIONAL, 'Sprite where sprite will be generated. By default it\'s equal <info>path</info>', null);
		$this->addOption('no-optim', null, InputOption::VALUE_NONE, 'If set sprite will not be optimized');
		foreach (array_keys($this->dumpers) as $key) {
			$this->addOption('dump-' . $key, null, InputOption::VALUE_OPTIONAL, sprintf('Path where <info>%s</info> file will be generated.', strtoupper($key)), false);
		}
		$this->addOption('rule-name-style', null, InputOption::VALUE_OPTIONAL, 'Rules names style.', 'dash');
	}

	/**
	 * {@inheritdoc}
	 */
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

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{

		$fileName = $this->outputPath->getRealpath() . DIRECTORY_SEPARATOR . $input->getOption('name');

		@unlink($fileName);

		$finder = new Finder();
		$finder->files()->in($this->path->getRealpath())->notName($fileName)->name('/\.'.implode('|', $this->supported).'$/');

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

		$nameGenerator = new $this->nameGenerators[$input->getOption('rule-name-style')];

		foreach (array_keys($this->dumpers) as $key) {
			$optionName = 'dump-' . $key;
			if($input->getOption($optionName) !== false) {
				$file = new \SplFileObject($input->getOption($optionName), 'w');
				$dumper = new $this->dumpers[$key]($generator->getPositions(), $nameGenerator);
				$file->fwrite($dumper->dump());
			}
		}

	}

}
