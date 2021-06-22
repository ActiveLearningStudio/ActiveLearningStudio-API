<?php

namespace App\Services\CurrikiGo;

/**
 * Interface for the Generic LMS Integration Service
 */
interface LTIConsumerServiceInterface
{

    /**
     * Launch LTI 1.0 request.
     * 
     * @param array $launchData
     * @return string
     */
	public function launch($launchData);
    
}
