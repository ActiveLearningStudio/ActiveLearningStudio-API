<?php

namespace App\CurrikiGo\LRS\Interactions;

use App\CurrikiGo\LRS\InteractionSummaryInterface;
use App\CurrikiGo\LRS\InteractionSummary;
use \TinCan\Statement;

/**
 * Fill-in Interaction summary class
 */
class FillInSummary extends InteractionSummary// implements InteractionSummaryInterface
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
            $summary['response'] = $this->getFormattedResponse();
            $summary['raw-response'] = $this->getRawResponse();
        }
        // Get Verb
        $summary['verb'] = $this->getVerb();
        return $summary;
    }

    /**
     * Get formatted response
     *
     * @return string
     */
    public function getFormattedResponse()
    {
        // student responses.
        $response = $this->getRawResponse();
        return $response;
    }
}
