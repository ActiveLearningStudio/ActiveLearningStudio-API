<?php

namespace App\CurrikiGo\LRS\Interactions;

use App\CurrikiGo\LRS\InteractionSummaryInterface;
use App\CurrikiGo\LRS\InteractionSummary;
use \TinCan\Statement;

/**
 * Choice Interaction summary class
 */
class ChoiceSummary extends InteractionSummary
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
     * Get student response array
     *
     * @return array
     */
    private function getResponses()
    {
        if (!empty($this->getResult())) {
            return explode('[,]', $this->getResult()->getResponse());
        }
        return [];
        
    }

    /**
     * Get descriptive student responses
     *
     * @return array
     */
    public function getFormattedResponse()
    {
        // student responses.
        $responses = $this->getResponses();
        $choices = $this->getChoicesListArray();
        $answers = [];
        if (!empty($responses) && !empty($choices)) {
            foreach ($responses as $value) {
                if (array_key_exists($value, $choices)) {
                    $answers[] = $choices[$value];
                }
            }
        }
        return $answers;
    }

    /**
     * Get quiz choices array
     *
     * @return array
     */
    public function getChoicesListArray()
    {
        return $this->prepareChoiceList($this->getRawChoices());
    }

    /**
     * Get raw quiz choices
     *
     * @return array
     */
    private function getRawChoices()
    {
        $definition = $this->getDefinition();
        return $definition->getChoices();
    }
    
    /**
     * Prepare choice list in an array.
     * 
     * @param array $list
     * @param string $languageKey Defaults to en-US
     * @return array
     */
    private function prepareChoiceList($list, $languageKey = 'en-US')
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
        // student responses.
        $responsePattern = $this->getCorrectResponsesPattern();
        
        // Check if it's a scorable type
        if ($this->isScorable()) {
            // it's a good possibility that the responses are concatenated by [,]
            if (is_array($responsePattern)) {
                $responsePattern = array_map(function ($arr) {
                    return explode('[,]', $arr);
                }, $responsePattern);
                return $responsePattern;
            } 
            return explode('[,]', $responsePattern);
        }
        return $responsePattern;
    }
}
