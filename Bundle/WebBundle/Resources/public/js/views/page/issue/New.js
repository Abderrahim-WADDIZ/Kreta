/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import '../../../../scss/views/page/issue/_new.scss';

import React from 'react';
import {History} from 'react-router';
import $ from 'jquery';

import {FormSerializerService} from '../../../service/FormSerializer';
import {Issue} from '../../../models/Issue';
import {NotificationService} from '../../../service/Notification';
import ContentMiddleLayout from '../../layout/ContentMiddleLayout.js';
import Selector from '../../component/Selector.js';
import IssueField from '../../component/IssueField.js';
import UserImage from '../../component/UserImage.js';

export default React.createClass({
  getInitialState() {
    return {
      isLoading: true,
      project: null,
      selectableProjects: []
    };
  },
  mixins: [History],
  componentDidMount() {
    this.selectorsLeft = 0;
    this.updateSelectors(this.props.params.projectId);
  },
  componentDidUpdate (prevProps) {
    const oldId = prevProps.params.projectId,
      newId = this.props.params.projectId;
    if (newId !== oldId) {
      this.updateSelectors(this.props.params.projectId);
    }
  },
  updateSelectors(projectId) {
    const project = App.collection.project.get(projectId);
    this.selectorsLeft = 2;

    project.on('change', $.proxy(this.onProjectUpdated, this));
    this.setState({
      project,
      selectableProjects: App.collection.project,
      isLoading: true
    });
  },
  onProjectUpdated(model) {
    this.selectorsLeft--;
    this.setState({
      isLoading: this.selectorsLeft,
      project: model
    });
  },
  getProjectOptions() {
    const project = App.collection.project.get(this.state.project.id);
    if (!project) {
      return {
        asignee: [],
        priority: [],
        type: []
      };
    }
    var selectableProjects = this.state.selectableProjects.map((p) => {
        return (
          <IssueField
            text={p.get('name')}
            value={p.id}/>
        );
      }),
      assignee = project.get('participants').map((p) => {
        return (
          <IssueField image={<UserImage user={p.user}/>}
          label="Assigned to"
          text={`${p.user.first_name} ${p.user.last_name}`}
          value={p.user.id}/>
        );

      }),
      priority = project.get('issue_priorities').map((p) => {
        return (
          <IssueField image={<i className="fa fa-exclamation"></i>}
          label="Priority"
          text={p.name}
          value={p.id}/>
        );
      }),
      type = project.get('issue_types').map((t) => {
        return (
          <IssueField image={<i className="fa fa-coffee"></i>}
          label="Type"
          text={t.name}
          value={t.id}/>
        );
      });

    return {selectableProjects, assignee, priority, type};
  },
  save(ev) {
    ev.preventDefault();

    const issue = FormSerializerService.serialize(
      $(this.refs.form), Issue
    );

    this.setState({isLoading: true});

    issue.save(null, {
      success: (model) => {
        NotificationService.showNotification({
          type: 'success',
          message: 'Issue created successfully'
        });
        this.history.pushState(null, `/project/${model.get('project')}`);
      }, error: () => {
        NotificationService.showNotification({
          type: 'error',
          message: 'Error while saving this issue'
        });
        this.setState({isLoading: false});
      }
    });
  },
  render() {
    if (!this.state.project) {
      return <div>Loading</div>;
    }

    const options = this.getProjectOptions();

    return (
      <ContentMiddleLayout>
        <form className="issue-new"
              id="issue-new"
              method="POST"
              onSubmit={this.save}
              ref="form">
          <div className="issue-new-actions">
            <button className="button">Cancel</button>
            <button className="button green"
                    tabIndex="7"
                    type="submit">Done
            </button>
          </div>
          <Selector name="project"
                    onChange={this.updateSelectors}
                    tabIndex={1}
                    value={this.state.project.id}>
            {options.selectableProjects}
          </Selector>
          <input className="big"
                 name="title"
                 placeholder="Type your task title"
                 tabIndex={2}
                 type="text"
                 value={this.state.project.title}/>
          <textarea name="description"
                    placeholder="Type your task description"
                    tabIndex={3}
                    value={this.state.project.description}></textarea>

          <div className={`issue-new__details${this.state.isLoading ? ' issue-new__details--hidden' : ''}`}>
            <Selector name="assignee"
                      style={{width: '25%'}}
                      tabIndex={4}
                      value=""
                      >
              {options.assignee}
            </Selector>
            <Selector name="priority"
                      style={{width: '25%'}}
                      tabIndex={5}
                      value="">
              {options.priority}
            </Selector>
            <Selector name="priority"
                      style={{width: '25%'}}
                      tabIndex={6}
                      value="">
              {options.type}
            </Selector>
          </div>
        </form>
      </ContentMiddleLayout>
    );
  }
});

