<?php

namespace Bydn\OpenAi\Model\OpenAi;

class Base
{
    /**
     * Model name to be defined by the subclasses
     * @var string
     */
    protected $modelName = '';

    /**
     * Endpoint URL to be defined by the subclasses
     * @var string
     */
    protected $endpointUrl = '';

    /**
     * @var \Bydn\OpenAi\Helper\Config
     */
    private $openAiConfig;

    /**
     * @param \Bydn\OpenAi\Helper\Config $openAiConfig
     */
    public function __construct(
        \Bydn\OpenAi\Helper\Config $openAiConfig
    ) {
        $this->openAiConfig = $openAiConfig;
    }

    /**
     * Returns the OpenAI model to be used
     *
     * @return mixed|string
     */
    protected function getModelName()
    {
        return $this->modelName;
    }

    /**
     * Return the endpoint for
     *
     * @return mixed|string
     */
    protected function getEndpointUrl()
    {
        return $this->endpointUrl;
    }

    /**
     * Make a request to the OpenAI moderation service
     *
     * @param array $data
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _makeRequest($data)
    {
        // Do not post anything if not enabled
        if (!$this->openAiConfig->isEnabled()) {
            throw new \Magento\Framework\Exception\LocalizedException(
                new \Magento\Framework\Phrase('OpenAi is not enabled. Request aborted.')
            );
        }

        // Get the API key to be used
        $apiKey = $this->openAiConfig->getApiKey();

        // Get the endpoint to be used
        $endpoint = $this->getEndpointUrl();

        // Encode payload as JSON
        $json_data = json_encode($data);

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ]);

        // Make the API request and get the response
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close the cURL session
        curl_close($ch);

        // Decode data from response
        $responseDecoded = json_decode($response, true);

        // And return
        return $responseDecoded;
    }
}
