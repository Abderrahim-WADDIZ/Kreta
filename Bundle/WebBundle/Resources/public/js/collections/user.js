/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

import {Config} from '../config';
import {User} from '../models/user';

export class UserCollection extends Backbone.Collection {
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
