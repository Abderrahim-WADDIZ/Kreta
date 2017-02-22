/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import {combineReducers} from 'redux';
import {routeReducer as routing} from 'react-router-redux';
import {reducer as form} from 'redux-form';

import currentOrganization from './reducers/CurrentOrganization';
import currentProject from './reducers/CurrentProject';
import dashboard from './reducers/Dashboard';
import mainMenu from './reducers/MainMenu';
import notification from './reducers/Notification';
import organization from './reducers/Organization';
import profile from './reducers/Profile';
import projects from './reducers/Projects';
import user from './reducers/User';

export default combineReducers({
  currentOrganization,
  currentProject,
  dashboard,
  mainMenu,
  form,
  notification,
  organization,
  profile,
  projects,
  user,
  routing
});
