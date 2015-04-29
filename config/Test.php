<?php
/**
 * Part of Cli for CodeIgniter
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-cli
 */

namespace Kenjis\CodeIgniter_Cli\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Test extends Config
{
    public function define(Container $di)
    {
        $seeder_path =  ROOTPATH . '/vendor/kenjis/codeigniter-cli/tests/Fake/seeds/';
        $di->setter['Kenjis\CodeIgniter_Cli\Command\Seed']['setSeederPath']
            = $seeder_path;
    }

    public function modify(Container $di)
    {
    }
}
