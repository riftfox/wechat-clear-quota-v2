<?php

namespace Riftfox\Wechat\ClearQuota\V2;

use Riftfox\Wechat\Application\ApplicationInterface;
use Riftfox\Wechat\Token\TokenInterface;

interface ClearQuotaV2ProviderInterface
{
    const string CLEAR_QUOTA_V2_URL = 'https://api.weixin.qq.com/clear_quota/v2';
    public function clearQuota(ApplicationInterface $application): void;
}