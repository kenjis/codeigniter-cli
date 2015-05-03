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

class RunHelp extends Help
{
    public function init()
    {
        $this->setSummary('Run controller.');
        $this->setUsage('<controller> [<method> [<arg1> [<arg2> [...]]]]');
        $this->setUsage([
            '<controller>',
            '<controller> <method>',
            '<controller> <method> <arg1>',
            '<controller> <method> <arg1> <arg2> [...]',
        ]);
        $this->setDescr(
            'Run controller via the CLI.'
        );
    }
}
