<?php
/**
 * Part of CodeIgniter Cli
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-cli
 */

$installer = new Installer();
$installer->install();

class Installer
{
    public static function install()
    {
        self::recursiveCopy('application/vendor/kenjis/codeigniter-cli/config', 'config');
        
        @mkdir('tmp', 0755);
        @mkdir('tmp/log', 0755);
        
        self::copy('application/vendor/kenjis/codeigniter-cli/cli', 'cli');
        self::copy('application/vendor/kenjis/codeigniter-cli/ci_instance.php', 'ci_instance.php');
        
        chmod('cli', 0755);
    }

    private static function copy($src, $dst)
    {
        $success = copy($src, $dst);
        if ($success) {
            echo 'copied: ' . $dst . PHP_EOL;
        }
    }

    /**
     * Recursive Copy
     *
     * @param string $src
     * @param string $dst
     */
    private static function recursiveCopy($src, $dst)
    {
        @mkdir($dst, 0755);
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($src, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                @mkdir($dst . '/' . $iterator->getSubPathName());
            } else {
                $success = copy($file, $dst . '/' . $iterator->getSubPathName());
                if ($success) {
                    echo 'copied: ' . $dst . '/' . $iterator->getSubPathName() . PHP_EOL;
                }
            }
        }
    }
}
