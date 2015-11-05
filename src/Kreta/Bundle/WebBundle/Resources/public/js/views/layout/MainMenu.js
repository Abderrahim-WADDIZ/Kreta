/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import './../../../scss/layout/_main-menu';

import ExitIcon from './../../../svg/exit';
import InboxIcon from './../../../svg/inbox';
import LogoIcon from './../../../svg/logo';
import ProjectsIcon from './../../../svg/projects';

import React from 'react';
import {Link} from 'react-router';

import Icon from './../component/Icon';
import Modal from './../component/Modal';
import ProjectList from './../page/project/List';
import UserImage from './../component/UserImage';

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
      <nav className="main-menu">
        <Icon className="main-menu__logo"
              glyph={LogoIcon}/>
        <div className="main-menu__actions">
          <Icon className="main-menu__action main-menu__action--green"
                glyph={ProjectsIcon}
                onClick={this.showProjectList}/>
          <Icon className="main-menu__action main-menu__action--red"
                glyph={ExitIcon}/>
        </div>
        <div className="main-menu__user">
          <div className="main-menu__notification">
            <Icon className="main-menu__action"
                  glyph={InboxIcon}/>
            <span className="main-menu__notification-bubble">0</span>
          </div>
          <Link to="/profile">
            <UserImage user={this.state.user.toJSON()}/>
            <span className="main-menu__username">@{this.state.user.get('username')}</span>
          </Link>
        </div>
        <Modal ref="projectListModal">
          <ProjectList onProjectSelected={this.hideProjectList}/>
        </Modal>
      </nav>
    );
  }
});
