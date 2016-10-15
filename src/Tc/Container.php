<?php

/**
 * @package Tc;
 * @Author renshan <1005110700@qq.com>
 */

namespace Tc;

use Tc\Exception\ServiceNotFoundException;
use Tc\Exception\ServiceConflictException;
use Tc\Exception\ParameterException;

class Container implements ContainerInterface
{
	private $services = [];

	public function register($key, $map, $parameters = null)
	{
		if ($this->exists($key)) {
			throw new ServiceConflictException("Service {$key} is conflict!");
		}

		$service = [];
		$service['key'] = $key;
		$service['map'] = $map;
		$service['parameters'] = $parameters;
		$service['instance'] = null;

		$this->services[$key] = $service;
	}

	public function get($key)
	{
		if (!$this->exists($key)) {
			throw new ServiceNotFoundException("Service {$key} dose not exists");
		}	

		if ($this->services[$key]['instance'] == null) {
			$this->services[$key]['instance'] = $this->getInstance($key);
		}

		return $this->services[$key]['instance'];
	}

	public function raw($key)
	{
		return $this->getInstance($key);
	}

	public function keys()
	{
		return array_keys($this->services);
	}

	public function exists($key)
	{
		return array_key_exists($key, $this->services);
	}

	private function getInstance($key)
	{
		/**
		 * Get service's map class and construcor
		 */
		$refClass = new \ReflectionClass($this->services[$key]['map']);
		$refConstructor = $refClass->getConstructor();

		/**
		 * If it's a non-constructor class
		 */
		if ($refConstructor == null) {
			return new $this->services[$key]['map'];
		}

		/**
		 * Get constructor's parameters info
		 */
		$refParameters = $refConstructor->getParameters();


		/**
		 * Constructor really do not need any parameters
		 */
		if (count($refParameters) == 0) {
			return new $this->service[$key]['map'];
		}

		/**
		 * The service's constructor parameter need more 
		 */
		if (count($refParameters) > count($this->services[$key]['parameters'])) {
			throw new ParameterException("Service class {$this->services[$key]['map']} require parameter to be construct");
		}

		$gaveParameters = $this->services[$key]['parameters'];

		foreach ($gaveParameters as $_key => $parameter) {
			if ($parameter[0] == '@') {
				$gaveParameters[$_key] = $this->get(substr($parameter, 1, strlen($parameter)));
			}
		}	

		return $refClass->newInstanceArgs($gaveParameters);
	}
}

?>
