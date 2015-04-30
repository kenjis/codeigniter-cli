<?php

namespace Kenjis\CodeIgniter_Cli\Command;

use Aura\Cli\CliFactory;
use Aura\Cli\Status;

class MigrateTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpbeforeClass()
    {
        $ci =& get_instance();
        $ci->dbforge->drop_table('bbs', true);
        $ci->dbforge->drop_table('captcha', true);
        $ci->dbforge->drop_table('category', true);
        $ci->dbforge->drop_table('product', true);
    }

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
        $this->cmd = new Migrate($context, $this->stdio, $this->ci);
        $this->ci->config->set_item('migration_version', 20150429110003);
    }

    public function test_command_not_exists()
    {
        $status = $this->cmd->__invoke('command-not-exists');
        $this->assertEquals(Status::USAGE, $status);

        $this->stderr->rewind();
        $actual = $this->stderr->fread(8192);
        $expected = 'No such command: command-not-exists' . PHP_EOL;
        $this->assertEquals($expected, $actual);
    }

    public function test_migrate()
    {
        $status = $this->cmd->__invoke();
        $this->assertEquals(0, $status);
    }

    public function test_status()
    {
        $status = $this->cmd->__invoke('status');
        $this->assertEquals(0, $status);

        $this->stdout->rewind();
        $actual = $this->stdout->fread(8192);
        $this->assertContains('20150429090001_Create_bbs.php', $actual);
        $this->assertContains('20150429110003_Create_category.php (current/database)', $actual);
    }

    public function test_status_current_not_equals_database()
    {
        $this->ci->config->set_item('migration_version', 20150429120004);
        $status = $this->cmd->__invoke('status');
        $this->assertEquals(0, $status);

        $this->stdout->rewind();
        $actual = $this->stdout->fread(8192);
        $this->assertContains('20150429110003_Create_category.php (database)', $actual);
        $this->assertContains('20150429120004_Create_product.php (current)', $actual);
    }

    public function test_version()
    {
        $status = $this->cmd->__invoke('version');
        $this->assertEquals(0, $status);

        $this->stdout->rewind();
        $actual = $this->stdout->fread(8192);
        $this->assertContains(' current: 20150429110003 (in config/migration.php)', $actual);
        $this->assertContains('database: 20150429110003 (in database table)', $actual);
        $this->assertContains('  latest: 20150429120004 (in migration files)', $actual);
    }
}
