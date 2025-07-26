<?php

namespace Sms4jawaly\Laravel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Gateway class provides methods to interact with the Jawaly SMS API.
 *
 * This class wraps common API endpoints such as fetching your account
 * balance, retrieving available sender names, and sending SMS messages.
 *
 * Example usage:
 * ```php
 * $gateway = app(\Sms4jawaly\Laravel\Gateway::class);
 * $balance = $gateway->getBalance();
 * $senders = $gateway->getSenders();
 * $response = $gateway->sendSms('Hello world', ['966500000000'], '4jawaly');
 * ```
 */
class Gateway
{
    /**
     * Base URL for the Jawaly SMS API.
     *
     * @var string
     */
    private const API_BASE_URL = 'https://api-sms.4jawaly.com/api/v1';

    /**
     * API key supplied by Jawaly.
     *
     * @var string
     */
    private $apiKey;

    /**
     * API secret supplied by Jawaly.
     *
     * @var string
     */
    private $apiSecret;

    /**
     * HTTP client used for making requests.
     *
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Gateway constructor.
     *
     * @param string $apiKey   Your Jawaly API key
     * @param string $apiSecret Your Jawaly API secret
     */
    public function __construct(string $apiKey, string $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->client = new Client([
            'base_uri' => self::API_BASE_URL,
            'headers' => $this->createHeaders(),
            'http_errors' => false,
        ]);
    }

    /**
     * Retrieve the current account balance.
     *
     * This method calls the packages endpoint with a filter for active SMS packages
     * of type 1. On success it returns the full decoded JSON response. On failure
     * it returns an error message in the `error` field.
     *
     * @return array{success:bool, data?:mixed, error?:string}
     */
    public function getBalance(): array
    {
        try {
            $response = $this->client->get('/account/area/me/packages', [
                'query' => [
                    'is_active' => 1,
                    'p_type'    => 1,
                ],
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            return [
                'success' => true,
                'data'    => $data,
            ];
        } catch (GuzzleException $e) {
            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Retrieve a list of sender names linked to your account.
     *
     * The API returns paginated sender lists. This method iterates through
     * all pages, collecting both all senders and those marked as default.
     *
     * @return array{
     *   success:bool,
     *   all_senders?:string[],
     *   default_senders?:string[],
     *   message?:string,
     *   error?:string
     * }
     */
    public function getSenders(): array
    {
        try {
            $allSenders = [];
            $defaultSenders = [];
            $page = 1;

            do {
                $response = $this->client->get('/account/area/senders', [
                    'query' => ['page' => $page],
                ]);
                $data = json_decode($response->getBody()->getContents(), true);
                $items = $data['items'] ?? [];

                if (!empty($items['data'])) {
                    foreach ($items['data'] as $item) {
                        $senderName = $item['sender_name'];
                        $allSenders[] = $senderName;
                        if (isset($item['is_default']) && $item['is_default'] === 1) {
                            $defaultSenders[] = $senderName;
                        }
                    }
                }
                $page++;
            } while (isset($items['last_page']) && $page <= $items['last_page']);

            return [
                'success'        => true,
                'all_senders'    => $allSenders,
                'default_senders'=> $defaultSenders,
                'message'        => 'تم',
            ];
        } catch (GuzzleException $e) {
            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Send SMS messages to one or more recipients.
     *
     * The API accepts an array of messages, each containing a text, list of
     * numbers, and sender name. This method wraps the call and returns
     * aggregated results including job IDs and counts of successes/failures.
     *
     * @param string $message The message text to send
     * @param array  $numbers Array of recipient phone numbers
     * @param string $sender  The sender name to use
     *
     * @return array{
     *   success:bool,
     *   total_success:int,
     *   total_failed:int,
     *   job_ids:string[],
     *   errors:array<string,array>
     * }
     */
    public function sendSms(string $message, array $numbers, string $sender): array
    {
        $result = [
            'success'       => true,
            'total_success' => 0,
            'total_failed'  => 0,
            'job_ids'       => [],
            'errors'        => [],
        ];

        try {
            $response = $this->client->post('/account/area/sms/send', [
                'json' => [
                    'messages' => [
                        [
                            'text'    => $message,
                            'numbers' => $numbers,
                            'sender'  => $sender,
                        ],
                    ],
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $result['total_success'] = count($numbers);
            if (isset($data['job_id'])) {
                $result['job_ids'][] = $data['job_id'];
            }
        } catch (GuzzleException $e) {
            $result['success'] = false;
            $result['total_failed'] = count($numbers);
            $result['errors'][$e->getMessage()] = $numbers;
        }

        return $result;
    }

    /**
     * Build authorization and content headers for the API.
     *
     * @return array<string,string>
     */
    private function createHeaders(): array
    {
        return [
            'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->apiSecret),
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ];
    }
}