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
        if (!$definition->getName()->isEmpty()) {
            $nameOfActivity = $definition->getName()->getNegotiatedLanguageString();
        }
        return $nameOfActivity;
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
        return $description;
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
     * Abstract method for the interaction summary.
     * Will be implemented by sub-classes
     */
    abstract public function summary();
    
}
