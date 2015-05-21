<?php

namespace Kenjis\CodeIgniter_Cli\Command;

use Aura\Cli\CliFactory;
use Aura\Cli\Status;

class RunTest extends \PHPUnit_Framework_TestCase
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
        $this->cmd = new Run($context, $this->stdio, $ci);
    }

    public function test_run_no_controller()
    {
        $status = $this->cmd->__invoke();
        $this->assertEquals(Status::USAGE, $status);

        $this->stderr->rewind();
        $actual = $this->stderr->fread();
        $expected = 'Controller is needed' . PHP_EOL;
        $this->assertEquals($expected, $actual);
    }

    public function test_run_welcome()
    {
        ob_start();
        $status = $this->cmd->__invoke('welcome');
        ob_end_clean();
        $this->assertEquals(Status::SUCCESS, $status);

        $this->stdout->rewind();
        $actual = $this->stdout->fread();
        $expected = 'php public/index.php welcome';
        $this->assertContains($expected, $actual);
    }
}
