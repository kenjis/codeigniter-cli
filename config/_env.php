<?php
/**
 * Part of Cli for CodeIgniter
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-cli
 */

// set the mode here only if it is not already set.
// this allows for setting via shell variables, integration testing, etc.
if (! isset($_ENV['AURA_CONFIG_MODE'])) {
    $_ENV['AURA_CONFIG_MODE'] = 'dev';
}
