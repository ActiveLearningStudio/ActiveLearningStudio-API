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
            if ($result->getScore()) {
                $summary['choices'] = $this->getChoicesListArray();
                $summary['correct-pattern'] = $this->getComponentListArray();
                $summary['score'] = [
                    'raw' => $result->getScore()->getRaw(),
                    'max' => $result->getScore()->getMax(),
                    'scaled' => $result->getScore()->getScaled(),
                ];
                $summary['duration'] = xAPIFormatDuration($result->getDuration());
            } else {
                $summary['score'] = [
                    'raw' => 0,
                    'max' => 0,
                    'scaled' => 0,
                ];
                $summary['duration'] = '00:00';
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
     * Get compnent list array
     * It contains the correct response pattern, which shows the correct answers or sequence.
     * @return string
     */
    public function getComponentListArray()
    {
        // response pattern is an array of strings.
        $responsePattern = $this->getCorrectResponsesPattern();
        $return = [];
        // Check if it's a scorable type
        foreach ($responsePattern as $pattern) {
            $return[] = str_replace('[,]', ' | ', $pattern);
        }
        return $return;
    }

    /**
     * Get quiz choices array
     *
     * @return array
     */
    public function getChoicesListArray()
    {
        // This  Interaction type doesn't have a separate component list.
        // it uses correct response pattern instead.
        return $this->getComponentListArray();
    }
}
