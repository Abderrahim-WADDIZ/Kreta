/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import React from 'react';

class HelpText extends React.Component {
  static propTypes = {
    text: React.PropTypes.string.isRequired
  };

  render() {
    return (
      <p className="help-text">
        {this.props.text}
      </p>
    );
  }
}

export default HelpText;
