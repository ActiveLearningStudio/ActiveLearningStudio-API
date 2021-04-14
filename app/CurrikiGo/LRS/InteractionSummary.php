<?php

namespace App\CurrikiGo\LRS;

/**
 * Abstract class for Interaction Summary
 */
abstract class InteractionSummary
{
    
    /**
     * An XAPI statement
     * 
     * @var \Statement
     */
    protected $statement;

    /**
     * Get Statement
     *
     * @return \Statement
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * Get Target Object
     *
     * @return \Activity
     */
    public function getTarget()
    {
        return $this->statement->getTarget();
    }

    /**
     * Get the Object Definition
     *
     * @return \ActivityDefinition
     */
    public function getDefinition()
    {
        $target = $this->getTarget();
        return $target->getDefinition();
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        $definition = $this->getDefinition();
        $nameOfActivity = '';
        if (!empty($definition) && !$definition->getName()->isEmpty()) {
            $nameOfActivity = $definition->getName()->getNegotiatedLanguageString();
        }
        return strip_tags(html_entity_decode($nameOfActivity));
    }

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription()
    {
        $definition = $this->getDefinition();
        $description = '';
        if (!$definition->getDescription()->isEmpty()) {
            $description = $definition->getDescription()->getNegotiatedLanguageString();
        }
        return strip_tags(html_entity_decode($description));
    }

    /**
     * Get the interaction type
     *
     * @return string
     */
    public function getInteractionType()
    {
        $definition = $this->getDefinition();
        return $definition->getInteractionType();
    }

    /**
     * Get the correct response pattern.
     *
     * @return string
     */
    public function getCorrectResponsesPattern()
    {
        $definition = $this->getDefinition();
        return $definition->getCorrectResponsesPattern();
    }

    /**
     * Get statement result
     *
     * @return \Result
     */
    public function getResult()
    {
        return $this->statement->getResult();
    }

    /**
     * Whether an interaction is scorable or not.
     *
     * @return bool
     */
    public function isScorable()
    {
        return (!empty($this->getCorrectResponsesPattern()) ? true : false);
    }

    /**
     * Get the statement verb
     *
     * @return string
     */
    public function getVerb()
    {
        return $this->getStatement()->getVerb()->getDisplay()->getNegotiatedLanguageString();
    }

    /**
     * Get statement response
     *
     * @return mixed
     */
    public function getRawResponse()
    {
        return $this->getResult()->getResponse();
    }

    /**
     * Get library name
     *
     * @return mixed
     */
    public function getLibrary()
    {
        $contextActivities = $this->statement->getContext()->getContextActivities();
        $category = $contextActivities->getCategory();
        $categoryId = '';
        $h5pInteraction = '';
        if (!empty($category)) {
            $categoryId = end($category)->getId();
            $h5pInteraction = explode("/", $categoryId);
            $h5pInteraction = end($h5pInteraction);

            $pattern = "/H5P.(\w+)(-|\s)(.*)/";
            if (preg_match($pattern, $h5pInteraction, $matches)) {
                $h5pInteraction = $matches[1];
            }
        }
        return $h5pInteraction;
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
        $summary['library'] = $this->getLibrary();
        return $summary;
    }

    /**
     * Abstract method for getting descriptive student responses
     * Will be implemented by sub-classes
     * @return array
     */
    abstract public function getFormattedResponse();

    /**
     * Abstract method for the interaction choices
     * Will be implemented by sub-classes
     */
    abstract public function getChoicesListArray();

    /**
     * Abstract method for compnent list array
     * Will be implemented by sub-classes
     */
    abstract public function getComponentListArray();
    
}
