<?php

namespace bscheshirwork\gacsa;

use Google_Client;
use Yii;
use yii\base\Component;
use yii\base\Exception;

/**
 * Class GoogleApiClient
 *
 */
class GoogleApiClient extends Component
{
    /**
     * @var string Your application name
     */
    public $applicationName = 'My Application';

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
     *
     * @return Google_Client the authorized client object
     * @throws Exception
     */
    public function getService() {
        return new $this->api($this->getClient());
    }

    /**
     * Returns an authorized API client.
     * $user_to_impersonate = 'email@suitedomain.com';
     * masquerade impersonate
     * $client->setSubject($user_to_impersonate);
     *
     * @return Google_Client the authorized client object
     */
    public function getClient() {

        //service account
        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();

        return $client;
    }

}
