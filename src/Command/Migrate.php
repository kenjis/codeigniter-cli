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

class Migrate extends Command
{
    public function __invoke()
    {
        $this->load->library('migration');

        if ($this->migration->current() === false) {
            $this->stdio->errln(
                '<<red>>' . $this->migration->error_string() . '<<reset>>'
            );
            return Status::FAILURE;
        }
    }
}
