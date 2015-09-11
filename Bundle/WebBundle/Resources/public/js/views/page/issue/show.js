/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

export class IssueShowView extends Backbone.Marionette.ItemView {
  constructor(options) {
    this.className = 'full-issue-aside spacer-2';
    this.template = '#issue-show-template';

    this.ui = {
      'tabContent': '.full-issue-tab-content'
    };

    this.events = {
      'click .full-issue-edit': 'editClicked',
      'click .full-issue-tab': 'tabClicked'
    };

    super(options);

    this.model.on('sync', this.render, this);

    App.vent.trigger('issue:highlight', this.model.id);
  }

  serializeData() {
    var data = this.model.toJSON();
    data['transitions'] = this.model.getAllowedTransitions();
    data['canEdit'] = this.model.canEdit(App.currentUser);
    return data;
  }

  tabClicked(ev) {
    var pos = $(ev.currentTarget).index();
    this.ui.tabContent.removeClass('visible');
    $(this.ui.tabContent.get(pos)).addClass('visible');

    return false;
  }

  editClicked() {
    App.router.base.navigate('/issue/' + this.model.id + '/edit');
    App.controller.issue.editAction(this.model);

    return false;
  }
}
