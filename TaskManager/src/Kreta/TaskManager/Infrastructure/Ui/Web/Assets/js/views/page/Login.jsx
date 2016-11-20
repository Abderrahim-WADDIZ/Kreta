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

import Login from './../form/Login';
import LogoHeader from './../component/LogoHeader';

import ContentLayout from './../layout/ContentLayout';
import ContentMiddleLayout from './../layout/ContentMiddleLayout';

export default class extends React.Component {
  login(credentials) {
    console.log(`Login tried by ${credentials.username}`);
  }

  render() {
    return (
      <ContentLayout>
        <ContentMiddleLayout>
          <LogoHeader/>
          <Login onSubmit={this.login.bind(this)}/>
        </ContentMiddleLayout>
      </ContentLayout>
    );
  }
}
