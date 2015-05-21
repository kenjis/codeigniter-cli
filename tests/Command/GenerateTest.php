<?php

namespace Kenjis\CodeIgniter_Cli\Command;

use Aura\Cli\CliFactory;
use Aura\Cli\Status;

class GenerateTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->ci =& get_instance();
        $cli_factory = new CliFactory;
        $context = $cli_factory->newContext($GLOBALS);
        $this->stdio = $cli_factory->newStdio(
            'php://memory',
            'php://memory',
            'php://memory'
        );
        $this->stdout = $this->stdio->getStdout();
        $this->stderr = $this->stdio->getStderr();
        $this->cmd = new Generate($context, $this->stdio, $this->ci);
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

    public function test_migration_generate()
    {
        $migration_path = __DIR__ . '/../Fake/migrations/';
        $this->ci->config->set_item('migration_path', $migration_path);
        $status = $this->cmd->__invoke('migration', 'Test_of_generate_migration');
        $this->assertEquals(0, $status);

        foreach (glob($migration_path . '*_Test_of_generate_migration.php') as $file) {
            unlink($file);
        }
    }

    public function test_migration_generate_sequential()
    {
        $migration_path = __DIR__ . '/../Fake/migrations/';
        $this->ci->config->set_item('migration_path', $migration_path);
        $this->ci->config->set_item('migration_type', 'sequential');
        $status = $this->cmd->__invoke('migration', 'Test_of_generate_migration');
        $this->assertEquals(Status::SUCCESS, $status);

        foreach (glob($migration_path . '*_Test_of_generate_migration.php') as $file) {
            $this->assertContains('001_Test_of_generate_migration', $file);
            unlink($file);
        }
    }

    public function test_migration_cannot_write_to_file()
    {
        $migration_path = __DIR__ . '/../Fake/migrations/not-exist-dir/';
        $this->ci->config->set_item('migration_path', $migration_path);
        $status = $this->cmd->__invoke('migration', 'Test_of_generate_migration');
        $this->assertEquals(Status::FAILURE, $status);

        $this->stderr->rewind();
        $actual = $this->stderr->fread(8192);
        $this->assertContains("Can't write to ", $actual);
        $this->assertContains('Fake/migrations/not-exist-dir/', $actual);
        $this->assertContains('Test_of_generate_migration.php', $actual);
    }
}
