<?php

namespace DanielNavarro\ChatGpt\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    const PATH_CHATGPT_ENABLE = 'danielnavarro_chatgpt/general/enable';
    const PATH_CHATGPT_API_Key = 'danielnavarro_chatgpt/general/api_key';

    /**
     * Check if OpenAI integration is enabled
     * @param $store_id
     * @return mixed
     */
    public function isEnabled($store_id = null) {
        return $this->scopeConfig->getValue(
            self::PATH_CHATGPT_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store_id
        );
    }

    /**
     * Returns API Key for the integration
     * @param $store_id
     * @return mixed
     */
    public function getApiKey($store_id = null) {
        return $this->scopeConfig->getValue(
            self::PATH_CHATGPT_API_Key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store_id
        );
    }
}
