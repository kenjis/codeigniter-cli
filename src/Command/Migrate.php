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

/**
 * @property \CI_Migration $migration
 * @property \CI_Loader    $load
 * @property \CI_Config    $config
 */
class Migrate extends Command
{
    public function __invoke($command = null)
    {
        $this->load->library('migration');
        $this->load->config('migration');

        if ($command === 'status') {
            $this->listMigrationFiles();
            return;
        }

        if ($command === 'version') {
            $this->showVersions();
            return;
        }

        // if argument is digits, migrate to the version
        if (ctype_digit($command)) {
            if ($this->migrateToVersion($command) === false) {
                return Status::FAILURE;
            } else {
                $this->showVersions();
                return;
            }
        }

        if ($command !== null) {
            $this->stdio->errln(
                '<<red>>No such command: ' . $command . '<<reset>>'
            );
            return Status::USAGE;
        }

        // if no argument, migrate to current
        if ($this->migration->current() === false) {
            $this->stdio->errln(
                '<<red>>' . $this->migration->error_string() . '<<reset>>'
            );
            return Status::FAILURE;
        } else {
            $this->showVersions();
        }
    }

    private function migrateToVersion($version)
    {
        if ($this->migration->version($version) === false) {
            $this->stdio->errln(
                '<<red>>' . $this->migration->error_string() . '<<reset>>'
            );
            return false;
        }
        
        return true;
    }

    private function getDbVersion()
    {
        $row = $this->db->select('version')->get($this->config->item('migration_table'))->row();
        return $row ? $row->version : '0';
    }

    private function showVersions()
    {
        $this->stdio->outln(
            ' current: <<green>>' . $this->config->item('migration_version') . '<<reset>>'
            . ' (in config/migration.php)'
        );
        $version = $this->getDbVersion();
        $this->stdio->outln(
            'database: <<bold>>' . $version . '<<reset>>'
            . ' (in database table)'
        );
        $version = $this->getLatestVersion();
        $this->stdio->outln(
            '  latest: ' . $version . ''
            . ' (in migration files)'
        );
    }

    private function getLatestVersion()
    {
        $files = $this->migration->find_migrations();
        
        if ($files === []) {
            return 'null';
        }
        
        end($files);
        return key($files);
    }

    private function listMigrationFiles()
    {
        $this->stdio->outln(
            $this->config->item('migration_path')
        );

        $current = $this->config->item('migration_version');
        $db = $this->getDbVersion();

        $files = $this->migration->find_migrations();
        foreach ($files as $v => $file) {
            if ($v == $current && $v == $db) {
                $this->stdio->outln(
                    '  <<green>>' . basename($file) .' (current/database)<<reset>>'
                );
            } elseif ($v == $current) {
                $this->stdio->outln(
                    '  <<green>>' . basename($file) .' (current)<<reset>>'
                );
            } elseif ($v == $db) {
                $this->stdio->outln(
                    '  <<bold>>' . basename($file) .' (database)<<reset>>'
                );
            } else {
                $this->stdio->outln('  ' . basename($file));
            }
        }
    }
}
