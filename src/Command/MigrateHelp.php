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
        $this->setUsage('[files|curver]');
        $this->setDescr(
            '<<bold>>migrate<<reset>>: Migrate up to the current version.' . PHP_EOL
            . '    <<bold>>migrate status<<reset>>: List all migration files and versions.' . PHP_EOL
            . '    <<bold>>migrate version<<reset>>: Show migration versions.' . PHP_EOL
        );
    }
}
