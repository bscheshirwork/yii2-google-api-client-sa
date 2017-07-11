<?php

namespace bscheshirwork\gacsa;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use Google_Service;
use Google_Client;

/**
 * Class GoogleApiClient
 *
 */
class GoogleApiClient extends Component
{

    /**
     * @var Object|string The API class or name
     */
    public $api;

    /**
     * @var string The Google application credentials path for service account
     * @see https://developers.google.com/api-client-library/php/auth/service-accounts
     */

    public $googleApplicationCredentials;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (is_string($this->api)) {
            $ref = new \ReflectionClass($this->api);
            $this->api = $ref->newInstanceWithoutConstructor();
        }
        if (!is_subclass_of($this->api, 'Google_Service')) {
            throw new Exception("Not a Google_Service class: " . $this->api);
        }

        $this->googleApplicationCredentials = Yii::getAlias($this->googleApplicationCredentials);
        if (!file_exists($this->googleApplicationCredentials)) {
            throw new Exception("The Google application credentials file \"{$this->googleApplicationCredentials}\" does not exist! See https://developers.google.com/api-client-library/php/auth/service-accounts");
        }

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $this->googleApplicationCredentials);

    }

    /**
     * Gets a Google Service
     * @param null $callable function with signature function(Google_Client $client):Google_Client
     * @return Google_Service the child of Google_Service class
     */
    public function getService($callable = null)
    {
        return new $this->api(($callable ?? function ($client) {
                /** @var Google_Client $client */
                return $client;
            })($this->getClient()));
    }


    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    public function getClient()
    {
        //service account
        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();

        return $client;
    }

}
