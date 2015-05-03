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

class SeedHelp extends Help
{
    public function init()
    {
        $this->setSummary('Seed the database with records.');
        $this->setUsage([
            '',
            '<class>'
        ]);
        $this->setOptions(array(
            'l,list' => "List all seeder files only. With this option, seeding does not run.",
        ));
        $this->setDescr(
            'Seed the database using Seeder class in "application/database/seeds" folder.'
        );
    }
}
