<?php

namespace Bydn\OpenAi\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    private const PATH_OPENAI_ENABLE = 'bydn_openai/general/enable';
    private const PATH_OPENAI_API_KEY = 'bydn_openai/general/api_key';

    /**
     * Check if OpenAI integration is enabled
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function isEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::PATH_OPENAI_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Returns API Key for the integration
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function getApiKey($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::PATH_OPENAI_API_KEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
