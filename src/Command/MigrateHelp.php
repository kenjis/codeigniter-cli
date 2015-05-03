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
        $this->setUsage([
            '            Migrate up to the current version.',
            '<version>   Migrate up to the version.',
            'status      List all migration files and versions.',
            'version     Show migration versions.'
        ]);
        $this->setDescr(
            '<<bold>>migrate<<reset>> command runs the migrations and shows its status.' . PHP_EOL
        );
    }
}
