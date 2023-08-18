<?php

namespace Bydn\OpenAi\Model\OpenAi;

class Completions extends Base
{
    /**
     * Message owner
     */
    public const COMPLETION_TYPE_USER = 'user';
    public const COMPLETION_TYPE_ASSISTANT = 'assistant';
    public const COMPLETION_TYPE_SYSTEM = 'system';
    public const COMPLETION_TYPE_FUNCTION = 'function';

    /**
     * Model name to be used. By default, GPT 3.5 Turbo, although it is configurable
     * @var string
     */
    protected $modelName = 'gpt-3.5-turbo';

    /**
     * Endpoint of the model
     * @var string
     */
    protected $endpointUrl = 'https://api.openai.com/v1/chat/completions';

    /**
     * Stores full conversation with the assistant
     * @var array
     */
    private $conversation = [];

    /**
     * Sets a model name to be used. Defaults to GPT 3.5 Turbo
     * @param string $model
     * @return void
     */
    public function setModel($model)
    {
        $this->modelName = $model;
    }

    /**
     * Add a message to the conversation
     *
     * @param string $text
     * @param $messageType
     * @return void
     */
    public function addMessageToConversation(string $text, $messageType = self::COMPLETION_TYPE_USER)
    {
        $this->conversation[] = [
            'role' => $messageType,
            'content' => $text
        ];
    }

    /**
     * Get last message from the conversation. Typically, the last response from the model.
     *
     * @return string|null
     */
    public function getLastMessageFromConversation()
    {
        if (!empty($this->conversation)) {
            $lastMessage = end($this->conversation);
            if (isset($lastMessage['content'])) {
                return $lastMessage['content'];
            }
        }
        return null;
    }

    /**
     * Make a request to OpenAI completions API with a full conversation
     *
     * @param string $text
     * @return array|mixed
     */
    public function askAssistant()
    {
        // Prepare data array...
        $data = [
            'model' => $this->modelName,
            'messages' => $this->conversation,
        ];

        // Make the request and get the result
        $result = $this->_makeRequest($data);

        // Should have a choices entry with one entry
        if (!isset($result['choices'][0]) ||
            !isset($result['choices'][0]['message']) ||
            !isset($result['choices'][0]['message']['role']) ||
            !isset($result['choices'][0]['message']['content'])
        ) {
            return false;
        }

        // Store last response
        $this->addMessageToConversation(
            $result['choices'][0]['message']['content'],
            $result['choices'][0]['message']['role']
        );

        return $this->conversation;
    }

    /**
     * Adds a message to the conversation and make a request to OpenAI completions API with a full conversation
     *
     * @param string $text
     * @return mixed|null Last message received or full conversation from the assistant or null if something fails
     */
    public function sendNewChatMessage(string $text, $messageType = self::COMPLETION_TYPE_USER, bool $fullResponse = false)
    {
        // Add message to the conversation
        $this->addMessageToConversation($text, $messageType);

        // Send conversation
        if (!$this->askAssistant()) {
            return null;
        }

        // Return full conversation or last message
        if  ($fullResponse) {
            return $this->conversation;
        }

        // Returns last message
        return $this->getLastMessageFromConversation();
    }
}
