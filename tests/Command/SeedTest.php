<?php

namespace Kenjis\CodeIgniter_Cli\Command;

class SeedTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $ci = require_once ROOTPATH . '/ci_instance.php';
    }

    public function createCliKernel()
    {
        return $this->kernel = (new \Aura\Project_Kernel\Factory)->newKernel(
            ROOTPATH,
            'Aura\Cli_Kernel\CliKernel'
        );
    }

    public function test_seed()
    {
        $_SERVER['argv'][1] = 'seed';

        $kernel = $this->createCliKernel();
        $this->expectOutputString('Table1SeederTable2Seeder');
        $status = $kernel();
        $this->assertEquals($status, 0);
    }

    public function test_seed_list()
    {
        $_SERVER['argv'][1] = 'seed';
        $_SERVER['argv'][2] = '-l';

        $kernel = $this->createCliKernel();
        $status = $kernel();
        $this->assertEquals($status, 0);
    }
}
