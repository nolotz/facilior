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


use Monolog\Handler\StreamHandler;

class LoggerFactory
{
    /**
     * Logger constructor.
     */
    public function __construct()
    {
        $this->logger = new \Monolog\Logger('facilior');
        $this->logger->pushHandler(new StreamHandler(FACILIOR_LOG . '/' . FACILIOR_START . '.log'));
    }

    /**
     * Returns logger for logger() function
     *
     * @return \Monolog\Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }
}