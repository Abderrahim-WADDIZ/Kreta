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
import { connect } from 'react-redux';

import Button from './../../component/Button';
import ContentMiddleLayout from './../../layout/ContentMiddleLayout';
import ContentRightLayout from './../../layout/ContentRightLayout';
import ProjectEdit from './Edit';
import UserPreview from './../../component/UserPreview';
import LoadingSpinner from '../../component/LoadingSpinner.jsx';
import CurrentProjectActions from '../../../actions/CurrentProject';
import PageHeader from '../../component/PageHeader';
import SectionHeader from './../../component/SectionHeader';
import DashboardWidget from './../../component/DashboardWidget';
import Modal from './../../component/Modal';

class Settings extends React.Component {
  state = {
    addParticipantsVisible: false
  };

  addParticipant(participant) {
    this.props.dispatch(CurrentProjectActions.addParticipant(participant));
  }

  showAddParticipants() {
    this.setState({addParticipantsVisible: true});
  }

  render() {
    if (!this.props.project) {
      return <LoadingSpinner/>;
    }

    const projectParticipants = this.props.project.participants.map((participant, index) => {
      return <UserPreview key={index} user={participant.user}/>;
    }), organizationParticipants = this.props.project.organization.participants.map((participant, index) => {
      return <UserPreview key={index} user={participant.user}/>
    });

    return (
      <div>
        <ContentMiddleLayout>
          <PageHeader buttons={[]}
                      image={this.props.project.image ? this.props.project.image.name : ''}
                      links={[]}
                      title={this.props.project.name}/>
          <ProjectEdit project={this.props.project}/>

          <section className="index__dashboard">
            <DashboardWidget title="Organization participants">
              {organizationParticipants}
            </DashboardWidget>
            <DashboardWidget title="Project participants">
              {projectParticipants}
              <Button color="green" onClick={this.showAddParticipants.bind(this)}>Add</Button>
            </DashboardWidget>
          </section>
        </ContentMiddleLayout>
      </div>
    );
  }
}

const mapStateToProps = (state) => {
  return {
    project: state.currentProject.project
  };
};

export default connect(mapStateToProps)(Settings);
