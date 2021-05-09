<?php

declare(strict_types=1);

namespace dokuwiki\plugin\dx\src;

use LogicException;
use RuntimeException;

final class Standardize
{
    private const STANDARDIZE_VERSION = '0.1.0';

    // TODO: add logger trait or something

    public function standardizePlugin(string $pluginName): void
    {
        // figure out plugin standardize version

        // check for no git changes in plugin directory
        $this->ensurePluginDirPristine($pluginName);

        // remove old files
        $filesToDelete = [
            '_test/general.test.php',
            '.github/workflows/phpCS.yml', // old name of phpQuality.yml
        ];
        foreach ($filesToDelete as $fileName) {
            $this->deleteFileFromPlugin($pluginName, $fileName);
        }

        // copy new files
        $filesToCopy = [
            '.versionrc.js',
            'build/pluginInfoVersionUpdater.js',
            '.github/workflows/testLinux.yml',
            '.github/workflows/phpQuality.yml',
            '.github/workflows/commit-lint.yml',
            '_test/GeneralTest.php',
        ];
        foreach ($filesToCopy as $fileName) {
            $this->copyFileToPlugin($pluginName, $fileName);
        }
        // write standardize version file?
    }

    private function ensurePluginDirPristine(string $pluginName): void
    {
        $pluginDir = DOKU_PLUGIN . $pluginName;
        if (!file_exists($pluginDir) || !is_dir($pluginDir)) {
            throw new RuntimeException("Plugin \"$pluginName\" does not exist in expected location \"$pluginDir\"!");
        }

        // TODO: check for git executable being available
        //       and for .git directory being present in plugin dir

        chdir($pluginDir);
        $gitStatusOutput = shell_exec('git status --porcelain');
        if ($gitStatusOutput !== null) {
            throw new RuntimeException($pluginDir . ' has uncommited git changes or untracked files!');
        }
    }

    private function deleteFileFromPlugin(string $pluginName, string $fileName): void
    {
        $targetFilePath = DOKU_PLUGIN . $pluginName . '/' . $fileName;
        if (!file_exists($targetFilePath)) {
            return;
        }
        if (!is_writable($targetFilePath)) {
            throw new RuntimeException($targetFilePath . ' is not writable!');
        }
        unlink($targetFilePath);
    }

    private function copyFileToPlugin(string $pluginName, string $fileName): void
    {
        $filePath = DOKU_PLUGIN . 'dx/skel/' . $fileName . '.skel';
        if (!file_exists($filePath)) {
            throw new LogicException('file missing: ' . $filePath);
        }
        $fileContents = file_get_contents($filePath);
        $fileContents = str_replace(
            '@@PLUGIN_NAME@@',
            $pluginName,
            $fileContents
        );
        $targetFilePath = DOKU_PLUGIN . $pluginName . '/' . $fileName;

        $this->makeFileDir($targetFilePath);
        if (!is_writable(dirname($targetFilePath))) {
            throw new RuntimeException($targetFilePath . ' is not writable!');
        }
        file_put_contents($targetFilePath, $fileContents);
    }

    private function makeFileDir(string $filePath): void
    {
        $dirPath = dirname($filePath);
        if (file_exists($dirPath) && is_dir($dirPath)) {
            // All good, directory already exists
            return;
        }
        if (!mkdir($dirPath, 0755, true) && !is_dir($dirPath)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dirPath));
        }
    }
}
