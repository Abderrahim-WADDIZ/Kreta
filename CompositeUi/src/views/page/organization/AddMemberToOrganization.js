/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import './../../../scss/views/page/project/_settings';

import Button from './../../component/Button';
import MemberActions from './../../../actions/Member';
import React from 'react';
import Search from './../../component/SearchMember';
import UserPreview from './../../component/UserPreview';
import { connect } from 'react-redux';

@connect(state => ({currentOrganization: state.currentOrganization}))
class AddMemberToOrganization extends React.Component {
  static propTypes = {
    onMemberRemoveClicked: React.PropTypes.func,
    organization: React.PropTypes.object
  };

  filterMembersToAdd(q) {
    this.props.dispatch(
      MemberActions.fetchMembersToAdd(
        q,
        this.props.currentOrganization.organization.organization_members.map((item) => item.id)
      )
    );
  }

  triggerOnMemberaddClicked(participant) {
    this.props.onMemberAddClicked(participant);
  }

  renderMembersToAdd() {
    return this.props.currentOrganization.potential_members
      .map((member, index) => {
        const actions = (
          <Button color="green"
              onClick={this.triggerOnMemberaddClicked.bind(this, member)}
              type="icon">
          </Button>
          );

        return (
          <div className="MemberList" key={index}>
            <UserPreview actions={actions}
               key={index}
               user={member}/>
          </div>
        );
      });
  }

  onChangeSearch(event) {
    const query = event.target.value;
    this.filterMembersToAdd(query);
  }

  render() {
    return (
      <div className="project-settings__not-participating">
          <Search
              onChange={this.onChangeSearch.bind(this)}
          />
          <div>
              { this.renderMembersToAdd() }
          </div>
      </div>
    );
  }
}

export default AddMemberToOrganization;
