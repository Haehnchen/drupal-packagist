<?php

namespace Drupal\PackagistBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Finder;


class DefaultController extends Controller {

	public function indexAction() {


		/** @var $kernel \Symfony\Component\HttpKernel\Kernel */
		$kernel = $this->get('kernel');
		$package_dir = dirname($kernel->getRootDir()) . '/web/p';

		/** @var $filesystem \Symfony\Component\Filesystem\Filesystem */
		$filesystem = $this->get('filesystem');

		$finder = new Finder();

		/** @var $files \Symfony\Component\Finder\SplFileInfo[] */
		$files = $finder->in($package_dir)->files('json');

		$packages = array();

		foreach($files as $file) {

			$packages['p/' . $file->getFilename()] = array(
				'sha1' => sha1($file->getMTime() . $file->getFilename()),
			);

		}

		return new JsonResponse(array(
			'notify' => '/downloads/%package%',
			'notify_batch' => '/download',
			'packages' => array(),
			'includes' => $packages
		));

	}

	function notifyBatchAction() {
		// @TODO: only for stats
		return new Response();
	}

	function notifyAction($package) {
		// @TODO: only for stats
		return new Response();
	}

}
