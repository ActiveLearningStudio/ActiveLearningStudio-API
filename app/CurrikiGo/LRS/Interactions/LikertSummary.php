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
     * Interaction summary
     *
     * @return array
     */
    public function summary()
    {
        $definition = $this->getDefinition();
        $summary['interaction'] = $this->getInteractionType();
        $result = $this->getResult();
        $summary['name'] = $this->getName();
        $summary['description'] = $this->getDescription();
        $summary['scorable'] = $this->isScorable();
        if ($result) {
            $summary['choices'] = $this->getChoicesListArray();
            $summary['correct-pattern'] = $this->getComponentListArray();
            $summary['response'] = $this->getFormattedResponse();
            $summary['raw-response'] = $this->getRawResponse();
            if ($result->getScore()) {
                $summary['score'] = [
                    'raw' => $result->getScore()->getRaw(),
                    'min' => $result->getScore()->getMin(),
                    'max' => $result->getScore()->getMax(),
                    'scaled' => $result->getScore()->getScaled(),
                ];
                $summary['duration'] = xAPIFormatDuration($result->getDuration());
                $summary['raw-duration'] = xAPIFormatDuration($result->getDuration(), false);
            } else {
                $summary['score'] = [
                    'raw' => 0,
                    'max' => 0,
                    'min' => 0,
                    'scaled' => 0,
                ];
                $summary['duration'] = '00:00';
                $summary['raw-duration'] = 0;
            }
            // Get Interaction type
        }
        // Get Verb
        $summary['verb'] = $this->getVerb();
        return $summary;
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
        
        return (array_key_exists($response, $choices) ? $choices[$response] : '');
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
