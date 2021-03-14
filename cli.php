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
        if (count($arguments) > 1) {
            $this->error('Please provide only a single plugin name');
            return;
        }
        $pluginName = $arguments[0];
        // TODO: check that plugin exists

        (new Standardize())->standardizePlugin($pluginName);
    }
}
