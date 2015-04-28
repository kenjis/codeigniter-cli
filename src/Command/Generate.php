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

class Generate extends Command
{
    public function __invoke($type, $classname = null)
    {
        $generator = __NAMESPACE__ . '\\Generate\\' . ucfirst($type);
        if (! class_exists($generator)) {
            $this->stdio->errln(
                '<<red>>No such generator class: ' . $generator . '<<reset>>'
            );
            return Status::FAILURE;
        }

        $command = new $generator($this->context, $this->stdio, $this->ci);
        return $command($type, $classname);
    }
}
