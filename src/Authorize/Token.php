<?php

namespace TrueLayer\Authorize;

use DateInterval;
use DateTime;

class Token
{
    /**
     * Hold the parts of our token
     *
     * @var string
     */
    protected $access_token;
    protected $expires_in;
    protected $token_type;
    protected $refresh_token;
    /* @var DateTime */
    protected $issued_at;
    protected $issued_at_unformatted;

    /**
     * Build our token
     *
     * @param array $token
     * @throws \Exception
     */
    public function __construct(array $token)
    {
        $this->access_token = $token['access_token'];
        $this->expires_in = $token['expires_in'];
        $this->token_type = $token['token_type'];

        $this->refresh_token = isset($token['refresh_token']) ?
            $token['refresh_token'] :
            null;

        $this->issued_at_unformatted = isset($token['issued_at']) ?
            (new DateTime($token['issued_at'])) :
            (new DateTime);

        $this->issued_at = $this->issued_at_unformatted->format(DateTime::ATOM);
    }

    /**
     * Get access token
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * Get refresh token
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    /**
     * Is our token expired
     *
     * @return bool
     * @throws \Exception
     */
    public function isExpired()
    {
        $interval = new DateInterval("PT" . $this->expires_in . "S");
        $expires = (clone $this->issued_at_unformatted)
            ->add($interval);

        return $expires < (new DateTime);
    }

    /**
     * Are we refreshable?
     *
     * @return bool
     */
    public function isRefreshable()
    {
        return (bool)$this->refresh_token;
    }
}
