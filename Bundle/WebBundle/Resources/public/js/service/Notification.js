/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

import {NotificationView} from '../views/component/Notification';

export class NotificationService {
  static showNotification(notification) {
    App.collection.notification.add(notification);
  }
}
