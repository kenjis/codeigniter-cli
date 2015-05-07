<?php

namespace Kenjis\CodeIgniter_Cli;

use Aura\Di\Container;
use Aura\Di\Factory;

class UserConfigTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->di = new Container(new Factory);
        $this->di->set('aura/cli-kernel:stdio', $this->di->lazyNew('Aura\Cli\Stdio'));
        $this->di->params['Aura\Cli\Stdio'] = [
            'stdin' => $this->di->lazyNew('Aura\Cli\Stdio\Handle', [
                'name' => 'php://memory',
                'mode' => 'r',
            ]),
            'stdout' => $this->di->lazyNew('Aura\Cli\Stdio\Handle', [
                'name' => 'php://memory',
                'mode' => 'w+',
            ]),
            'stderr' => $this->di->lazyNew('Aura\Cli\Stdio\Handle', [
                'name' => 'php://memory',
                'mode' => 'w+',
            ]),
            'formatter' => $this->di->lazyNew('Aura\Cli\Stdio\Formatter'),
        ];
    }

    public function test_registerCommandClasses()
    {
        $ci = new \stdClass();
        $paths = [ __DIR__ . '/Fake/user_commands/' ];
        UserConfig::registerCommandClasses($this->di, $ci, $paths);
        
        $this->assertTrue(array_key_exists('TestCommand', $this->di->params));
    }

    public function test_registerCommandClasses_bad_classname()
    {
        $ci = new \stdClass();
        $paths = [ __DIR__ . '/Fake/user_commands_bad/' ];
        UserConfig::registerCommandClasses($this->di, $ci, $paths);
        
        $stderr = $this->di->get('aura/cli-kernel:stdio')->getStderr();
        $stderr->rewind();
        $actual = $stderr->fread(8192);
        $expected = 'No such class: BadCommand';
        $this->assertContains($expected, $actual);
    }

    public function test_registerCommands()
    {
        $this->di->set(
            'aura/cli-kernel:dispatcher',
            $this->di->lazyNew('Aura\Dispatcher\Dispatcher', [
                'object_param' => 'command',
            ]
        ));
        $dispatcher = $this->di->get('aura/cli-kernel:dispatcher');
        $paths = [ __DIR__ . '/Fake/user_commands/' ];
        UserConfig::registerCommands($this->di, $dispatcher, $paths);
        
        $this->assertTrue($dispatcher->hasObject('test'));
    }

    public function test_registerCommandHelps()
    {
        $this->di->set(
            'aura/cli-kernel:help_service',
            $this->di->lazyNew('Aura\Cli_Kernel\HelpService')
        );
        $help_service = $this->di->get('aura/cli-kernel:help_service');
        $paths = [ __DIR__ . '/Fake/user_commands/' ];
        UserConfig::registerCommandHelps($this->di, $help_service, $paths);
        
        $this->assertTrue($help_service->has('test'));
    }
}
