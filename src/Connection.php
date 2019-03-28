<?php

namespace TrueLayer;

use DateTime;
use GuzzleHttp\Client;
use Teapot\StatusCode\Http;
use TrueLayer\Authorize\Token;
use TrueLayer\Exceptions\InvalidCodeExchange;
use TrueLayer\Data\Status;

class Connection
{
    /**
     * TrueLayer settings
     *
     * @const mixed
     */
    const AUTH_PATH = "https://auth.truelayer.com";
    const API_PATH = "https://api.truelayer.com";
    const STATUS_URI = "https://status-api.truelayer.com/api/v1/data/status";
    const DATA_PATH = "data";
    const API_VERSION = "v1";

    /**
     * Hold our API connection
     *
     * @var Client
     */
    protected $connection;

    /**
     * Hold our token and secret
     *
     * @var string
     */
    protected $client_id;
    protected $client_secret;
    protected $redirect_uri;
    protected $request_uri;
    protected $access_token;
    protected $scope;
    protected $state;

    /**
     * Set values and start a guzzle
     *
     * @param $client_id string
     * @param $client_secret string
     * @param $request_uri
     * @param array $scope
     * @param null $state
     */
    public function __construct(
        $client_id,
        $client_secret,
        $request_uri,
        $scope = [],
        $state = null
    ) {
        $this->connection = new Client;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->request_uri = $request_uri;
        $this->scope = $scope;
        $this->state = $state;
    }

    /**
     * Get client id
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * Get client id
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->client_secret;
    }

    /**
     * Get request uri
     *
     * @return string
     */
    public function getRequestUri()
    {
        return $this->request_uri;
    }

    /**
     * Get token url
     *
     * @return string
     */
    public function getTokenUrl()
    {
        return self::AUTH_PATH . "/connect/token";
    }

    /**
     * Get connection
     *
     * @return string
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Get a data path
     *
     * @param string $path
     * @return string
     */
    public function getUrl($path = "/")
    {
        return self::API_PATH . $path;
    }

    /**
     * Get a Bearer header
     *
     * @return array
     */
    public function getBearerHeader()
    {
        return [
            'Authorization' => "Bearer " . $this->access_token
        ];
    }

    /**
     * Generate a nonce
     *
     * @return string
     * @throws \Exception
     */
    public function getNonce()
    {
        return hash(
            'sha256',
            (new DateTime())->format(DateTime::ATOM)
        );
    }

    /**
     * Get our state
     *
     * @return string
     */
    public function getState()
    {
        $this->state = $this->state ? $this->state : uniqid("tl", true);
        return $this->state;
    }

    /**
     * Get our scope
     *
     * @return string
     */
    public function getScope()
    {
        return implode("%20", (count($this->scope) > 0 ? $this->scope : [
            "info",
            "accounts",
            "balance",
            "cards",
            "transactions",
            "offline_access"
        ]));
    }


    /**
     * Build an Authorization Link
     *
     * @return string
     * @throws \Exception
     */
    public function getAuthorizationLink()
    {

        return self::AUTH_PATH . "/" .
            "?response_type=code" .
            "&client_id=" . $this->getClientId() .
            "&nonce=" . $this->getNonce() .
            "&scope=" . $this->getScope() .
            "&redirect_uri=" . $this->getRequestUri() .
            "&state=" . urlencode($this->getState()) .
            "&enable_mock=true" .
            "&enable_oauth_providers=true" .
            "&enable_open_banking_providers=false" .
            "&enable_credentials_sharing_providers=true" .
            "&response_mode=form_post";
    }

    /**
     * Get oauth token from code
     *
     * @param $code
     * @return Token
     * @throws InvalidCodeExchange
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getOauthToken($code)
    {
        $result = $this->connection->request(
            'POST',
            $this->getTokenUrl(),
            [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => $this->getClientId(),
                    'client_secret' => $this->getClientSecret(),
                    'redirect_uri' => $this->getRequestUri(),
                    'code' => $code
                ]
            ]
        );

        if ((int) $result->getStatusCode() > Http::BAD_REQUEST) {
            throw new InvalidCodeExchange;
        }

        $token = json_decode($result->getBody(), true);

        return new Token($token);
    }

    /**
     * Refresh an auth token
     *
     * @param Token $token
     * @return Token
     * @throws InvalidCodeExchange
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function refreshOauthToken(Token $token)
    {
        $result = $this->connection->request(
            'POST',
            $this->getTokenUrl(),
            [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => $this->getClientId(),
                    'client_secret' => $this->getClientSecret(),
                    'refresh_token' => $token->getRefreshToken()
                ]
            ]
        );

        if ((int) $result->getStatusCode() > Http::BAD_REQUEST) {
            throw new InvalidCodeExchange;
        }

        $token = json_decode($result->getBody(), true);

        return new Token($token);
    }

    /**
     * A get proxy which adds our token
     *
     * @param string $path
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($path = "/", $params = [])
    {
        $result = $this->connection
            ->request(
                "GET",
                $this->getUrl($path),
                [
                    'headers' => ((bool)$this->access_token ?
                        $this->getBearerHeader() :
                        []
                    ),
                    'query' => $params
                ]
            );

        return $result;
    }

    /**
     * Set out access_token
     *
     * @param $token
     * @return Connection
     */
    public function setAccessToken($token)
    {
        $this->access_token = $token;

        return $this;
    }

    /**
     * A function to get our statuses for
     * each bank for the last 24 hours
     * 
     * @param DateTime $from
     * @param DateTime $to
     * @param array $providers
     * @return array|Status
     */
    public function getAvailability(
        DateTime $from = null, 
        DateTime $to = null,
        array $providers = []
    ) {
        $from = $from ? $from : new DateTime("-24 hours");
        $to  = $to ? $to : new DateTime();
        $providers = $providers ? $providers : null;

        $result = $this->connection->request(
            'GET',
            self::STATUS_URI,
            [
                'query' => array_filter([
                    'from' => $from->format(DateTime::ISO8601),
                    'to' => $to->format(DateTime::ISO8601),
                    'providers' => $providers
                ])
            ]
        );

        if ((int) $result->getStatusCode() > Http::BAD_REQUEST) {
            throw new InvalidCodeExchange;
        }

        $availability = [];
        $results = json_decode($result->getBody(), true);
 
        foreach($results['results'] as $result) {
            foreach($result['providers'] as $name => $p){
                $status = new Status();
                $status->name = $p['provider_id'];
                foreach($p['endpoints'] as $ep) {
                    if($ep['endpoint'] === "accounts") {          
                        $status->accounts = $ep['availability'];
                    }

                    if($ep['endpoint'] === "accounts/transactions") {
                        $status->transactions = $ep['availability'];
                    }

                    if($ep['endpoint'] === "cards") {
                        $status->cards = $ep['availability'];
                    }

                    if($ep['endpoint'] === "info") {
                        $status->pii = $ep['availability'];
                    }
                }
                $availability[$status->name] = $status;
            }
        }

        return $availability;
    }
}
