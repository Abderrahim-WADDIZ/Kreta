/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import './../../scss/layout/_base';

import React from 'react';
import {connect} from 'react-redux';

import ContentLayout from './ContentLayout';
import MainMenu from './MainMenu';
import NotificationLayout from './NotificationLayout';
import LoadingSpinner from './../component/LoadingSpinner';
import ProfileActions from './../../actions/Profile';
import ProjectsActions from './../../actions/Projects';

@connect(state => ({waiting: state.projects.fetching || state.profile.fetching || state.user.updatingAuthorization}))
class Base extends React.Component {
  componentDidMount() {
    const {dispatch} = this.props;

    dispatch(ProfileActions.fetchProfile());
    dispatch(ProjectsActions.fetchProjects());
  }

  render() {
    if (this.props.waiting) {
      return <LoadingSpinner/>;
    }

    return (
      <div className="base-layout">
        <NotificationLayout/>
        <MainMenu/>
        <ContentLayout>
          {this.props.children}
        </ContentLayout>
      </div>
    );
  }
}

export default Base;
