<?php

declare(strict_types=1);

namespace dokuwiki\plugin\dx\src;

use LogicException;
use RuntimeException;

final class Standardize
{
    private const STANDARDIZE_VERSION = '1.0.0';

    // TODO: add logger trait or something

    public function standardizePlugin(string $pluginName): void
    {
        // figure out plugin standardize version

        // remove old files -> add them to deleted.files
        // this includes _test/general.test.php

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
