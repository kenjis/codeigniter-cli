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

        $this->migration_path = __DIR__ . '/../Fake/migrations/';
        foreach (glob($this->migration_path . '*_Test_of_generate_migration.php') as $file) {
            unlink($file);
        }
    }

    public function test_no_generator()
    {
        $status = $this->cmd->__invoke('not_exists');
        $this->assertEquals(Status::FAILURE, $status);

        $this->stderr->rewind();
        $actual = $this->stderr->fread();
        $expected = 'No such generator class: Kenjis\CodeIgniter_Cli\Command\Generate\Not_exists' . PHP_EOL;
        $this->assertEquals($expected, $actual);
    }

    public function test_migration_no_classname()
    {
        $status = $this->cmd->__invoke('migration');
        $this->assertEquals(Status::USAGE, $status);

        $this->stderr->rewind();
        $actual = $this->stderr->fread();
        $expected = 'Classname is needed' . PHP_EOL
            . '  eg, generate migration CreateUserTable' . PHP_EOL;
        $this->assertEquals($expected, $actual);
    }

    public function test_migration_generate()
    {
        $this->ci->config->set_item('migration_path', $this->migration_path);
        $status = $this->cmd->__invoke('migration', 'Test_of_generate_migration');
        $this->assertEquals(Status::SUCCESS, $status);
    }

    public function test_migration_generate_file_exist()
    {
        $this->ci->config->set_item('migration_path', $this->migration_path);
        $status = $this->cmd->__invoke('migration', 'Test_of_generate_migration');
        $status = $this->cmd->__invoke('migration', 'Test_of_generate_migration');
        $this->stderr->rewind();
        $error = $this->stderr->fread();
        $expected = '_Test_of_generate_migration.php" already exists';
        $this->assertContains($expected, $error);
        $this->assertEquals(Status::FAILURE, $status);
    }

    public function test_migration_generate_class_exist()
    {
        $this->ci->config->set_item('migration_path', $this->migration_path);
        $status = $this->cmd->__invoke('migration', 'Test_of_generate_migration');

        // sleep not to generate the same file name
        sleep(1);
        $status = $this->cmd->__invoke('migration', 'Test_of_generate_migration');
        $this->stderr->rewind();
        $error = $this->stderr->fread();
        $expected = 'The Class "Test_of_generate_migration" already exists' . PHP_EOL;
        $this->assertEquals($expected, $error);
        $this->assertEquals(Status::FAILURE, $status);
    }

    public function test_migration_generate_class_exist_with_diff_case()
    {
        $this->ci->config->set_item('migration_path', $this->migration_path);
        $status = $this->cmd->__invoke('migration', 'Test_of_generate_migration');

        // sleep not to generate the same file name
        sleep(1);
        $status = $this->cmd->__invoke('migration', 'Test_of_Generate_Migration');
        $this->stderr->rewind();
        $error = $this->stderr->fread();
        $expected = 'The Class "Test_of_generate_migration" already exists' . PHP_EOL;
        $this->assertEquals($expected, $error);
        $this->assertEquals(Status::FAILURE, $status);
    }

    public function test_migration_generate_sequential()
    {
        $this->ci->config->set_item('migration_path', $this->migration_path);
        $this->ci->config->set_item('migration_type', 'sequential');
        $status = $this->cmd->__invoke('migration', 'Test_of_generate_migration');
        $this->assertEquals(Status::SUCCESS, $status);

        foreach (glob($this->migration_path . '*_Test_of_generate_migration.php') as $file) {
            $this->assertContains('003_Test_of_generate_migration', $file);
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
        $actual = $this->stderr->fread();
        $this->assertContains("Can't write to ", $actual);
        $this->assertContains('Fake/migrations/not-exist-dir/', $actual);
        $this->assertContains('Test_of_generate_migration.php', $actual);
    }
}
