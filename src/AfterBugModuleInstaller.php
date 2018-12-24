<?php

namespace AfterBugHQ\ModuleInstaller;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class AfterBugModuleInstaller extends LibraryInstaller
{
    const DEFAULT_ROOT = "Modules";

    /**
     * Get the fully-qualified install path
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        return $this->getBaseInstallationPath() . '/' . $this->getModuleDirectory($package);
    }

    /**
     * Get the base path that the module should be installed into.
     * Defaults to Modules/ and can be overridden in the module's composer.json.
     * @return string
     */
    protected function getBaseInstallationPath()
    {
        if (!$this->composer || !$this->composer->getPackage()) {
            return self::DEFAULT_ROOT;
        }

        $extra = $this->composer->getPackage()->getExtra();

        if (!$extra || empty($extra['module-dir'])) {
            return self::DEFAULT_ROOT;
        }

        return $extra['module-dir'];
    }

    /**
     * Get the module name, i.e. "afterbug/something-module" will be transformed into "afterbug/something"
     * @param PackageInterface $package
     * @return string
     * @throws \Exception
     */
    protected function getModuleDirectory(PackageInterface $package)
    {
        $name = $package->getPrettyName();
        $split = explode("/", $name);

        if (count($split) !== 2) {
            throw new \Exception($this->usage());
        }

        $splitNameToUse = explode("-", $split[1]);

        if (count($splitNameToUse) < 2) {
            throw new \Exception($this->usage());
        }

        if (array_pop($splitNameToUse) !== 'module') {
            throw new \Exception($this->usage());
        }

        array_unshift($splitNameToUse, $split[0]);

        return implode('/', array_map('strtolower', $splitNameToUse));
    }

    /**
     * Get the usage instructions
     * @return string
     */
    protected function usage()
    {
        return "Ensure your package's name is in the format <vendor>/<name>-<module>";
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return 'afterbug-module' === $packageType;
    }
}
