/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import AddIcon from './../../../../svg/add';
import ListIcon from './../../../../svg/list';

import React from 'react';
import {Link} from 'react-router';
import {connect} from 'react-redux';
import {routeActions} from 'react-router-redux';

import Button from './../../component/Button';
import NavigableList from './../../component/NavigableList';
import ProjectPreview from './../../component/ProjectPreview';
import ShortcutHelp from './../../component/ShortcutHelp';
import {Row, RowColumn} from './../../component/Grid';

@connect(state => ({projects: state.projects.projects}))
export default class extends React.Component {
  static propTypes = {
    onProjectSelected: React.PropTypes.func
  };

  state = {
    selectedProject: 0,
    selectedShortcut: 0,
    filter: ''
  };

  shortcuts = [{
    'icon': ListIcon,
    'link': '',
    'tooltip': 'Show full project'
  }, {
    'icon': AddIcon,
    'link': 'issue/new/',
    'tooltip': 'New task'
  }];

  onKeyUp(ev) {
    if (ev.which === 13) { // Enter
      this.goToShortcutLink(this.state.selectedShortcut);
    } else if (ev.which < 37 || ev.which > 40) { // Filter
      this.setState({filter: ev.currentTarget.value});
    } else {
      this.refs.navigableList.handleNavigation(ev);
    }

    ev.stopPropagation();
  }

  changeSelectedRow(index) {
    this.setState({selectedProject: index});
  }

  changeSelectedShortcut(index) {
    this.setState({selectedShortcut: index});
  }

  goToShortcutLink(index) {
    const link = `/project/${this.state.selectedProject.id}/${this.shortcuts[index].link}`;
    this.props.dispatch(routeActions.push(link));
    this.triggerOnProjectSelected();
  }

  triggerOnProjectSelected() {
    this.props.onProjectSelected();
  }

  focus() {
    this.refs.filter.focus();
  }

  render() {
    const projectItems = this.props.projects.filter(project => {
      return this.state.filter.length === 0 ||
        (project.name.indexOf(this.state.filter) > -1 ||
        project.organization.name.indexOf(this.state.filter) > -1);
    }).map((project, index) => {
      return <ProjectPreview key={index}
                             project={project}
                             selectedShortcut={this.state.selectedShortcut}
                             shortcuts={this.shortcuts}/>;

    });

    return (
      <div>
        <div className="project-preview__header">
          <Row>
            <RowColumn small={9}>
              <ShortcutHelp keyboard="← →" does="to select action"/>
              <ShortcutHelp keyboard="↑ ↓" does="to select project"/>
              <ShortcutHelp keyboard="↵" does="go to project"/>
              <ShortcutHelp keyboard="esc" does="to dismiss"/>
            </RowColumn>
            <RowColumn small={3}>
              <Link to="/project/new">
                <Button color="green"
                        onClick={this.triggerOnProjectSelected.bind(this, null)}
                        size="small">
                  New project
                </Button>
              </Link>
            </RowColumn>
          </Row>
        </div>
        <input className="project-preview__filter"
               onKeyUp={this.onKeyUp.bind(this)}
               placeholder="Type the project"
               ref="filter"
               type="text"/>
        <NavigableList className="project-preview__list"
                       onElementMouseEnter={this.changeSelectedRow.bind(this)}
                       onXChanged={this.changeSelectedShortcut.bind(this)}
                       onYChanged={this.changeSelectedRow.bind(this)}
                       ref="navigableList"
                       xSelected={this.state.selectedShortcut}
                       ySelected={this.state.selectedProject}
                       xLength={2}
                       yLength={projectItems.length}>
          { projectItems }
        </NavigableList>
      </div>
    );
  }
}
