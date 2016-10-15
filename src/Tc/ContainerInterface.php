<?php

/**
 * @package Tc;
 * @Author renshan <1005110700@qq.com>
 */
namespace Tc;

interface ContainerInterface
{
	public function register($key, $val);
	public function get($key);
	public function raw($key);
	public function keys();
	public function exists($key);

}

?>
