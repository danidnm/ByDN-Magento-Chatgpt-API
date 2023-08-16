<?php

namespace DanielNavarro\ChatGpt\Model\ChatGpt;

class Moderation extends Base
{
    // Internal categories
    protected const CATEGORY_SEXUAL = 'sexual';
    protected const CATEGORY_HATE = 'hate';
    protected const CATEGORY_HARASSMENT = 'harassment';
    protected const CATEGORY_SELF_HARM = 'selfharm';
    protected const CATEGORY_THREATENING = 'threatening';
    protected const CATEGORY_VIOLENCE = 'violence';

    // OpenAI classification
    protected const OPENAI_CATEGORY_SEXUAL = 'sexual';
    protected const OPENAI_CATEGORY_HATE = 'hate';
    protected const OPENAI_CATEGORY_HARASSMENT = 'harassment';
    protected const OPENAI_CATEGORY_SELF_HARM = 'self-harm';
    protected const OPENAI_CATEGORY_SEXUAL_MINORS = 'sexual/minors';
    protected const OPENAI_CATEGORY_HATE_THREATENING = 'hate/threatening';
    protected const OPENAI_CATEGORY_VIOLENCE_GRAPHIC = 'violence/graphic';
    protected const OPENAI_CATEGORY_SELF_HARM_INTENT = 'self-harm/intent';
    protected const OPENAI_CATEGORY_SELF_HARM_INSTRUCTIONS = 'self-harm/instructions';
    protected const OPENAI_CATEGORY_HARASSMENT_THREATENING = 'harassment/threatening';
    protected const OPENAI_CATEGORY_VIOLENCE = 'violence';

    /**
     * Translation into our categories
     * @var array[]
     */
    private $openAiToInternalCategories = [
        self::OPENAI_CATEGORY_SEXUAL => [self:: CATEGORY_SEXUAL],
        self::OPENAI_CATEGORY_HATE => [self::CATEGORY_HATE],
        self::OPENAI_CATEGORY_HARASSMENT => [self::CATEGORY_HARASSMENT],
        self::OPENAI_CATEGORY_SELF_HARM => [self::CATEGORY_SELF_HARM],
        self::OPENAI_CATEGORY_SEXUAL_MINORS => [self::CATEGORY_SEXUAL],
        self::OPENAI_CATEGORY_HATE_THREATENING => [self::CATEGORY_HATE, self::CATEGORY_THREATENING],
        self::OPENAI_CATEGORY_VIOLENCE_GRAPHIC => [self::CATEGORY_VIOLENCE],
        self::OPENAI_CATEGORY_SELF_HARM_INTENT => [self::CATEGORY_SELF_HARM],
        self::OPENAI_CATEGORY_SELF_HARM_INSTRUCTIONS => [self::CATEGORY_SELF_HARM],
        self::OPENAI_CATEGORY_HARASSMENT_THREATENING => [self::CATEGORY_HARASSMENT, self::CATEGORY_THREATENING],
        self::OPENAI_CATEGORY_VIOLENCE => [self::CATEGORY_VIOLENCE],
    ];

    /**
     * Model name to be used
     * @var string
     */
    protected $modelName = '';

    /**
     * Endpoint of the model
     * @var string
     */
    protected $endpointUrl = 'https://api.openai.com/v1/moderations';

    /**
     * Make a request to OpenAI moderation service with the specified text
     *
     * @param string $text
     * @return array|mixed
     */
    public function moderateText(string $text)
    {
        // Prepare data array...
        $data = [
            'input' => $text,
        ];

        // Make the request and get the result
        $result = $this->_makeRequest($data);

        // Should be this data in the response
        if (!isset($result['results'][0]['flagged']) ||
            !isset($result['results'][0]['categories']) ||
            !isset($result['results'][0]['category_scores'])
        ) {
            return [];
        }

        // Return translated categories
        $processedResult = $this->translateScores(
            $result['results'][0]['flagged'],
            $result['results'][0]['categories'],
            $result['results'][0]['category_scores']
        );

        return $processedResult;
    }

    /**
     * Returns internal category for a particular OpenAI category
     *
     * @param string $openAiCategory
     * @return array|string[]
     */
    private function getInternalCategories(string $openAiCategory)
    {
        // Check if it is set
        if (isset($this->openAiToInternalCategories[$openAiCategory])) {
            return $this->openAiToInternalCategories[$openAiCategory];
        }

        return ['unknown'];
    }

    /**
     * Translates OpenAI scores into internal extension scores
     *
     * @param string $flagged
     * @param array $categories
     * @param array $scores
     * @return array
     */
    private function translateScores($flagged, $categories, $scores)
    {
        // Data to return
        $result = [];

        // Overall result
        $result['flagged'] = $flagged;

        // Iterate over categories and set scores
        foreach ($categories as $category => $status) {

            // Get internal categories
            $internalCategories = $this->getInternalCategories($category);

            // Get current score
            $score = $scores[$category] ?? 0;

            // Add to the internal categories
            foreach ($internalCategories as $internalCategory) {

                // Initicalize if neede
                if (!isset($result['categories'][$internalCategory])) {
                    $result['categories'][$internalCategory] = 0;
                }

                // Add score round to four decimals
                $result['categories'][$internalCategory] += round($score, 4);
            }
        }

        return $result;
    }
}
