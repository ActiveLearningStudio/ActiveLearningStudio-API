<?php

namespace App\Listeners;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class UserRegistrationListener
{
    private $client;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Handle the given event.
     *
     * @param Registered $event
     * @return void
     * @throws GuzzleException
     */
    public function handle(Registered $event)
    {
        $data = [
            'properties' => [
                [
                    'property' => 'email',
                    'value' => $event->user->email,
                ],
                [
                    'property' => 'firstname',
                    'value' => $event->user->first_name . ' ' . $event->user->last_name,
                ],
                [
                    'property' => 'memberships',
                    'value' => 'CurrikiStudio',
                ],
            ],
        ];

        $hubspot_url = 'https://api.hubapi.com/contacts/v1/contact';
        $headers = [
            'Accept' => 'application/json',
        ];
        $query = [
            'hapikey' => '68896ae2-0832-43d3-ae55-9852086ca0ef',
        ];

        try {
            $this->client->request(
                'POST',
                $hubspot_url,
                [
                    'headers' => $headers,
                    'query' => $query,
                    'json' => $data,
                ]
            );
        } catch (\Exception $e) {
            Log::error('HubSpot Registration Error: ');
            Log::error($e);
        }
    }
}
