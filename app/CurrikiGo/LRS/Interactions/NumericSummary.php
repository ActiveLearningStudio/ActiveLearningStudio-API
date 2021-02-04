<?php

namespace App\CurrikiGo\LRS\Interactions;

use App\CurrikiGo\LRS\InteractionSummaryInterface;
use App\CurrikiGo\LRS\InteractionSummary;
use \TinCan\Statement;

/**
 * Numeric Interaction summary class
 */
class NumericSummary extends InteractionSummary
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
        // student response
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
        $return = [];
        $responsePattern = $this->getCorrectResponsesPattern();
        if (!empty($responsePattern)) {
            // Check if it's a scorable type
            foreach ($responsePattern as $pattern) {
                $range = explode("[:]", $pattern);
                $return[] = ($range[0] !== '' ? 'minimum '. $range[0] : '') . ($range[1] !== '' ? ($range[0] !== '' ? ', ' : '') . 'maximum '. $range[1] : '');
            }
        }
        return $return;
    }

    /**
     * Get fill-in choices array
     *
     * @return array
     */
    public function getChoicesListArray()
    {
        // This Interaction type doesn't have a separate component list.
        // it uses correct response pattern instead.
        return $this->getComponentListArray();
    }
}
