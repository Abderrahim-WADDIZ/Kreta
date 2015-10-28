/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import '../../../scss/components/_notification.scss';

import classnames from 'classnames';
import React from 'react';

export default React.createClass({
  propTypes: {
    message: React.PropTypes.string.isRequired,
    type: React.PropTypes.string,
    value: React.PropTypes.number.isRequired
  },
  getDefaultProps() {
    return {
      type: 'success'
    };
  },
  onCloseClick() {
    App.collection.notification.remove(
      App.collection.notification.at(this.props.value)
    );
  },
  render() {
    const classes = classnames({
      'notification': true,
      'notification--visible': true,
      'notification--error': this.props.type === 'error'
    });

    return (
      <div className={classes}>
        <div className="notification__icon">
          <i className="fa fa-exclamation-circle"></i>
        </div>
        <p className="notification__message">{this.props.message}</p>
        <i className="notification__hide fa fa-times"
           onClick={this.onCloseClick}></i>
      </div>
    );
  }
});
