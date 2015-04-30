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
        $this->stdio = $cli_factory->newStdio(
            'php://memory',
            'php://memory',
            'php://memory'
        );
        $this->stdout = $this->stdio->getStdout();
        $this->stderr = $this->stdio->getStderr();
        $this->cmd = new Generate($context, $this->stdio, $ci);
    }

    public function test_no_generator()
    {
        $status = $this->cmd->__invoke('not_exists');
        $this->assertEquals(Status::FAILURE, $status);

        $this->stderr->rewind();
        $actual = $this->stderr->fread(8192);
        $expected = 'No such generator class: Kenjis\CodeIgniter_Cli\Command\Generate\Not_exists' . PHP_EOL;
        $this->assertEquals($expected, $actual);
    }

    public function test_migration_no_classname()
    {
        $status = $this->cmd->__invoke('migration');
        $this->assertEquals(Status::USAGE, $status);

        $this->stderr->rewind();
        $actual = $this->stderr->fread(8192);
        $expected = 'Classname is needed' . PHP_EOL
            . '  eg, generate migration CreateUserTable' . PHP_EOL;
        $this->assertEquals($expected, $actual);
    }
}
