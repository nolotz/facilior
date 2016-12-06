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

namespace Nolotz\Facilior\Tests\Foundation;


use Nolotz\Facilior\Foundation\Process\CommandParser;
use PHPUnit\Framework\TestCase;

class CommandParserTest extends TestCase
{
	/**
	 * @var CommandParser
	 */
	protected $commandParser;

	/**
	 * @setUp
	 */
	public function setUp()
	{
		$this->commandParser = new CommandParser();
	}

	public function testParserWillResolveAnArray()
	{

	}
}
