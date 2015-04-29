<?php
/**
 * Part of Cli for CodeIgniter
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-cli
 */

namespace Kenjis\CodeIgniter_Cli\Command;

use Aura\Cli\Status;

class Migrate extends Command
{
    public function __invoke($command = null)
    {
        $this->load->library('migration');
        $this->load->config('migration');

        if ($command === 'files') {
            $this->listMigrationFiles();
            return;
        }

        if ($command === 'curver') {
            $this->showCurrentVersion();
            return;
        }

        if ($this->migration->current() === false) {
            $this->stdio->errln(
                '<<red>>' . $this->migration->error_string() . '<<reset>>'
            );
            return Status::FAILURE;
        }
    }

    private function showCurrentVersion()
    {
        $this->stdio->outln(
            $this->config->item('migration_version')
        );
    }

    private function listMigrationFiles()
    {
        $this->stdio->outln(
            $this->config->item('migration_path')
        );

        $current = $this->config->item('migration_version');

        $files = $this->migration->find_migrations();
        foreach ($files as $v =>$file) {
            if ($v === $current) {
                $this->stdio->outln(
                    '  <<green>>' . basename($file) .' (current)<<reset>>'
                );
            } else {
                $this->stdio->outln('  ' . basename($file));
            }
        }
    }
}
