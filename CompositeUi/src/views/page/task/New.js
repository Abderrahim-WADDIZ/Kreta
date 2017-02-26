/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import React from 'react';
import {connect} from 'react-redux';
import ContentMiddleLayout from './../../layout/ContentMiddleLayout';
import TaskNew from './../../form/TaskNew';
import CurrentProjectActions from './../../../actions/CurrentProject';
import LoadingSpinner from './../../component/LoadingSpinner';

@connect(state => ({currentProject: state.currentProject}))
class New extends React.Component {
  createTask(task) {
    this.props.dispatch(CurrentProjectActions.createTask(task));
  }

  render() {
    if (this.props.currentProject.waiting) {
      return <LoadingSpinner/>;
    }

    return (
      <ContentMiddleLayout centered>
        <TaskNew onSubmit={this.createTask.bind(this)}/>
      </ContentMiddleLayout>
    );
  }
}

export default New;
