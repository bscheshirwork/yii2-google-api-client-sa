A Yii2 wrapper for the official Google API PHP Client (In case used Service Account + DwD)
------------

For easily config and give you access to the service

Installation
------------

First you can register Google service account with DwD, user for this account and enable G suite access for this user id.
[api-client-library service-accounts](https://developers.google.com/api-client-library/php/auth/service-accounts)

Second - install wrapper and configure it use secret data.



The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Add

```
"bscheshirwork/yii2-google-api-client-sa": "*"
```

to the require section of your `composer.json` file.

This package will also install the [google/apiclient](http://github.com/google/apiclient) library.

Configuration
-------------

You application may use as much Google_Service instances as you need, by adding an entry into the `components` index of the Yii configuration array.

Here's how to setup GMail for example, a usage sample is provided below.

```php
    'components' => [
        // ..
        'gmail' => [
            'class' => 'bscheshirwork\gacsa\GoogleApiClient',
            'clientSecretPath' => '@runtime/secret-place/myprojectname-privatekeyshortdigits.json',
            'api' => Google_Service_Gmail::class,
        ],
```

This will enable you to access the GMail authenticated service `Yii::$app->gmail->getService()` in your application.

Usage
-----

**Displaying your newest message subject on GMail**

```php
/**
 * @var $service Google_Service_Gmail
 */
$service = Yii::$app->gmail->getService();

$messages = $service->users_messages->listUsersMessages('me', [
    'maxResults' => 1,
    'labelIds' => 'INBOX',
]);
$list = $messages->getMessages();


if (count($list) == 0) {
    echo "You have no emails in your INBOX .. how did you achieve that ??";
} else {
    $messageId = $list[0]->getId(); // Grab first Message

    $message = $service->users_messages->get('me', $messageId, ['format' => 'full']);

    $messagePayload = $message->getPayload();
    $headers = $messagePayload->getHeaders();

    echo "Your last email subject is: ";
    foreach ($headers as $header) {
        if ($header->name == 'Subject') {
            echo "<b>" . $header->value . "</b>";
        }
    }

}
```

Thanks [Mehdi Achour](https://github.com/machour/yii2-google-apiclient)
