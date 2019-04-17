<?php

namespace app\modules\seguridad;

class Module extends \yii\base\Module
{

    const VERSION = '0.9.5';
    /** Email is changed right after user enter's new email address. */
    const STRATEGY_INSECURE = 0;
    /** Email is changed after user clicks confirmation link sent to his new email address. */
    const STRATEGY_DEFAULT = 1;
    /** Email is changed after user clicks both confirmation links sent to his old and new email addresses. */
    const STRATEGY_SECURE = 2;
    public $controllerNamespace = 'app\modules\seguridad\controllers';
    /** @var bool Whether to show flash messages. */
    public $enableFlashMessages = true;
    /** @var bool Whether to enable registration. */
    public $enableRegistration = true;
    /** @var bool Whether to remove password field from registration form. */
    public $enableGeneratingPassword = false;
    /** @var bool Whether user has to confirm his account. */
    public $enableConfirmation = true;
    /** @var bool Whether to allow logging in without confirmation. */
    public $enableUnconfirmedLogin = false;
    /** @var bool Whether to enable password recovery. */
    public $enablePasswordRecovery = true;
    /** @var int Email changing strategy. */
    public $emailChangeStrategy = self::STRATEGY_DEFAULT;
/** @var int The time you want the user will be remembered without asking for credentials. */
    public $rememberFor = 1209600;
/** @var int The time before a confirmation token becomes invalid. */
    public $confirmWithin = 86400; // two weeks
/** @var int The time before a recovery token becomes invalid. */
    public $recoverWithin = 21600; // 24 hours
    /** @var int Cost parameter used by the Blowfish hash algorithm. */
    public $cost = 10; // 6 hours
    /** @var array An array of administrator's usernames. */
    public $admins = [];
    /** @var array Mailer configuration */
    public $mailer = [];
    /** @var array Model map */
    public $modelMap = [];
    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'seguridad';

    public function init()
    {
        parent::init();
        $this->defaultRoute = 'roles';
        // custom initialization code goes here
    }

}
