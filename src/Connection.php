<?php

namespace TrueLayer;

use DateTime;
use GuzzleHttp\Client;
use Teapot\StatusCode\Http;
use TrueLayer\Authorize\Token;
use TrueLayer\Banking\AbstractResolver;
use TrueLayer\Banking\DataResolver;
use TrueLayer\Exceptions\InvalidCodeExchange;
use TrueLayer\Data\Status;
use TrueLayer\Exceptions\UnresolvableResult;

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
    const SANDBOX_AUTH_PATH = "https://auth.truelayer-sandbox.com";
    const SANDBOX_API_PATH = "https://api.truelayer-sandbox.com";
    const SANDBOX_STATUS_URI = "https://status-api.truelayer-sandbox.com/api/v1/data/status";
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
    protected $data_resolver;
    protected $sandbox;
    protected $provider;

    /**
     * Set values and start a guzzle
     *
     * @param $client_id string
     * @param $client_secret string
     * @param $request_uri
     * @param array $scope
     * @param null $state
     * @param string $data_resolver
     * @param string $provider
     */
    public function __construct(
        $client_id,
        $client_secret,
        $request_uri,
        $scope = [],
        $state = null,
        $data_resolver = DataResolver::class,
        $provider = null,
        $sandbox = false
    ) {
        $this->connection = new Client;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->request_uri = $request_uri;
        $this->scope = $scope;
        $this->state = $state;
        $this->data_resolver = new $data_resolver();
        $this->provider = $provider;
        $this->sandbox = $sandbox;
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
        $auth_path = ($this->sandbox) ? self::SANDBOX_AUTH_PATH : self::AUTH_PATH;
        return $auth_path . "/connect/token";
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
        $api_path = ($this->sandbox) ? self::SANDBOX_API_PATH : self::API_PATH;
        return $api_path . $path;
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
        $auth_path = ($this->sandbox) ? self::SANDBOX_AUTH_PATH : self::AUTH_PATH;
        $url = $auth_path . "/" .
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

        if ($this->provider) {
            $url .= "&provider_id=" . $this->provider;
        }

        return $url;
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
     * @return string
     */
    public function getDataResolver()
    {
        return $this->data_resolver;
    }

    /**
     * @param AbstractResolver $resolver
     * @return Connection
     */
    public function setDataResolver(AbstractResolver $resolver)
    {
        $this->data_resolver = $resolver;

        return $this;
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
     * Set our provider if known
     *
     * @param $provider
     * @return Connection
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
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
     * @throws InvalidCodeExchange
     * @throws UnresolvableResult
     * @throws \GuzzleHttp\Exception\GuzzleException
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

        return $this->resolve(json_decode($result->getBody(), true), __FUNCTION__);
    }

    /**
     * @param array $results
     * @param string $function
     * @return mixed
     * @throws UnresolvableResult
     */
    public function resolve(array $results, $function)
    {
        if (false === method_exists($this->data_resolver, $function)) {
            throw new UnresolvableResult($function);
        }

        return $this->data_resolver->{$function}($results);
    }
}
