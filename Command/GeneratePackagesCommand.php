<?php

namespace Drupal\PackagistBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Composer\Json\JsonFile;
use Buzz\Browser;
use Drupal\PackagistBundle\Parser\Project;

class GeneratePackagesCommand extends ContainerAwareCommand {

	protected function configure() {
		$this->setName('espend:drupal:packagist:generator')->setDescription('Packagist generator out of updates.drupal.org');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		/** @var $kernel \Symfony\Component\HttpKernel\Kernel */
		$kernel = $this->getContainer()->get('kernel');
		$package_dir = dirname($kernel->getRootDir()) . '/web/p';

		/** @var $filesystem \Symfony\Component\Filesystem\Filesystem */
		$filesystem = $this->getContainer()->get('filesystem');

		if(!$filesystem->exists($package_dir)) {
			$filesystem->mkdir($package_dir);
		}

		$main_url = 'http://updates.drupal.org/release-history/%s/%d.x';

		$api_versions = array(6,7,8);

		// @TODO: we do we have a full list or a complete version info file
		$projects = array('adaptivetheme','omega', 'zen', 'panels',	'filefield','cck','admin_menu','date','wysiwyg','pathauto','ctools','token','views');

		$browser = new Browser();
		foreach ($api_versions as $version) {

			$output->writeln('change to api version: ' . $version);

			$modules = array();

			foreach ($projects as $project) {

				$output->writeln(sprintf('working on %s-%dx', $project, $version));

				try {

					/** @var $response \Buzz\Message\Response */
					$response = $browser->get(sprintf($main_url, $project, $version));
					$project = new Project($response->getContent());
					$modules += $project->getComposerPackages();

				} catch (\Exception $e) {
					$output->writeln($e->getMessage());
				}

			}

			$writer = new JsonFile($package_dir . '/packages-' . $version . 'x.json');
			$writer->write(array('packages' => $modules));
		}

	}
}