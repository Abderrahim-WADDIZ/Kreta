/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import SettingsIcon from './../../../../svg/settings';

import $ from 'jquery';
import React from 'react';
import Mousetrap from 'mousetrap';
import { connect } from 'react-redux';

import Config from './../../../Config';

import Filter from './../../component/Filter';
import ContentMiddleLayout from './../../layout/ContentMiddleLayout';
import ContentRightLayout from './../../layout/ContentRightLayout';
import IssuePreview from './../../component/IssuePreview';
import IssueShow from './../issue/Show';
import PageHeader from './../../component/PageHeader';
import NavigableList from './../../component/NavigableList';
import ProjectsActions from '../../../actions/Projects';
import CurrentProjectActions from '../../../actions/CurrentProject';

class IssueList extends React.Component {
  componentDidMount() {
    const projectId = this.props.params.projectId;
    Mousetrap.bind(Config.shortcuts.issueNew, () => {
      this.props.dispatch(ProjectActions.showCreateForm());
    });
    Mousetrap.bind(Config.shortcuts.projectSettings, () => {
      this.props.dispatch(CurrentProjectActions.showSettings(projectId));
    });

    this.keyUpListenerRef = this.keyboardNavigate.bind(this);
    window.addEventListener('keyup', this.keyUpListenerRef);

    this.props.dispatch(CurrentProjectActions.fetchProject(projectId));
  }

  componentDidUpdate(prevProps) {
    const oldId = prevProps.params.projectId,
      newId = this.props.params.projectId;
    if (newId !== oldId) {
      this.props.dispatch(CurrentProjectActions.fetchProject(newId));
    }
  }

  componentWillUnmount() {
    window.removeEventListener('keyup', this.keyUpListenerRef);
  }

  keyboardNavigate(ev) {
    this.refs.navigableList.handleNavigation(ev);
  }

  filterIssues(filters) {
    var data = {project: this.state.project.id};

    filters.forEach((filter) => {
      filter.forEach((item) => {
        if (item.selected) {
          data[item.filter] = item.value;
        }
      });
    });

    this.props.dispatch(CurrentProjectActions.filterIssues(data));
  }

  loadFilters(project) {
//    var assigneeFilters = [{
//        filter: 'assignee',
//        selected: true,
//        title: 'All',
//        value: ''
//      }, {
//        filter: 'assignee',
//        selected: false,
//        title: 'Assigned to me',
//        value: this.props.profile.profile.id
//      }],
//      priorityFilters = [{
//        filter: 'priority',
//        selected: true,
//        title: 'All priorities',
//        value: ''
//      }
//      ],
//      priorities = [], //project.get('issue_priorities'),
//      statusFilters = [{
//        filter: 'status',
//        selected: true,
//        title: 'All statuses',
//        value: ''
//      }],
//      statuses = [];// project.get('statuses');
//
//    if (priorities) {
//      priorities.forEach((priority) => {
//        priorityFilters.push({
//          filter: 'priority',
//          selected: false,
//          title: priority.name,
//          value: priority.id
//        });
//      });
//    }
//
//    if (statuses) {
//      statuses.forEach((status) => {
//        statusFilters.push({
//          filter: 'status',
//          selected: false,
//          title: status.name,
//          value: status.id
//        });
//      });
//    }
//    this.setState({filters: [assigneeFilters, priorityFilters, statusFilters]});
  }

  selectCurrentIssue(issue) {
    this.props.dispatch(CurrentProjectActions.selectCurrentIssue(issue));
  }

  hideIssue() {

  }

  render() {
    if (this.props.currentProject.fetching || !this.props.currentProject.project) {
      return <p>Loading...</p>;
    }
    const issuesEl = this.props.currentProject.issues.map((issue, index) => {
        return <IssuePreview issue={issue}
                             key={index}
                             onClick={this.selectCurrentIssue.bind(this, issue)}
                             selected={this.props.selectedIssue && this.props.selectedIssue.id === issue.id}/>;
      }),
      links = [{
        href: `/project/${this.props.currentProject.project.id}/settings`,
        icon: SettingsIcon,
        title: 'Settings',
        color: 'green'
      }],
      buttons = [{
        href: `/issue/new/${this.props.currentProject.project.id}`,
        title: 'New issue'
      }];
    let issue = '';
    if (this.props.currentProject.selectedIssue) {
      issue = <IssueShow issue={this.props.currentProject.selectedIssue}
                         project={this.props.currentProject.project}/>;
    }

    return (
      <div>
        <ContentMiddleLayout>
          <PageHeader buttons={buttons}
                      image=""
                      links={links}
                      title={this.props.currentProject.project.name}/>
          <Filter filters={this.props.currentProject.filters}
                  onFilterSelected={this.filterIssues.bind(this)}/>

          <NavigableList className="issues"
                         //onYChanged={this.changeSelected.bind(this)}
                         ref="navigableList"
                         yLength={issuesEl.length}>
            {this.props.currentProject.fetching ? 'Loading...' : issuesEl}
          </NavigableList>
        </ContentMiddleLayout>
        <ContentRightLayout isOpen={this.props.currentProject.selectedIssue}
                            onRequestClose={this.hideIssue.bind(this)}>
          {issue}
        </ContentRightLayout>
      </div>
    );
  }
}

const mapStateToProps = (state) => {
  return {
    currentProject: state.currentProject
  }
};

export default connect(mapStateToProps)(IssueList);
