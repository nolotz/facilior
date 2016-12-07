<?php
/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Noah-Jerome Lotzer <n.lotzer@neusta.de>, Neusta GmbH
 *
 *  All rights reserved
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace Nolotz\Facilior\Foundation;


class Container
{
	/**
	 * @var Container|null
	 */
	protected static $instance;

	/**
	 * @return Container
	 */
	public static function getInstance() {
		if (self::$instance === null)
			self::$instance = new static;

		return self::$instance;
	}

	/**
	 * @var array
	 */
	protected $instances = [];

	/**
	 * Container constructor.
	 */
	private function __construct() {}

	/**
	 * @param $abstract
	 *
	 * @return \Illuminate\Container\Container|mixed|null
	 */
	public function make($abstract, $parameters = array())
	{
		$instance = null;
		if (!isset($this->instances[$abstract])) {
			if (class_exists($abstract)) {
				$instance = app($abstract, $parameters);
				$this->instances[$abstract] = $instance;
			}
		} else {
			$instance = $this->instances[$abstract];
		}

		return $instance;
	}
}
