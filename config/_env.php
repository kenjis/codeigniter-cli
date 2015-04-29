<?php

/**
 * Part of Cli for CodeIgniter
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-cli
 */

if (isset($_ENV['CI_ENV'])) {
    switch ($_ENV['CI_ENV']) {
        case 'development':
            $_ENV['AURA_CONFIG_MODE'] = 'dev';
            break;
        case 'testing':
            $_ENV['AURA_CONFIG_MODE'] = 'test';
            break;
        case 'production':
            $_ENV['AURA_CONFIG_MODE'] = 'prod';
            break;
        default:
            $_ENV['AURA_CONFIG_MODE'] = 'dev';
    }
}

// set the mode here only if it is not already set.
// this allows for setting via shell variables, integration testing, etc.
if (! isset($_ENV['AURA_CONFIG_MODE'])) {
    $_ENV['AURA_CONFIG_MODE'] = 'dev';
}
