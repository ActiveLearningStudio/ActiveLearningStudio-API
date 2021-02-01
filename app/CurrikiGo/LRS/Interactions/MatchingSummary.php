<?php

namespace App\CurrikiGo\LRS\Interactions;

use App\CurrikiGo\LRS\InteractionSummaryInterface;
use App\CurrikiGo\LRS\InteractionSummary;
use \TinCan\Statement;

/**
 * Matching Interaction summary class
 */
class MatchingSummary extends InteractionSummary// implements InteractionSummaryInterface
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
        // student response
        // A list of matching pairs, where each pair consists of a source item id followed by a target item id. 
        // Items can appear in multiple (or zero) pairs. 
        // Items within a pair are delimited by [.]. Pairs are delimited by [,].
        $response = $this->getRawResponse();
        return $this->formatMatchingResponse($response);
    }

    /**
     * Get component list array
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
            $return[] = $this->formatMatchingResponse($responsePattern);
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
        $source = $this->prepareMatchingChoiceList($this->getRawSource());
        $target = $this->prepareMatchingChoiceList($this->getRawTarget());
        $choices[] = '{source} ' . implode(" | ", $source);
        $choices[] = '{target} ' . implode(" | ", $target);
        return $choices;
    }

    /**
     * Get raw quiz choices
     *
     * @return array
     */
    private function getRawSource()
    {
        $definition = $this->getDefinition();
        return $definition->getSource();
    }

    /**
     * Get raw quiz choices
     *
     * @return array
     */
    private function getRawTarget()
    {
        $definition = $this->getDefinition();
        return $definition->getTarget();
    }

    /**
     * Prepare choice list in an array.
     * 
     * @param array $list
     * @param string $languageKey Defaults to en-US
     * @return array
     */
    private function prepareMatchingChoiceList($list, $languageKey = 'en-US')
    {
        if (!is_array($list)) {
            return $list;
        }
        $return = [];
        foreach ($list as $values) {
            $return[$values['id']] = trim($values['description'][$languageKey]);
        }
        return $return;
    }

    /**
     * Get formatted Match pattern response
     *
     * @return string
     */
    private function formatMatchingResponse($response)
    {
        // student response
        // A list of matching pairs, where each pair consists of a source item id followed by a target item id. 
        // Items can appear in multiple (or zero) pairs. 
        // Items within a pair are delimited by [.]. Pairs are delimited by [,].
        $answer = [];
        $source = $this->prepareMatchingChoiceList($this->getRawSource());
        $target = $this->prepareMatchingChoiceList($this->getRawTarget());
        // Check if it's a scorable type
        if ($this->isScorable()) {
            $pairs = explode('[,]', $this->getRawResponse()); 
            // for response, target is listed first and source as second.
            if (!empty($pairs)) {
                foreach($pairs as $pair) {
                    $items = explode('[.]', $pair);
                    $answer[] = $source[$items[0]] . ' | ' . $target[$items[1]];
                }
                return $answer;
            }
        }
        return $response;
    }
}
