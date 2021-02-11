<?php

namespace App\CurrikiGo\LRS\Interactions;

use App\CurrikiGo\LRS\InteractionSummaryInterface;
use App\CurrikiGo\LRS\InteractionSummary;
use \TinCan\Statement;

/**
 * Likert Interaction summary class
 */
class LikertSummary extends InteractionSummary
{
    /**
     * Initialize
     *
     * @param Statement $statement
     */
    public function __construct(Statement $statement)
    {
        $this->statement = $statement;
    }

    /**
     * Get descriptive student responses
     *
     * @return array
     */
    public function getFormattedResponse()
    {
        // student response.
        $response = $this->getRawResponse();
        $choices = $this->getChoicesListArray();
        
        return (!empty($choices) && array_key_exists($response, $choices) ? $choices[$response] : '');
    }

    /**
     * Get quiz scale array
     *
     * @return array
     */
    public function getChoicesListArray()
    {
        return $this->prepareScaleList($this->getRawScale());
    }

    /**
     * Get raw quiz choices
     *
     * @return array
     */
    private function getRawScale()
    {
        $definition = $this->getDefinition();
        return $definition->getScale();
    }
    
    /**
     * Prepare scale list in an array.
     * 
     * @param array $list
     * @param string $languageKey Defaults to en-US
     * @return array
     */
    private function prepareScaleList($list, $languageKey = 'en-US')
    {
        if (!is_array($list)) {
            return $list;
        }
        $return = [];
        foreach ($list as $values) {
            $return[$values['id']] = $values['description'][$languageKey];
        }
        return $return;
    }

    /**
     * Get component list array
     * It contains the correct response pattern, which shows the correct answers or sequence.
     * @return string
     */
    public function getComponentListArray()
    {
        return $this->getCorrectResponsesPattern();
    }
}
