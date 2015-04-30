<?php

namespace Kenjis\CodeIgniter_Cli\Command;

use Aura\Cli\Context\OptionFactory;

class GenerateHelpTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->help = new GenerateHelp(new OptionFactory);
    }

    public function test_get_help()
    {
        $actual = $this->help->getSummary('run');
        $expected = 'No such generator class: Kenjis\CodeIgniter_Cli\Command\Generate\Not_exists' . PHP_EOL;
        $this->assertEquals('Generate code.', $actual);
    }
}
