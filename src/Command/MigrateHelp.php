<?php
/**
 * Part of Cli for CodeIgniter
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-cli
 */

namespace Kenjis\CodeIgniter_Cli\Command;

use Aura\Cli\Help;

class MigrateHelp extends Help
{
    public function init()
    {
        $this->setSummary('Runs the migrations.');
        $this->setDescr('Runs the migration classes.');
    }
}
