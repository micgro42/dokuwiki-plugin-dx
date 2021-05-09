<?php

declare(strict_types=1);

use dokuwiki\Extension\CLIPlugin;
use dokuwiki\plugin\dx\src\Standardize;
use splitbrain\phpcli\Options;

final class cli_plugin_dx extends CLIPlugin
{

    protected function setup(Options $options): void
    {
        $options->setHelp('An opinionated plugin to improve the Developer Experience of DokuWiki plugin developers');

        $options->registerCommand('standardize', 'Standardize the dev files of a plugin');
        $options->registerArgument('plugin', 'plugin name', true, 'standardize');
//        $options->registerOption('version', 'print version', 'v');
    }

    protected function main(Options $options): void
    {
        if ($options->getCmd() !== 'standardize') {
            return;
        }
        $arguments = $options->getArgs();

        foreach ( $arguments as $pluginName ) {
            (new Standardize())->standardizePlugin($pluginName);
        }
    }
}
