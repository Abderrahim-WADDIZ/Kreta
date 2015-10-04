/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

import Backbone from 'backbone';
import {Config} from '../config';
import {Participant} from '../models/participant';

export class ParticipantCollection extends Backbone.Collection {
  constructor(models, options = {}) {
    _.defaults(options, {
      model: Participant
    });
    super(models, options);
  }

  setProject(projectId) {
    this.url = `${Config.baseUrl}/projects/${projectId}/participants`;

    return this;
  }
}
