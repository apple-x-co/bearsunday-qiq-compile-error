# bearsunday-qiq-compile-error

## 再現手順 

1. プロジェクトを `composer create-project -n bear/skeleton MyVendor.MyProject` で作成する
2. `QiqModule` を `composer require bear/qiq-module` で追加する ※ [参考：HTML > Qiq](https://bearsunday.github.io/manuals/1.0/ja/html-qiq.html)
3. `cp -r vendor/bear/qiq-module/var/qiq var` を実行する
4. `mkdir -p var/qiq/template/Page; touch var/qiq/template/Page/Index.php` を実行する
5. `MyVendor.MyProject/src/Module` に `HtmlModule` を設置して `QiqModule` をインストールする
6. `MyVendor.MyProject/src/Module` に `ProdModule` を設置する ※ [参考：プロダクション > アプリケーションのProdModule](https://bearsunday.github.io/manuals/1.0/ja/production.html)
7. `ProdModule` で `QiqErrorModule` および `QiqProdModule` をインストールする
8. `composer.json` の `autoload > psr-4` の下に `"Qiq\\Helper\\": "var/qiq/helper/"` を追加
9. `composer dump-autoload` を実行
10. `./vendor/bin/bear.compile 'MyVendor\MyProject' prod-html-app ./` でコンパイルできることを確認する
11. `./vendor/bin/bear.compile 'MyVendor\MyProject' prod-hal-api-app ./` で `qiq_template_dir` の束縛がないエラーが出ることが確認できる

### MyVendor.MyProject/src/Module/HtmlModule.php

```php
<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\QiqModule\QiqModule;

class HtmlModule extends AbstractAppModule
{
    protected function configure(): void
    {
        $this->install(new QiqModule($this->appMeta->appDir . '/var/qiq/template'));
    }
}
```

### MyVendor.MyProject/src/Module/ProdModule.php

```php
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
```

### エラーログ

```text
PHP Fatal error:  Uncaught exception 'Ray\Di\Exception\Unbound' with message 'dependency '' with name 'qiq_template_dir' used in /path/to/repository/apple-x-co/bearsunday-qiq-compile-error/MyVendor.MyProject/vendor/bear/qiq-module/src/QiqErrorPageRenderer.php:17 ($templateDir)'

  thrown in /path/to/repository/apple-x-co/bearsunday-qiq-compile-error/MyVendor.MyProject/vendor/ray/compiler/src/NodeFactory.php on line 118

Fatal error: Uncaught exception 'Ray\Di\Exception\Unbound' with message 'dependency '' with name 'qiq_template_dir' used in /path/to/repository/apple-x-co/bearsunday-qiq-compile-error/MyVendor.MyProject/vendor/bear/qiq-module/src/QiqErrorPageRenderer.php:17 ($templateDir)'

  thrown in /path/to/repository/apple-x-co/bearsunday-qiq-compile-error/MyVendor.MyProject/vendor/ray/compiler/src/NodeFactory.php on line 118
PHP Fatal error:  Uncaught exception 'Ray\Di\Exception\Unbound' with message 'dependency '' with name 'qiq_template_dir' used in /path/to/repository/apple-x-co/bearsunday-qiq-compile-error/MyVendor.MyProject/vendor/bear/qiq-module/src/QiqErrorPageRenderer.php:17 ($templateDir)'

  thrown in /path/to/repository/apple-x-co/bearsunday-qiq-compile-error/MyVendor.MyProject/vendor/ray/compiler/src/NodeFactory.php on line 118

Fatal error: Uncaught exception 'Ray\Di\Exception\Unbound' with message 'dependency '' with name 'qiq_template_dir' used in /path/to/repository/apple-x-co/bearsunday-qiq-compile-error/MyVendor.MyProject/vendor/bear/qiq-module/src/QiqErrorPageRenderer.php:17 ($templateDir)'

  thrown in /path/to/repository/apple-x-co/bearsunday-qiq-compile-error/MyVendor.MyProject/vendor/ray/compiler/src/NodeFactory.php on line 118
```
