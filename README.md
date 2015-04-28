# Cli for CodeIgniter 3.0

This package provides Cli framework to [CodeIgniter](https://github.com/bcit-ci/CodeIgniter) 3.0.

This includes a few commands and you can create your commands easily.

This is based on Aura.Cli_Project 2.0.

## Installation

Install this project with Composer.

~~~
$ cd /path/to/codeigniter/
$ composer require kenjis/codeignier-cli --dev
~~~

Install command file (cli) and config files (config/) to your CodeIgniter project.

~~~
$ php vendor/kenjis/codeigniter-cli/install.php
~~~

* Above command always overwrites exsiting files.
* You must run at CodeIgniter project top folder.

Fix the paths in `ci_instance.php` if you need.

~~~php
$system_path = 'vendor/codeigniter/framework/system';
$application_folder = 'application';
~~~

## Usage

Show command list.

~~~
$ cd /path/to/codeigniter/
$ php cli
~~~

Show help of a command.

~~~
$ php cli help seed
~~~

## Create Database Seeds

Seeder class must be placed in `application/database/seeds` folder.

`application/database/seeds/ProductSeeder.php`
~~~php
<?php

class ProductSeeder extends Seeder {

	public function run()
	{
		$this->db->truncate('product');

		$data = [
			'category_id' => 1,
			'name' => 'CodeIgniter Book',
			'detail' => 'Very good CodeIgniter book.',
			'price' => 3800,
		];
		$this->db->insert('product', $data);

		$data = [
			'category_id' => 2,
			'name' => 'CodeIgniter CD',
			'detail' => 'Great CodeIgniter CD.',
			'price' => 4800,
		];
		$this->db->insert('product', $data);

		$data = [
			'category_id' => 3,
			'name' => 'CodeIgniter DVD',
			'detail' => 'Awesome CodeIgniter DVD.',
			'price' => 5800,
		];
		$this->db->insert('product', $data);
	}

}
~~~

## Create User Command

Command class name must be `*Command.php` and be placed in `application/commands` folder.

`application/commands/TestCommand.php`
~~~php
<?php

class TestCommand extends Command {

	public function __invoke()
	{
		$this->stdio->outln('<<green>>This is TestCommand class<<reset>>');
	}

}
~~~

You can access CodeIgniter instance as `$this->ci` in Command class.

Command Help class name must be `*CommandHelp.php` and be placed in `application/commands` folder.

`application/commands/TestCommandHelp.php`
~~~php
<?php

class TestCommandHelp extends Help {

	public function init()
	{
		$this->setSummary('A single-line summary.');
		$this->setUsage('<arg1> <arg2>');
		$this->setOptions(array(
			'f,foo' => "The -f/--foo option description",
			'bar::' => "The --bar option description",
		));
		$this->setDescr("A multi-line description of the command.");
	}

}
~~~

## Reference

* https://github.com/auraphp/Aura.Cli_Project
* http://auraphp.com/framework/2.x/en/cli/
