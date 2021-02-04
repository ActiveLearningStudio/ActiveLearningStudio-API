<?php

namespace App\CurrikiGo\LRS\Interactions;

use App\CurrikiGo\LRS\InteractionSummaryInterface;
use App\CurrikiGo\LRS\InteractionSummary;
use \TinCan\Statement;

/**
 * Other interations summary class
 * Defines methods for when there is no interaction.
 */
class OtherSummary extends InteractionSummary
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
        // student responses.
        $response = $this->getRawResponse();
        // Check if it's a scorable type
        if ($this->isScorable()) {
            // it's a good possibility that the responses are concatenated by [,]
            return explode('[,]', $this->getRawResponse());
        }
        return $response;
    }

    /**
     * Get component list array
     * It contains the correct response pattern, which shows the correct answers or sequence.
     * @return string
     */
    public function getComponentListArray()
    {
        // there is no response pattern
        return [];
    }

    /**
     * Get quiz choices array
     *
     * @return array
     */
    public function getChoicesListArray()
    {
        // This  Interaction type doesn't have a separate component list.
        return [];
    }
}
