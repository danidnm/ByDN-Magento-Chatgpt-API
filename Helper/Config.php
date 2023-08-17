<?php

namespace Bydn\ChatGpt\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    private const PATH_CHATGPT_ENABLE = 'bydn_chatgpt/general/enable';
    private const PATH_CHATGPT_API_KEY = 'bydn_chatgpt/general/api_key';

    /**
     * Check if OpenAI integration is enabled
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function isEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::PATH_CHATGPT_ENABLE,
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
            self::PATH_CHATGPT_API_KEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
