<?php

namespace Kenjis\CodeIgniter_Cli;

class CliTest extends \PHPUnit_Framework_TestCase
{
    public function test_cli_help()
    {
        $console = ROOTPATH . '/cli';
        $actual = shell_exec("php {$console} help");
        $expect = 'Gets the available commands, or the help for one command.';
        $this->assertContains($expect, $actual);
    }
}
