# Aura.Cli_Project

This package provides a minimal framework for command-line projects.

By "minimal" we mean *very* minimal. The package provides only a dependency
injection container, a configuration system, a dispatcher, a pair of
context and standard I/O objects, and a logging instance.

This minimal implementation should not be taken as "restrictive". The DI
container, with its two-stage configuration system, allows a wide range of
programmatic service definitions. The dispatcher is built with
iterative refactoring in mind, so you can start with micro-framework-like
closure commands, and work your way up to more complex command objects of your
own design.

## Foreword

### Requirements

This project requires PHP 5.4 or later; we recommend using the latest available version of PHP as a matter of principle.

Unlike Aura library packages, this project package has userland dependencies, which themselves may have other dependencies:

- [aura/cli-kernel](https://packagist.org/packages/aura/cli-kernel)
- [monolog/monolog](https://packagist.org/packages/monolog/monolog)

### Installation

Install this project via Composer to a `{$PROJECT_PATH}` of your choosing:

    composer create-project aura/cli-project {$PROJECT_PATH}

This will create the project skeleton and install all of the necessary packages.

### Tests

[![Build Status](https://travis-ci.org/auraphp/Aura.Cli_Project.png)](https://travis-ci.org/auraphp/Aura.Cli_Project)

To run the unit tests at the command line, issue `phpunit` at the package root. This requires [PHPUnit](http://phpunit.de/) to be available as `phpunit`.

Alternatively, after you have installed the project, go to the project directory and issue the following command:

    cd {$PROJECT_PATH}
    php cli/console.php hello

You should see the output `Hello World!`. Try passing a name after `hello` to
see `Hello name!`.

### PSR Compliance

This projects attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

### Community

To ask questions, provide feedback, or otherwise communicate with the Aura community, please join our [Google Group](http://groups.google.com/group/auraphp), follow [@auraphp on Twitter](http://twitter.com/auraphp), or chat with us on #auraphp on Freenode.

### Services

This package uses services defined by:

- [Aura.Project_Kernel](https://github.com/auraphp/Aura.Project_Kernel#services)
- [Aura.Cli_Kernel](https://github.com/auraphp/Aura.Cli_Kernel#services)

This project resets the following services:

- `aura/project-kernel:logger`: an instance of `Monolog\Logger`


## Getting Started

### Component Packages

This project combines a collection of independent Aura packages into a cohesive whole. The operation of each package is documented separately.

The dependency injection _Container_ is absolutely central to the operation of an Aura project. Please be familiar with [the Aura.Di docs](https://github.com/auraphp/Aura.Di) before continuing.

You should also familiarize yourself with [Aura.Dispatcher](https://github.com/auraphp/Aura.Dispatcher), as well as the [Aura.Cli](https://github.com/auraphp/Aura.Cli) _Context_, _Stdio_, and _Status_ objects.

### Project Configuration

Every Aura project is configured the same way. Please see the [shared configuration docs](https://github.com/auraphp/Aura.Project_Kernel#configuration) for more information.

### Logging

The project automatically logs to `{$PROJECT_PATH}/tmp/log/{$mode}.log`. If you want to change the logging behaviors for a particular config mode, edit the related config file (e.g., `config/Dev.php`) file to modify the `aura/project-kernel:logger` service.

### Commands

We configure commands via the project-level `config/` class files. If a command needs to be available in every config mode, edit the project-level `config/Common.php` class file. If it only needs to be available in a specific mode, e.g. `dev`, then edit the config file for that mode.

Here are two different styles of command definition.

#### Micro-Framework Style

The following is an example of a command where the logic is embedded in the dispatcher, using the `aura/cli-kernel:context` and `aura/cli-kernel:stdio` services along with standard exit codes. (The dispatcher object name doubles as the command name.)

```php
<?php
namespace Aura\Cli_Project\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    // ...

    public function modifyCliDispatcher(Container $di)
    {
        $context = $di->get('aura/cli-kernel:context');
        $stdio = $di->get('aura/cli-kernel:stdio');
        $dispatcher = $di->get('aura/cli-kernel:dispatcher');
        $dispatcher->setObject(
            'foo',
            function ($id = null) use ($context, $stdio) {
                if (! $id) {
                    $stdio->errln("Please pass an ID.");
                    return \Aura\Cli\Status::USAGE;
                }

                $id = (int) $id;
                $stdio->outln("You passed " . $id . " as the ID.");
            }
        );
    }
?>
```

You can now run the command to see its output.

    cd {$PROJECT_PATH}
    php cli/console.php foo 88

(If you do not pass an ID argument, you will see an error message.)

#### Full-Stack Style

You can migrate from a micro-controller style to a full-stack style (or start
with full-stack style in the first place).

First, define a command class and place it in the project `src/` directory.

```php
<?php
/**
 * {$PROJECT_PATH}/src/App/Command/FooCommand.php
 */
namespace App\Command;

use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Aura\Cli\Status;

class FooCommand
{
    public function __construct(Context $context, Stdio $stdio)
    {
        $this->context = $context;
        $this->stdio = $stdio;
    }

    public function __invoke($id = null)
    {
        if (! $id) {
            $this->stdio->errln("Please pass an ID.");
            return Status::USAGE;
        }

        $id = (int) $id;
        $this->stdio->outln("You passed " . $id . " as the ID.");
    }
}
?>
```

Next, tell the project how to build the _FooCommand_ through the DI
_Container_. Edit the project `config/Common.php` file to configure the
_Container_ to pass the `aura/cli-kernel:context` and `aura/cli-kernel:stdio` service objects to
the _FooCommand_ constructor. Then put the _App\Command\FooCommand_ object in the dispatcher under the name `foo` as a lazy-loaded instantiation.

```php
<?php
namespace Aura\Cli_Project\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->set('aura/project-kernel:logger', $di->newInstance('Monolog\Logger'));

        $di->params['App\Command\FooCommand'] = array(
            'context' => $di->lazyGet('aura/cli-kernel:context'),
            'stdio' => $di->lazyGet('aura/cli-kernel:stdio'),
        );
    }

    // ...

    public function modifyCliDispatcher(Container $di)
    {
        $dispatcher = $di->get('aura/cli-kernel:dispatcher');

        $dispatcher->setObject(
            'foo',
            $di->lazyNew('App\Command\FooCommand')
        );
    }
?>
```

You can now run the command to see its output.

    cd {$PROJECT_PATH}
    php cli/console.php foo 88

(If you do not pass an ID argument, you will see an error message.)

#### Other Variations

These are only some common variations of dispatcher interactions;
[there are many other combinations](https://github.com/auraphp/Aura.Dispatcher/tree/develop-2#refactoring-to-architecture-changes).
