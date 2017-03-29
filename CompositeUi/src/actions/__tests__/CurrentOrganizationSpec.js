/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import configureMockStore from 'redux-mock-store';
import CurrentOrganizationAction from '../../actions/CurrentOrganization';
import TaskManagerGraphQl from './../../api/graphql/TaskManagerGraphQl';
import thunk from 'redux-thunk';

const middlewares = [ thunk ];
const mockStore = configureMockStore(middlewares);
const fetchMock = require('fetch-mock');

describe('Current organization actions', () => {
  it('fetch organization', () => {

      TaskManagerGraphQl.query = jest.fn().mockImplementation(() =>
          Promise.resolve(
              mockResponse(200, null, '{"id":"1234"}')
          )
      );
      const store = mockStore({ concerts: [] });
      return store.dispatch(CurrentOrganizationAction.fetchOrganization()).then(() => {
          expect(store.getActions()).toEqual(expectedAction)
      });
  });
});
