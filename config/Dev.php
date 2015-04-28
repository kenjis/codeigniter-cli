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

class Dev extends Config
{
    public function define(Container $di)
    {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', true);
    }

    public function modify(Container $di)
    {
    }
}
