/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

import {BaseRouter} from 'router/base';
import {IssueRouter} from 'router/issue';
import {ProjectRouter} from 'router/project';

import {ProjectController} from 'controllers/project';
import {IssueController} from 'controllers/issue';

import {Profile} from 'models/profile';

import {BaseLayoutView} from 'views/layout/base';
import {HeaderView} from 'views/layout/mainMenu';

export class App extends Backbone.Marionette.Application {
  initialize() {
    this.router = {
      base: new BaseRouter(),
      issue: new IssueRouter(),
      project: new ProjectRouter()
    };

    this.controller = {
      issue: new IssueController(),
      project: new ProjectController()
    };

    this.initializeGlobalShortcuts();
    this.addAutenticationHeader();
  }

  loadLayout() {
    this.currentUser = new Profile();
    this.currentUser.fetch();

    this.layout = new BaseLayoutView();
    this.layout.render();

    new HeaderView();
  }

  initializeGlobalShortcuts() {
    Mousetrap.bind('p', () => {
      this.router.base.navigate('/projects', true);
    });

    Mousetrap.bind('n', () => {
      this.router.base.navigate('/issue/new', true);
    });
  }
  addAutenticationHeader() {
    Backbone.$.ajaxSetup({
      headers: {'Authorization': 'Bearer ' + this.getAccessToken()}
    });
  }

  getBaseUrl () {
    var host = location.hostname + (location.port != "" ? ":" + location.port : '');
    return '//' + host + '/api';
  }

  getCookie(name) {
    var value = '; ' + document.cookie;
    var parts = value.split('; ' + name + '=');
    if (parts.length === 2) {
      return parts.pop().split(';').shift();
    }
  }

  getAccessToken() {
    return this.getCookie('access_token');
  }
}
