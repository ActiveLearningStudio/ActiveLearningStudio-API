<?php

namespace App\CurrikiGo\LRS\Interactions;

use App\CurrikiGo\LRS\InteractionSummaryInterface;
use App\CurrikiGo\LRS\InteractionSummary;
use \TinCan\Statement;

/**
 * Matching Interaction summary class
 */
class MatchingSummary extends InteractionSummary
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
        if (!empty($responsePattern)) {
            // Check if it's a scorable type
            foreach ($responsePattern as $pattern) {
                $return[] = $this->formatMatchingResponse($pattern);
            }
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
        $choices = [];
        if (!empty($source)) {
            $choices[] = '{source} ' . implode(" | ", $source);
        }
        if (!empty($target)) {
            $choices[] = '{target} ' . implode(" | ", $target);
        }
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
        if (empty($response)) {
            return $answer;
        }
        $source = $this->prepareMatchingChoiceList($this->getRawSource());
        $target = $this->prepareMatchingChoiceList($this->getRawTarget());
        $pairs = explode('[,]', trim($response));
       
        // for response, target is listed first and source as second.
        if (!empty($pairs) && !empty($source) && !empty($target)) {
            foreach($pairs as $pair) {
                // "correctResponsesPattern": [
                // "0[.]2[,]0[.]1[,]0[.]0[,]1[.]3[,]1[.]4[,]1[.]5"
                // ], 
                // target[.]source[,]target[.]source
                // Although, it should be the other way around; as per XAPI docs
                // A list of matching pairs, where each pair consists of a source item id followed by a target item id.
                // Items can appear in multiple (or zero) pairs. Items within a pair are delimited by [.]. Pairs are delimited by [,].
                // H5P has it wrong... @TODO
                $items = explode('[.]', $pair);
                $answer[] = (isset($target[$items[0]]) ? $target[$items[0]] : '') . ' | ' . (isset($source[$items[1]]) ? $source[$items[1]] : '');
            }
        }
        return $answer;
    }
}
