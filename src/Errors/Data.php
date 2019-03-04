<?php

namespace TrueLayer\Errors;

class Data
{
    /**
     * Hold all our errors
     *
     * @var array
     */
    protected $errors = [
        'validation_error' => [
            'code' => 400,
            'message' => 'The supplied parameters are not valid.'
        ],
        'invalid_date_range' => [
            'code' => 400,
            'message' => 'The supplied parameters are not valid.'
        ],
        'wrong_credentials' => [
            'code' => 401,
            'message' => 'The credentials entered are incorrect.'
        ],
        'account_temporarily_locked' => [
            'code' => 403,
            'message' => 'The account is temporarily locked by the provider.'
        ],
        'account_permanently_locked' => [
            'code' => 403,
            'message' => 'The account is permanently locked by the provider.'
        ],
        'access_denied' => [
            'code' => 403,
            'message' => 'Access to the account has been revoked or expired.'
        ],
        'mfa_required' => [
            'code' => 403,
            'message' => 'Multi Factor Authentication required.'
        ],
        'user_input_required' => [
            'code' => 403,
            'message' => 'User input is required by the provider.'
        ],
        'account_not_found' => [
            'code' => 404,
            'message' => 'The requested account cannot be found.'
        ],
        'request_conflict' => [
            'code' => 409,
            'message' => 'This request is already running. Please try again later.'
        ],
        'wrong_bank' => [
            'code' => 410,
            'message' => 'The selected provider recognises the user within a different context.'
        ],
        'internal_server_error' => [
            'code' => 500,
            'message' => 'Internal server error.'
        ],
        'endpoint_not_supported' => [
            'code' => 501,
            'message' => 'Feature not supported by the provider.'
        ],
        'provider_error' => [
            'code' => 503,
            'message' => 'The provider service is unavailable.'
        ],
        'unknown' => [
            'code' => 500,
            'message' => 'An unknown error has occurred.'
        ]
    ];

    /**
     * Get our error by key
     *
     * @param $string
     * @return array
     */
    public function getError($string)
    {
        return array_key_exists($string, $this->errors) ?
            $this->errors[$string] :
            $this->errors['unknown'];
    }
}