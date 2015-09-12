/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

import {FilterView} from '../../component/filter';
import {IssuePreviewView} from '../../component/issuePreview';

export class IssueListView extends Backbone.Marionette.CompositeView {
  initialize() {
    this.template = '#project-issues-template';
    this.childView = IssuePreviewView;
    this.childViewContainer = '.issues';

    this.events = {
      'click #project-settings-show': 'showSettings'
    };

    this.ui = {
      issues: '.issues',
      filter: '.filter'
    };

    this.filters = [];
    this.highlightIndex = -1;

    Mousetrap.bind('j', $.proxy(this.highlightPrevious, this));
    Mousetrap.bind('k', $.proxy(this.highlightNext, this));

    this.model.on('sync', $.proxy(this.render, this));
    this.model.on('change', $.proxy(this.render, this));
  }

  onRender() {
    this.loadFilters();

    this.filterView = new FilterView(this.filters);
    this.filterView.onFilterClicked((filter) => {
      this.filterIssues(filter);
    });

    this.ui.filter.html(this.filterView.render().el);
  }

  loadFilters() {
    var assigneeFilters = [
      {
        title: 'All',
        filter: 'assignee',
        value: '',
        selected: true
      }, {
        title: 'Assigned to me',
        filter: 'assignee',
        value: App.currentUser.get('id'),
        selected: false
      }
    ];

    var priorityFilters = [
      {
        title: 'All priorities',
        filter: 'priority',
        value: '',
        selected: true
      }
    ];

    var priorities = this.model.get('issue_priorities');
    if(priorities) {
      priorities.forEach(function (priority) {
        priorityFilters.push({
          title: priority.name,
          filter: 'priority',
          value: priority.id,
          selected: false
        })
      });
    }


    var statusFilters = [
      {
        title: 'All statuses',
        filter: 'status',
        value: '',
        selected: true
      }
    ];

    var statuses = this.model.get('statuses');
    if(statuses) {
      statuses.forEach(function (status) {
        statusFilters.push({
          title: status.name,
          filter: 'status',
          value: status.id,
          selected: false
        })
      });
    }

    this.filters = [assigneeFilters, priorityFilters, statusFilters];
  }

  filterIssues(filters) {
    var data = {project: this.model.id};

    filters.forEach((filter) => {
      filter.forEach((item) => {
        if (item.selected) {
          data[item.filter] = item.value;
        }
      });
    });

    this.ui.issues.html('');
    this.collection.fetch({data: data, reset: true});
  }

  showSettings() {
    App.router.base.navigate('/project/' + this.model.id + '/settings');
    App.controller.project.settingsAction(this.model);
    return false;
  }

  highlightPrevious() {
    if (this.highlightIndex === 0) {
      return;
    }
    this.highlightIndex--;
    this.showHighlightedIssue();
    return false;
  }

  highlightNext() {
    if (this.highlightIndex >= this.collection.length - 1) {
      return;
    }
    this.highlightIndex++;
    this.showHighlightedIssue();
    return false;
  }

  showHighlightedIssue() {
    var issue = this.collection.at(this.highlightIndex);
    App.vent.trigger('issue:highlight', issue.get('id'));
    App.controller.issue.showAction(issue);
  }

  onDestroy() {
    Mousetrap.unbind('j');
    Mousetrap.unbind('k');
  }
}
