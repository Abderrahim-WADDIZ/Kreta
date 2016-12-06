/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import Config from './../Config';

class Security {
  login(username, password) {
    return new Promise((resolve, reject) => {
      fetch(`${Config.identityAccessUrl}/auth/token`, {
        headers: {
          'Authorization': `Basic ${btoa(`${username}:${password}`)}`,
        },
        method: 'POST'
      }).then((response) => {
        if (response.ok) {
          resolve(response.json());
        } else {
          reject(response.json());
        }
      });
    });
  }

  logout() {
    return new Promise((resolve) => {
      localStorage.removeItem('token');

      setTimeout(() => {
        resolve();
      }, 600);
    });
  }

  isLoggedIn() {
    return !!localStorage.token;
  }
}

const SecurityInstance = new Security();

export default SecurityInstance;
