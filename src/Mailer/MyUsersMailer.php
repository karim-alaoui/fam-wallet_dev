<?php
/**
 * Copyright 2010 - 2017, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2017, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace App\Mailer;

use Cake\Datasource\EntityInterface;
use CakeDC\Users\Mailer\UsersMailer;

/**
 * User Mailer
 *
 */
class MyUsersMailer extends UsersMailer
{
    /**
     * Send the templated email to the user
     *
     * @param EntityInterface $user User entity
     * @return void
     */
    protected function validation(EntityInterface $user)
    {
        $firstName = isset($user['first_name'])? $user['first_name'] . ', ' : '';
        // un-hide the token to be able to send it in the email content
        $user->setHidden(['password', 'token_expires', 'api_token']);
        $subject = __d('CakeDC/Users', '[Completion of provisional registration] Link for e-mail address verification sent');
        $this
             ->setTo($user['email'])
             ->setSubject($firstName . $subject)
             ->setViewVars($user->toArray());

        $this
            ->viewBuilder()->setTemplate('validation');
    }

    /**
     * Send the reset password email to the user
     *
     * @param EntityInterface $user User entity
     *
     * @return void
     */
    protected function resetPassword(EntityInterface $user)
    {
        $firstName = isset($user['first_name'])? $user['first_name'] . ', ' : '';
        $subject = __d('CakeDC/Users', '{0}Your reset password link', $firstName);
        // un-hide the token to be able to send it in the email content
        $user->setHidden(['password', 'token_expires', 'api_token']);

        $this
            ->setTo($user['email'])
            ->setSubject($subject)
            ->setViewVars($user->toArray());
        $this
            ->viewBuilder()
            ->setTemplate('resetPassword');
    }

    /**
     * Send account validation email to the user
     *
     * @param EntityInterface $user User entity
     * @param EntityInterface $socialAccount SocialAccount entity
     *
     * @return void
     */
    protected function socialAccountValidation(EntityInterface $user, EntityInterface $socialAccount)
    {
        $firstName = isset($user['first_name'])? $user['first_name'] . ', ' : '';
        // note: we control the space after the username in the previous line
        $subject = __d('CakeDC/Users', '{0}Your social account validation link', $firstName);
        $this
            ->setTo($user['email'])
            ->setSubject($subject)
            ->setViewVars(compact('user', 'socialAccount'));
        $this
            ->viewBuilder()
            ->setTemplate('CakeDC/Users.socialAccountValidation');
    }
}
