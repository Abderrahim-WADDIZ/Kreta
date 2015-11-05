/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import _ from 'lodash';
import Backbone from 'backbone';

import Config from './../Config';
import User from './../models/User';

class UserCollection extends Backbone.Collection {
  constructor(models, options = {}) {
    _.defaults(options, {
      model: User
    });
    super(models, options);
  }

  url() {
    return `${Config.baseUrl}/users`;
  }
}

export default UserCollection;
