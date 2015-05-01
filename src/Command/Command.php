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
use RuntimeException;

abstract class Command
{
    protected $context;
    protected $stdio;
    protected $ci;
    protected $db;
    protected $dbforge;

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

    public function __get($property)
    {
        if (! property_exists($this->ci, $property)) {
            ob_start();
            var_dump(debug_backtrace());
            $backtrace = ob_get_clean();
            file_put_contents(ROOTPATH . '/tmp/backtrace.log', $backtrace, LOCK_EX);
            $this->stdio->errln(
                '<<red>>No such property: ' . $property . ' in CodeIgniter instance<<reset>>'
            );
            $this->stdio->errln('Backtrace was saved in tmp/backtrace.log');
            throw new RuntimeException('Property does not exist');
        }

        return $this->ci->$property;
    }
}
