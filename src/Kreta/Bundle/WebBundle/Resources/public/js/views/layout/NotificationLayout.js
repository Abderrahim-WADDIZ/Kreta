/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import './../../../scss/layout/_notification';

import React from 'react';

import Notification from './../component/Notification';

export default React.createClass({
  getInitialState() {
    return {
      notifications: []
    };
  },
  componentDidMount() {
    App.collection.notification.on('add', this.updateNotifications);
    App.collection.notification.on('remove', this.updateNotifications);
  },
  updateNotifications() {
    this.setState({
      notifications: App.collection.notification.models
    });
  },
  render() {
    const notifications = this.state.notifications.map((notification, index) => {
      return (
        <Notification key={index}
                      message={notification.get('message')}
                      type={notification.get('type')}
                      value={index}/>
      );
    });

    return (
      <div className="notification-layout">
        {notifications}
      </div>
    );
  }
});
