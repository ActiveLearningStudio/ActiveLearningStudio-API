<?php

namespace App\CurrikiGo\LRS\Interactions;

use App\CurrikiGo\LRS\InteractionSummaryInterface;
use App\CurrikiGo\LRS\InteractionSummary;
use \TinCan\Statement;

/**
 * True-False Interaction summary class
 */
class TrueFalseSummary extends InteractionSummary
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
     * Get formatted response
     *
     * @return string
     */
    public function getFormattedResponse()
    {
        // student response: Either true or false
        return $this->getRawResponse();
    }

    /**
     * Get component list array
     * It contains the correct response pattern, which shows the correct answers or sequence.
     * @return string
     */
    public function getComponentListArray()
    {
        // response pattern is an array of strings.
        return $this->getCorrectResponsesPattern();
    }

    /**
     * Get quiz choices array
     *
     * @return array
     */
    public function getChoicesListArray()
    {
        // This  Interaction type doesn't have a separate component list.
        // It has a fixed choice list.
        return ['true', 'false'];
    }
}
