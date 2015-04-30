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
        $stdio = $cli_factory->newStdio();
        $this->cmd = new Seed($context, $stdio, $ci);
        $this->cmd->setSeederPath($this->seeder_path);
    }

    public function test_seed()
    {
        $this->expectOutputString('Table1SeederTable2Seeder');
        $status = $this->cmd->__invoke();
        $this->assertEquals($status, 0);
    }

    public function test_seed_list()
    {
        $GLOBALS['argv'][1] = 'seed';
        $GLOBALS['argv'][2] = '-l';
        $GLOBALS['argc'] = 3;

        $ci =& get_instance();
        $cli_factory = new CliFactory;
        $context = $cli_factory->newContext($GLOBALS);
        $stdio = $cli_factory->newStdio();
        $this->cmd = new Seed($context, $stdio, $ci);
        $this->cmd->setSeederPath($this->seeder_path);

        $status = $this->cmd->__invoke();
        $this->assertEquals($status, 0);
    }
}
