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

use Aura\Cli\Status;
use Kenjis\CodeIgniter_Cli\Command\Command;

class Migration extends Command
{
    public function __invoke($type, $classname)
    {
        if ($classname === null) {
            $this->stdio->errln(
                '<<red>>Classname is needed<<reset>>'
            );
            return Status::USAGE;
        }

        $this->ci->load->config('migration');
        $migration_path = $this->ci->config->item('migration_path');
        $file_path = $migration_path . date('YmdHis') . '_' . $classname . '.php';

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
        file_put_contents($file_path, $output, LOCK_EX);

        $this->stdio->outln('<<green>>Generated: ' . $file_path . '<<reset>>');
    }
}
