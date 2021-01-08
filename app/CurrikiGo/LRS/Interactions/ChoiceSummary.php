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
     * Interaction summary
     *
     * @return array
     */
    public function summary()
    {
        $definition = $this->getDefinition();
        // $summary['correct-pattern'] = $this->getCorrectResponsesPattern();
        $summary['interaction'] = $this->getInteractionType();
        $result = $this->getResult();
        $summary['name'] = $this->getName();
        $summary['description'] = $this->getDescription();
        $summary['scorable'] = $this->isScorable();
        if ($result) {
            $summary['choices'] = $this->getChoicesListArray();
            $summary['response'] = $this->getDescriptiveResponses();
            $summary['raw-response'] = $this->getRawResponse();
            // Get Interaction type
        }
        // Get Verb
        $summary['verb'] = $this->getVerb();
        return $summary;
    }

    /**
     * Get student response array
     *
     * @return array
     */
    public function getResponses()
    {
        return explode('[,]', $this->getResult()->getResponse());
    }

    /**
     * Get descriptive student responses
     *
     * @return array
     */
    public function getDescriptiveResponses()
    {
        // student responses.
        $responses = $this->getResponses();
        $choices = $this->getChoicesListArray();
        $answers = [];
        foreach ($responses as $value) {
            $answers[] = $choices[$value];
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
}
