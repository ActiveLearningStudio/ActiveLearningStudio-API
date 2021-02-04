<?php

namespace App\CurrikiGo\LRS;

use \TinCan\Statement;
use App\CurrikiGo\LRS\Interactions\ChoiceSummary;
use App\CurrikiGo\LRS\Interactions\FillInSummary;
use App\CurrikiGo\LRS\Interactions\TrueFalseSummary;
use App\CurrikiGo\LRS\Interactions\MatchingSummary;
use App\CurrikiGo\LRS\Interactions\LikertSummary;
use App\CurrikiGo\LRS\Interactions\NumericSummary;
use App\CurrikiGo\LRS\Interactions\OtherSummary;

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
            case 'sequencing':
                return new ChoiceSummary($statement);
            case 'fill-in':
            case 'long-fill-in':
                return new FillInSummary($statement);
            case 'true-false':
                return new TrueFalseSummary($statement);
            case 'matching':
                return new MatchingSummary($statement);
            case 'likert':
                return new LikertSummary($statement);
            case 'numeric':
                return new NumericSummary($statement);
            default:
                // When there is no interaction type
                return new OtherSummary($statement);
        }
    }
}
