<?php

namespace Kenjis\CodeIgniter_Cli\Command;

require_once __DIR__ . '/../Fake/Command/Test.php';

use Aura\Cli\CliFactory;
use Aura\Cli\Status;

class CommandTest extends \PHPUnit_Framework_TestCase
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
        $this->cmd = new Test($context, $this->stdio, $ci);
    }

    public function test_no_property()
    {
        $this->setExpectedException('RuntimeException');
        $status = $this->cmd->__invoke();
    }
}
