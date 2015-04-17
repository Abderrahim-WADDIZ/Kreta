/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

import {IssueShowView} from '../views/page/issue/show';
import {CreateIssueView} from 'views/main/createIssue';

export class IssueController extends Backbone.Controller {
  initialize() {
    this.routes = {
      'issue/:id': 'showAction',
      'issue/new': 'newAction'
    };
  }

  newAction() {
    var view = new CreateIssueView();
    App.views.main.render(view.render().el);
  }

  showAction(id) {
    var view = new IssueShowView({id: id});
    view.show();
  }
}