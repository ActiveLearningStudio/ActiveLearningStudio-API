<?php

namespace App\CurrikiGo\LRS;

use \TinCan\Statement;
use App\CurrikiGo\LRS\Interactions\ChoiceSummary;
use App\CurrikiGo\LRS\Interactions\FillInSummary;

/**
 * Factory Class for Interactions
 */
class InteractionFactory
{
    
    /**
     * Initialize and return an interaction object.
     *
     * @param Statement $statement
     * @return InteractionSummary|null
     */
    public function initInteraction(Statement $statement)
    {
        $target = $statement->getTarget();
        $definition = $target->getDefinition();
        $interactionType = $definition->getInteractionType();
        switch ($interactionType) {
            case 'choice':
                return new ChoiceSummary($statement);
            case 'fill-in':
                return new FillInSummary($statement);
            default:
                return null;
        }
    }
}
