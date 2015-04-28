<?php
namespace Kenjis\CodeIgniter_Cli;

class CliProjectTest extends \PHPUnit_Framework_TestCase
{
    public function testCli()
    {
        $console = dirname(__DIR__) . '/cli/console.php';
        $actual = shell_exec("php {$console} hello");
        $expect = 'Hello World!' . PHP_EOL;
        $this->assertSame($expect, $actual);
    }
}
