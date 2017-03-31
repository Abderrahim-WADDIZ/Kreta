/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import {connect} from 'react-redux';
import React from 'react';

import UserActions from './../../actions/User';

import ContentLayout from './../layout/ContentLayout';
import ContentMiddleLayout from './../layout/ContentMiddleLayout';
import Login from './../form/Login';
import LogoCustomHeader from './../component/LogoCustomHeader';
import NotificationLayout from './../layout/NotificationLayout';

@connect()
class LoginPage extends React.Component {
  componentDidMount() {
    const
      {dispatch, location} = this.props,
      token = location.query['confirmation-token'];

    if (typeof token !== 'undefined' && token.length > 0) {
      dispatch(
        UserActions.enable(token)
      );
    }
  }

  login(credentials) {
    this.props.dispatch(UserActions.login(credentials));
  }

  render() {
    return (
      <div>
        <NotificationLayout/>
        <ContentLayout>
          <ContentMiddleLayout centered>
            <LogoCustomHeader title="Sign in to Kreta"/>
            <Login onSubmit={this.login.bind(this)}/>
          </ContentMiddleLayout>
        </ContentLayout>
      </div>
    );
  }
}

export default LoginPage;
