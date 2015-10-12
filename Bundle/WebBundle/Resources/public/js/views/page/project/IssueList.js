/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

import React from 'react';

import {IssueCollection} from '../../../collections/Issue';
import IssuePreview from '../../component/IssuePreview.js';
import Filter from '../../component/Filter.js';
import IssueShow from '../../page/issue/Show.js';
import NavigableCollection from '../../../mixins/NavigableCollection.js';
import ContentMiddleLayout from '../../layout/ContentMiddleLayout.js';
import ContentRightLayout from '../../layout/ContentRightLayout.js';
import PageHeader from '../../component/PageHeader.js';

export default React.createClass({
  getInitialState() {
    return {
      project: null,
      filters: [],
      issues: [],
      fetchingIssues: true
    };
  },
  mixins: [NavigableCollection],
  componentDidMount() {
    this.state.project = App.collection.project.get(this.props.params.projectId);
    this.state.project.on('sync', this.loadFilters);
    this.state.project.on('change', this.loadFilters);

    this.collection = new IssueCollection();
    this.collection.on('sync', $.proxy(this.issuesUpdated, this));
    this.collection.fetch({data: {project: this.state.project.id}});

    this.loadFilters();
  },
  issuesUpdated(data) {
    this.setState({
      issues: data,
      fetchingIssues: false
    });
  },
  filterIssues(filters) {
    var data = {project: this.state.project.id};

    filters.forEach((filter) => {
      filter.forEach((item) => {
        if (item.selected) {
          data[item.filter] = item.value;
        }
      });
    });

    this.setState({fetchingIssues: true});
    this.collection.fetch({data, reset: true});
  },
  loadFilters() {
    var assigneeFilters = [{
        filter: 'assignee',
        selected: true,
        title: 'All',
        value: ''
      }, {
        filter: 'assignee',
        selected: false,
        title: 'Assigned to me',
        value: App.currentUser.get('id')
      }],
      priorityFilters = [{
        filter: 'priority',
        selected: true,
        title: 'All priorities',
        value: ''
      }
      ],
      priorities = this.state.project.get('issue_priorities'),
      statusFilters = [{
        filter: 'status',
        selected: true,
        title: 'All statuses',
        value: ''
      }],
      statuses = this.state.project.get('statuses');

    if (priorities) {
      priorities.forEach((priority) => {
        priorityFilters.push({
          filter: 'priority',
          selected: false,
          title: priority.name,
          value: priority.id
        });
      });
    }

    if (statuses) {
      statuses.forEach((status) => {
        statusFilters.push({
          filter: 'status',
          selected: false,
          title: status.name,
          value: status.id
        });
      });
    }
    this.setState({filters: [assigneeFilters, priorityFilters, statusFilters]});
  },
  changeSelected(ev) {
    this.setState({
      selectedItem: $(ev.currentTarget).index()
    });
  },
  render() {
    if (!this.state.project) {
      return <p>Loading...</p>;
    }
    const issuesEl = this.state.issues.map((issue, index) => {
      return <IssuePreview issue={issue}
                           key={index}
                           onClick={this.changeSelected}
                           selected={this.state.selectedItem === index}/>;
    });
    let issue = '';
    if (this.state.issues.length > 0 && !this.state.fetchingIssues) {
      issue = <IssueShow issue={this.state.issues.at(this.state.selectedItem)}/>;
    }
    const links = [{
      href: '#',
      icon: 'dashboard',
      title: 'Dashboard'
    }, {
      href: `/project/${this.state.project.id}/settings`,
      icon: 'settings',
      title: 'Settings'
    }];

    return (
      <div>
        <ContentMiddleLayout rightOpen={true}>
          <PageHeader image="" links={links} title=""/>
          <Filter filters={this.state.filters} onFilterSelected={this.filterIssues}/>


          <div className="issues">
            {this.state.fetchingIssues ? 'Loading...' : issuesEl}
          </div>
        </ContentMiddleLayout>
        <ContentRightLayout>
          {issue}
        </ContentRightLayout>
      </div>
    );
  }
});
