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
        $stdio = $cli_factory->newStdio();
        $this->cmd = new Migrate($context, $stdio, $this->ci);
        $this->ci->config->set_item('migration_version', 20150429110003);
    }

    public function test_command_not_exists()
    {
        $status = $this->cmd->__invoke('command-not-exists');
        $this->assertEquals($status, Status::USAGE);
    }

    public function test_migrate()
    {
        $status = $this->cmd->__invoke();
        $this->assertEquals($status, 0);
    }

    public function test_status()
    {
        $status = $this->cmd->__invoke('status');
        $this->assertEquals($status, 0);
    }

    public function test_status_current_not_equals_database()
    {
        $this->ci->config->set_item('migration_version', 20150429120004);
        $status = $this->cmd->__invoke('status');
        $this->assertEquals($status, 0);
    }

    public function test_version()
    {
        $status = $this->cmd->__invoke('version');
        $this->assertEquals($status, 0);
    }
}
