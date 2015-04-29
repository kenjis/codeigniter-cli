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

use Aura\Cli\Status;

class Run extends Command
{
    public function __invoke($controller = null, $method = null)
    {
        if ($controller === null) {
            $this->stdio->errln('<<red>>Controller is needed<<reset>>');
            return Status::USAGE;
        }

        $argv = $this->context->argv->get();
        array_shift($argv);
        array_shift($argv);
        array_shift($argv);
        array_shift($argv);
        $arguments = implode(' ', $argv);

        $console = FCPATH . 'index.php';
        $this->stdio->outln(
            "<<green>>php {$console} {$controller} {$method} {$arguments}<<reset>>"
        );
        passthru("php {$console} {$controller} {$method}  {$arguments}");
    }
}
