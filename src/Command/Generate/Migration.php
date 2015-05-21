<?php
/**
 * Part of Cli for CodeIgniter
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-cli
 */

namespace Kenjis\CodeIgniter_Cli\Command\Generate;

use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Aura\Cli\Status;
use Kenjis\CodeIgniter_Cli\Command\Command;
use CI_Controller;

/**
 * @property \CI_Loader $load
 * @property \CI_Config $config
 */
class Migration extends Command
{
    public function __construct(Context $context, Stdio $stdio, CI_Controller $ci)
    {
        parent::__construct($context, $stdio, $ci);
        $this->load->library('migration');
        $this->load->config('migration');
    }

    /**
     * @param string $type
     * @param string $classname
     */
    public function __invoke($type, $classname)
    {
        if ($classname === null) {
            $this->stdio->errln(
                '<<red>>Classname is needed<<reset>>'
            );
            $this->stdio->errln(
                '  eg, generate migration CreateUserTable'
            );
            return Status::USAGE;
        }

        $migration_path = $this->config->item('migration_path');
        $migration_type = $this->config->item('migration_type');

        if ($migration_type === 'sequential') {
            $files = $this->migration->find_migrations();
            if (empty($files)) {
                $version = 0;
            } else {
                end($files);
                $version = key($files);

                // max version
                if ($version == 999) {
                    $this->stdio->errln(
                        "<<red>>The version number is out of range<<reset>>"
                    );
                    return Status::FAILURE;
                }
            }
            $file_path = $migration_path . sprintf('%03d', ++$version) . '_' . $classname . '.php';
        } else {
            $file_path = $migration_path . date('YmdHis') . '_' . $classname . '.php';
        }

        // check file exist
        if (file_exists($file_path)) {
            $this->stdio->errln(
                "<<red>>The file \"$file_path\" already exists<<reset>>"
            );
            return Status::FAILURE;
        }

        // check class exist
        foreach (glob($migration_path . '*_*.php') as $file) {
            $name = basename($file, '.php');

            // use date('YmdHis') so...
            if (preg_match('/^\d{14}_(\w+)$/', $name, $match)) {
                if (strtolower($match[1]) === strtolower($classname)) {
                    $this->stdio->errln(
                        "<<red>>The Class \"$classname\" already exists<<reset>>"
                    );
                    return Status::FAILURE;
                }
            }
        }

        $template = file_get_contents(__DIR__ . '/templates/Migration.txt');
        $search = [
            '@@classname@@',
            '@@date@@',
        ];
        $replace = [
            $classname,
            date('Y/m/d H:i:s'),
        ];
        $output = str_replace($search, $replace, $template);
        $generated = @file_put_contents($file_path, $output, LOCK_EX);

        if ($generated !== false) {
            $this->stdio->outln('<<green>>Generated: ' . $file_path . '<<reset>>');
        } else {
            $this->stdio->errln(
                "<<red>>Can't write to \"$file_path\"<<reset>>"
            );
            return Status::FAILURE;
        }
    }
}
