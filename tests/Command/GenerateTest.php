<?php

namespace Kenjis\CodeIgniter_Cli\Command;

use Aura\Cli\CliFactory;
use Aura\Cli\Status;

class GenerateTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $ci =& get_instance();
        $cli_factory = new CliFactory;
        $context = $cli_factory->newContext($GLOBALS);
        $stdio = $cli_factory->newStdio();
        $this->cmd = new Generate($context, $stdio, $ci);
    }

    public function test_no_generator()
    {
        $status = $this->cmd->__invoke('not_exists');
        $this->assertEquals($status, Status::FAILURE);
    }

    public function test_migration_no_classname()
    {
        $status = $this->cmd->__invoke('migration');
        $this->assertEquals($status, Status::USAGE);
    }
}
