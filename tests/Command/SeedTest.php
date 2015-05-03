<?php

namespace Kenjis\CodeIgniter_Cli\Command;

use Aura\Cli\CliFactory;
use Aura\Cli\Status;

class SeedTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->seeder_path = ROOTPATH . '/vendor/kenjis/codeigniter-cli/tests/Fake/seeds/';

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
        $this->cmd = new Seed($context, $this->stdio, $ci);
        $this->cmd->setSeederPath($this->seeder_path);
    }

    public function test_seed()
    {
        $this->expectOutputString('Table1SeederTable2Seeder');
        $status = $this->cmd->__invoke();
        $this->assertEquals(0, $status);

        $this->stdout->rewind();
        $actual = $this->stdout->fread(8192);
        $expected = 'Seeded: Table1Seeder' . PHP_EOL . 'Seeded: Table2Seeder' . PHP_EOL;
        $this->assertEquals($expected, $actual);
    }

    public function test_seed_specific_class()
    {
        $this->expectOutputString('Table1Seeder');
        $status = $this->cmd->__invoke('Table1Seeder');
        $this->assertEquals(0, $status);

        $this->stdout->rewind();
        $actual = $this->stdout->fread(8192);
        $expected = 'Seeded: Table1Seeder' . PHP_EOL;
        $this->assertEquals($expected, $actual);
    }

    public function test_seed_list()
    {
        $GLOBALS['argv'][1] = 'seed';
        $GLOBALS['argv'][2] = '-l';
        $GLOBALS['argc'] = 3;

        $ci =& get_instance();
        $cli_factory = new CliFactory;
        $context = $cli_factory->newContext($GLOBALS);
        $this->cmd = new Seed($context, $this->stdio, $ci);
        $this->cmd->setSeederPath($this->seeder_path);

        $status = $this->cmd->__invoke();
        $this->assertEquals(0, $status);

        $this->stdout->rewind();
        $actual = $this->stdout->fread(8192);
        $this->assertContains('Table1Seeder', $actual);
        $this->assertContains('Table2Seeder', $actual);
    }
}
