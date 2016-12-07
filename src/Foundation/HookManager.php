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


use Nolotz\Facilior\Foundation\Process\ProcessFactory;

class HookManager
{
	/**
	 * @var array
	 */
	protected $hooks = [];

	/**
	 * HookManager constructor.
	 */
	public function __construct()
	{
		$this->getDefaultHooks();
		$this->getProjectHooks();
	}

	/**
	 * @return void
	 */
	public function getProjectHooks()
	{
		if (file_exists(FACILIOR_ROOT . '/hooks.json') && is_readable(FACILIOR_ROOT . '/hooks.json')) {
			foreach ((array) json_decode(file_get_contents(FACILIOR_ROOT . '/hooks.json'), true) as $key => $hooks) {
				foreach ($hooks as $hook) {
					$this->hooks[$key][] = $hook;
				}
			}
		}
	}

	/**
	 * @return void
	 */
	protected function getDefaultHooks()
	{
		$this->hooks = array_merge($this->hooks, Config::get('hooks'));
	}

	/**
	 * @param $hook
	 *
	 * @throws \Exception
	 */
	public function fire($hook)
	{
		if (!isset($this->hooks[$hook])) {
			throw new \Exception('Hook '.$hook.' not found');
		}

		foreach ($this->hooks[$hook] as $command) {
			ProcessFactory::create($command, []);
		}
	}
}
