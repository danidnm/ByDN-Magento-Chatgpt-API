<?php

namespace DanielNavarro\ChatGpt\Model\ChatGpt;

class Moderation extends Base
{
    protected $modelName = '';
    protected $endpointUrl = 'https://api.openai.com/v1/moderations';
    public function moderateText($text) {

        // Prepare data array...
        $data = [
            'input' => $text,
        ];

        // Make the request and get the result
        $result = $this->_makeRequest($data);

        // Should be two arrays in the response
        if (isset($result['results'])) {
            return $result['results'];
        }

        // Rerturn nothing if not found
        return [];
    }
}
