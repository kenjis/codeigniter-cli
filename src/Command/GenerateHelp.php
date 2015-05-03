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

class GenerateHelp extends Help
{
    public function init()
    {
        $this->setSummary('Generate code.');
        $this->setUsage('<migration> <classname>');
        $this->setDescr(
            '<<bold>>generate migration<<reset>>: Generate migration file skeleton.' . PHP_EOL
            . '        eg, generate migration Create_user_table'
        );
    }
}
