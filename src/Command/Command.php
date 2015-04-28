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

use Aura\Cli\Stdio;
use Aura\Cli\Context;
use CI_Controller;

abstract class Command
{
    public function __construct(Context $context, Stdio $stdio, CI_Controller $ci)
    {
        $this->context = $context;
        $this->stdio = $stdio;
        $this->ci = $ci;

        $this->ci->load->database();
        $this->db = $this->ci->db;
        $this->ci->load->dbforge();
        $this->dbforge = $this->ci->dbforge;
    }
}
