<?php
namespace Nolotz\Facilior\Foundation\Process;

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
class CommandParser
{
	/**
	 * @var array
	 */
	protected $regexOrder = [
		'/{([a-zA-Z0-9]+)}/',
		'/{([a-zA-Z0-9.]+)}/'
	];

    /**
     * parse
     *
     * @param       $command
     * @param array $variables
     *
     * @return mixed
     */
    public function parse($command, array $variables) {
        logger('Command template: ' . $command['command']);

		foreach ($this->regexOrder as $regex) {
			$command['command'] = $this->matchByRegex($regex, $command['command'], $variables);
			$command['description'] = $this->matchByRegex($regex, $command['description'], $variables);
		}

        return $command;
    }

	/**
	 * @param $regex
	 * @param $command
	 * @param $variables
	 *
	 * @return mixed
	 */
	protected function matchByRegex($regex, $command, $variables)
	{
		$parsedCommand = $command;

		if(preg_match_all($regex, $command, $placeholders) !== false) {
			foreach(last($placeholders) as $placeholder) {
				$parsedCommand = str_replace('{'.$placeholder.'}', array_get($variables, $placeholder), $parsedCommand);
			}
		}

		return $parsedCommand;
    }

}
