<?php

/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kreta\Bundle\UserBundle\EventListener;

use Kreta\Bundle\UserBundle\Event\CookieEvent;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Cookie event listener class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class CookieListener
{
    /**
     * Checks if the session has the tokens to
     * create cookies that will be add into response.
     *
     * @param CookieEvent $event The cookie event
     */
    public function onCookieEvent(CookieEvent $event)
    {
        $session = $event->getSession();
        $accessToken = $session->get('access_token');
        $refreshToken = $session->get('refresh_token');

        $event->getResponse()->headers->setCookie($this->cookie('access_token', $accessToken));
        $event->getResponse()->headers->setCookie($this->cookie('refresh_token', $refreshToken));
    }

    /**
     * Creates cookie that its content is the given token.
     *
     * @param string $key   The name of token
     * @param string $value The value of token
     *
     * @return Cookie
     */
    private function cookie($key, $value)
    {
        return new Cookie($key, $value, 0, '/', null, false, false);
    }
}
