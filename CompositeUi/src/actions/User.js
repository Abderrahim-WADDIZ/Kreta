/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import {routeActions} from 'react-router-redux';

import ActionTypes from './../constants/ActionTypes';
import SecurityInstance from './../api/Security';

const Actions = {
  login: (credentialData) => (dispatch) => {
    dispatch({
      type: ActionTypes.USER_AUTHORIZING
    });
    SecurityInstance.login(credentialData.username, credentialData.password)
      .then((token) => {
        dispatch({
          type: ActionTypes.USER_AUTHORIZED,
          token
        });
        dispatch(
          routeActions.push('/')
        );
      });
  },
  logout: () => (dispatch) => {
    dispatch({
      type: ActionTypes.USER_UNAUTHORIZING
    });
    SecurityInstance.logout()
      .then(() => {
        dispatch({
          type: ActionTypes.USER_UNAUTHORIZED
        });
        dispatch(
          routeActions.push('/login')
        );
      });
  }
};

export default Actions;
