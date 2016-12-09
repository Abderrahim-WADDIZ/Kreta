/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import ActionTypes from './../constants/ActionTypes';

const initialState = {
  fetching: true,
  organizations: []
};

export default function reducer(state = initialState, action = {}) {
  switch (action.type) {
    case ActionTypes.ORGANIZATION_CREATING:
      return { ...state, fetching: true };

    case ActionTypes.ORGANIZATION_CREATED:
      return { ...state, fetching: false, organizations: [...state.organizations, action.organization]};

    default:
      return state;
  }
}
