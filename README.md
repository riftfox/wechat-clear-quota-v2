# wechat-clear-quota-v2

`wechat-clear-quota-v2` 是对 [微信公众平台 clear_quota/v2 接口](https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/openApi-mgnt/clearQuota.html) 的 PHP SDK 实现。

## 功能简介

- 封装微信 clear_quota/v2 接口，便于开发者重置公众号/小程序的 API 调用次数。
- 遵循 PSR-7/PSR-17/PSR-18 标准，支持主流 HTTP 客户端。
- 依赖统一的 `ApplicationInterface`，可与其它微信 SDK 组件无缝集成。

## 依赖

- PHP 7.4 及以上
- psr/http-client
- psr/http-factory
- psr/log
- riftfox/wechat-application
- riftfox/wechat-exception

## 安装

建议通过 Composer 安装（如已发布到 Packagist）：

```bash
composer require riftfox/wechat-clear-quota-v2
```

或在本地开发环境中，确保相关依赖已正确引入。

## 使用方法

### 1. 实现 ApplicationInterface

你可以直接使用 `wechat-application` 包中的实现，或自定义实现：

```php
use Riftfox\Wechat\Application\ApplicationInterface;

class Application implements ApplicationInterface {
    // 实现 getAppId/getAppSecret 等方法
}
```

### 2. 初始化 ClearQuotaV2Provider

```php
use Riftfox\Wechat\ClearQuota\V2\ClearQuotaV2Provider;
use Riftfox\Wechat\Exception\ExceptionFactoryInterface;
use GuzzleHttp\Client; // 仅为示例，需实现 PSR-18 ClientInterface
use Nyholm\Psr7\Factory\Psr17Factory; // 需实现 PSR-17 Request/Uri/StreamFactory

$client = new Client(); // 实现了 PSR-18
$requestFactory = new Psr17Factory();
$uriFactory = new Psr17Factory();
$streamFactory = new Psr17Factory();
$exceptionFactory = new class implements ExceptionFactoryInterface {
    public function createException(string $message, int $code, $previous = null): \Exception {
        return new \Exception($message, $code, $previous);
    }
};

$provider = new ClearQuotaV2Provider(
    $client,
    $requestFactory,
    $uriFactory,
    $streamFactory,
    $exceptionFactory
);
```

### 3. 重置 API 调用次数（v2）

```php
$application = new Application('your-appid', 'your-secret', ApplicationInterface::TYPE_OFFICE);

try {
    $provider->clearQuota($application);
    echo 'API 调用次数已重置 (v2)';
} catch (\Exception $e) {
    // 处理异常
    echo '重置失败：' . $e->getMessage();
}
```

## 相关链接

- [微信 clear_quota/v2 官方文档](https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/openApi-mgnt/clearQuota.html)
- [微信 clear_quota/v2 接口](https://api.weixin.qq.com/clear_quota/v2)

## License

MIT 