<?php

namespace DanielNavarro\ChatGpt\Model\ChatGpt;

class Base
{
    protected $modelName = '';
    protected $endpointUrl = '';

    /**
     * @var \DanielNavarro\ChatGpt\Helper\Config
     */
    private $chatGptConfig;

    /**
     * @param \DanielNavarro\ChatGpt\Helper\Config $chatGptConfig
     */
    public function __construct(
        \DanielNavarro\ChatGpt\Helper\Config $chatGptConfig
    ) {
        $this->chatGptConfig = $chatGptConfig;
    }

    /**
     * @return mixed|string
     */
    protected function getModelName() {
        return $this->modelName;
    }

    /**
     * @return mixed|string
     */
    protected function getEndpointUrl() {
        return $this->endpointUrl;
    }

    protected function _makeRequest($data) {

        // Do not post anything if not enabled
        if (!$this->chatGptConfig->isEnabled()) {
            throw new Exception('ChatGpt is not enabled. Request aborted.');
        }

        // Get the API key to be used
        $apiKey = $this->chatGptConfig->getApiKey();

        // Get the endpoint to be used
        $endpoint = $this->getEndpointUrl();

        // Encode payload as JSON
        $json_data = json_encode($data);

        // Initialize cURL session
        $ch = curl_init();

        try {

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "Authorization: Bearer $apiKey"
            ));

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
        catch (Exception $e) {

        }
    }
}
