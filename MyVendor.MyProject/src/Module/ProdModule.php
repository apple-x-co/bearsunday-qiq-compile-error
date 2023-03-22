<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\Package\Context\ProdModule as PackageProdModule;
use BEAR\QiqModule\QiqErrorModule;
use BEAR\QiqModule\QiqProdModule;
use BEAR\QueryRepository\CacheVersionModule;
use BEAR\Resource\Module\OptionsMethodModule;

class ProdModule extends AbstractAppModule
{
    protected function configure(): void
    {
        $this->install(new PackageProdModule());
        $this->override(new OptionsMethodModule);
        $this->install(new CacheVersionModule('1'));

        $this->install(new QiqErrorModule());
        $this->install(new QiqProdModule($this->appMeta->appDir . '/var/tmp'));
    }
}
