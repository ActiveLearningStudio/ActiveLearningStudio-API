<?php

namespace App\CurrikiGo\LRS\Interactions;

use App\CurrikiGo\LRS\InteractionSummaryInterface;
use App\CurrikiGo\LRS\InteractionSummary;
use \TinCan\Statement;

/**
 * Other interations summary class
 * Defines methods for when there is no interaction.
 */
class OtherSummary extends InteractionSummary// implements InteractionSummaryInterface
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
            $summary['response'] = $this->getFormattedResponse();
            $summary['raw-response'] = $this->getRawResponse();
            $summary['choices'] = $this->getChoicesListArray();
            $summary['correct-pattern'] = $this->getComponentListArray();
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
