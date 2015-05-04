# Cli for CodeIgniter 3.0

[![Latest Stable Version](https://poser.pugx.org/kenjis/codeigniter-cli/v/stable)](https://packagist.org/packages/kenjis/codeigniter-cli) [![Total Downloads](https://poser.pugx.org/kenjis/codeigniter-cli/downloads)](https://packagist.org/packages/kenjis/codeigniter-cli) [![Latest Unstable Version](https://poser.pugx.org/kenjis/codeigniter-cli/v/unstable)](https://packagist.org/packages/kenjis/codeigniter-cli) [![License](https://poser.pugx.org/kenjis/codeigniter-cli/license)](https://packagist.org/packages/kenjis/codeigniter-cli)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kenjis/codeigniter-cli/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kenjis/codeigniter-cli/?branch=master)
[![Coverage Status](https://coveralls.io/repos/kenjis/codeigniter-cli/badge.svg?branch=master)](https://coveralls.io/r/kenjis/codeigniter-cli?branch=master)
[![Build Status](https://travis-ci.org/kenjis/codeigniter-cli.svg?branch=master)](https://travis-ci.org/kenjis/codeigniter-cli)

This package provides a Cli tool for [CodeIgniter](https://github.com/bcit-ci/CodeIgniter) 3.0.

This includes a few commands and you can create your commands easily.

This is based on Aura.Cli_Project 2.0.

## Included Commands

~~~
generate migration ... Generates migration file skeleton.
migrate            ... Run migrations.
migrate status     ... List all migration files and versions.
seed               ... Seed the database.
run                ... Run controller.
~~~

## Folder Structure

```
codeigniter/
├── application/
├── ci_instance.php ... script to generate CodeIgniter instance
├── cli             ... command file
├── config/         ... config folder
└── vendor/
```

## Requirements

* PHP 5.4.0 or later
* `composer` command
* Git

## Installation

Install this project with Composer:

~~~
$ cd /path/to/codeigniter/
$ composer require kenjis/codeigniter-cli:1.0.x@dev --dev
~~~

Install command file (`cli`) and config files (`config/`) to your CodeIgniter project:

~~~
$ php vendor/kenjis/codeigniter-cli/install.php
~~~

* Above command always overwrites exisiting files.
* You must run it at CodeIgniter project root folder.

Fix the paths in `ci_instance.php` if you need.

~~~php
$system_path        = 'vendor/codeigniter/framework/system';
$application_folder = 'application';
$doc_root           = 'public';
~~~

## Usage

Show command list.

~~~
$ cd /path/to/codeigniter/
$ php cli
~~~

Show help for a command.

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

### Reference

* https://github.com/auraphp/Aura.Cli_Project
* http://auraphp.com/framework/2.x/en/cli/

## How to Run Tests

To run tests, you must install CodeIgniter first.

~~~
$ composer create-project kenjis/codeigniter-composer-installer codeigniter
$ cd codeigniter
$ composer require kenjis/codeigniter-cli:1.0.x@dev --dev
$ php vendor/kenjis/codeigniter-cli/install.php
$ cd vendor/kenjis/codeigniter-cli
$ composer install
$ phpunit
~~~

## Related

If you want to install CodeIgniter via Composer, check it.

* https://github.com/kenjis/codeigniter-composer-installer
