<?php

namespace AfterBugHQ\ModuleInstaller;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class AfterBugModuleInstallerPlugin implements PluginInterface
{

    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new AfterBugModuleInstaller($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }

}
