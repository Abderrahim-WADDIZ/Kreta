/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import '../../../scss/layout/_main-menu.scss';

import React from 'react';
import {Link} from 'react-router';

import Modal from '../component/Modal.js';
import UserImage from '../component/UserImage.js';
import ProjectList from '../page/project/List.js';

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
          <UserImage user={this.state.user.toJSON()}/>
          <span className="menu-user-name">@{this.state.user.get('username')}</span>
        </div>
        <div>
          <a className="menu-action">
            <i className="fa fa-sign-out"></i>
            <span className="menu-notification-bubble">4</span>
          </a>
          <a className="menu-action projects"
             onClick={this.showProjectList}>
            <i className="fa fa-sign-out"></i>
          </a>
          <Link to="/profile">
            <i className="fa fa-sign-out"></i>
          </Link>
          <a className="menu-action"
             href="/logout">
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
