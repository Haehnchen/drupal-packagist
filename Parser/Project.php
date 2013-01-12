<?php

namespace Drupal\PackagistBundle\Parser;

use Composer\Package\Dumper\ArrayDumper;

class Project {

	/**
	 * @var \SimpleXMLElement
	 */
	var $xml;

	protected $terms;

	/**
	 * @var Release[]
	 */
	protected $releases;

	function __construct($xml) {

		if(!$xml instanceof \SimpleXMLElement) {
			$xml = @simplexml_load_string($xml);
		}

		$this->xml = $xml;
	}

	public function getComposerPackages() {

		$repo = array();

		if (!count($releases = $this->getReleases())) {
			return $repo;
		}

		$dumper = new ArrayDumper;
		$converter = new ComposerPackageConvert($this);

		foreach ($releases as $release) {
			$package = $converter->ToComposerPackage($release);
			$repo[$package->getPrettyName()][$package->getPrettyVersion()] = $dumper->dump($package);
		}

		return $repo;
	}

	/**
	 * @return Release[]
	 */
	function getReleases() {

		if ($this->releases) {
			return $this->releases;
		}

		if(!$this->xml->releases) {
			return $this->releases = array();
		}

		foreach ($this->xml->releases->release as $release) {
			$this->releases[] = new Release($release);
		}

		return $this->releases;
	}

	function getTerms() {

		if($this->terms) {
			return $this->terms;
		}

		if(!$this->xml->terms) {
			return $this->terms = array();
		}

		$terms = array();

		foreach($this->xml->terms->term as $term) {
			$terms[(string) $term->name][] = (string) $term->value;
		}

		return $this->terms = $terms;

	}

	function getTerm($name, $default = null) {
		$terms = $this->getTerms();

		return isset($terms[$name]) ? $terms[$name] : $default;
	}

	function getProjectType() {
		$project_terms = $this->getTerm('Projects');

		if(in_array('Modules', $project_terms)) {
			return 'Modules';
		}

		if (in_array('Themes', $project_terms)) {
			return 'Themes';
		}

		return null;
	}

}
