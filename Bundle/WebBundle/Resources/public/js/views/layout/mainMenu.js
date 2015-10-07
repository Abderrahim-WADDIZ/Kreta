/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */
import React from 'react';

import Modal from '../component/modal.js';
import ProjectList from '../page/project/list.js';

export default React.createClass({
  getInitialState() {
    return {
      user: App.currentUser,
      projectListVisible: false
    };
  },
  showProjectList(ev) {
    this.refs.projectListModal.openModal();
    ev.preventDefault();
  },
  hideProjectList() {
    this.refs.projectListModal.closeModal();
  },
  render() {
    return (
      <nav className="menu">
        <img className="menu-logo" src=""/>
        <div className="menu-user">
          <img className="menu-user-image" src={this.state.user.get('photo').name}/>
          <span className="menu-user-name">@{this.state.user.get('username')}</span>
        </div>
        <div>
          <a className="menu-action" href="/">
            <i className="fa fa-sign-out"></i>
            <span className="menu-notification-bubble">4</span>
          </a>
          <a className="menu-action projects" onClick={this.showProjectList}
             href="#" data-tooltip-text="Project list" data-tooltip-position="right">
            <i className="fa fa-sign-out"></i>
          </a>
          <a className="menu-action" href="/profile/edit" data-tooltip-text="Edit profile"
             data-tooltip-position="right">
            <i className="fa fa-sign-out"></i>
          </a>
          <a className="menu-action" href="/logout" data-bypass data-tooltip-text="Logout"
             data-tooltip-position="right" title="Logout">
            <i className="fa fa-sign-out"></i>
          </a>
        </div>
        <Modal ref="projectListModal">
          <ProjectList onProjectSelected={this.hideProjectList}/>
        </Modal>
      </nav>
    );
  }
});
