<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@gmail.com>
 *
 */

namespace Spriter;

use Symfony\Component\Finder\Finder;

class Compiler
{

	public function compile($pharFile = 'spriter.phar')
	{
		if (file_exists($pharFile)) {
			unlink($pharFile);
		}

		$phar = new \Phar($pharFile, 0, 'spriter.phar');
		$phar->setSignatureAlgorithm(\Phar::SHA1);

		$phar->startBuffering();

		$finder = new Finder();
		$finder->files()
			->ignoreVCS(true)
			->name('*.php')
			->notName('Compiler.php')
			->in(__DIR__.'/..')
			->in(__DIR__.'/../../vendor/symfony/class-loader/Symfony/Component/ClassLoader')
			->in(__DIR__.'/../../vendor/symfony/console/Symfony/Component/Console')
			->in(__DIR__.'/../../vendor/symfony/finder/Symfony/Component/Finder')
			->in(__DIR__.'/../../vendor/symfony/process/Symfony/Component/Process')
			->in(__DIR__.'/../../vendor/kriswallsmith/assetic/src/Assetic')
			->in(__DIR__.'/../../vendor/imagine/Imagine/lib/Imagine')
		;

		foreach ($finder as $file) {
			$this->addFile($phar, $file);
		}

		$this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/.composer/autoload.php'));
		$this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/.composer/ClassLoader.php'));
		$this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/.composer/autoload_namespaces.php'));

		$phar->setDefaultStub(new \SplFileInfo('../../src/Spriter/Console/Spriter.php'));

		$phar->stopBuffering();

		unset($phar);
	}

	protected function addFile($phar, $file, $strip = true)
	{
		$path = str_replace(dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR, '', $file->getRealPath());
		$content = file_get_contents($file);
		if ($strip) {
			$content = self::stripComments($content);
		}

		$phar->addFromString($path, $content);
	}

	static public function stripComments($source)
	{
		if (!function_exists('token_get_all')) {
			return $source;
		}

		$output = '';
		foreach (token_get_all($source) as $token) {
			if (is_string($token)) {
				$output .= $token;
			} elseif (in_array($token[0], array(T_COMMENT, T_DOC_COMMENT))) {
				$output .= str_repeat("\n", substr_count($token[1], "\n"));
			} else {
				$output .= $token[1];
			}
		}

		return $output;
	}
}
