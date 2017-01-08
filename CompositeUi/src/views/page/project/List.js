/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import AddIcon from './../../../svg/add';
import ListIcon from './../../../svg/list';

import React from 'react';
import {connect} from 'react-redux';
import {routeActions} from 'react-router-redux';
import classNames from 'classnames';

import {routes} from './../../../Routes';

import Icon from './../../component/Icon';
import MainMenuActions from './../../../actions/MainMenu';
import NavigableList from './../../component/NavigableList';
import ResourcePreview from './../../component/ResourcePreview';
import ShortcutHelp from './../../component/ShortcutHelp';
import {Row, RowColumn} from './../../component/Grid';

@connect(state => ({projects: state.projects.projects, mainMenu: state.mainMenu}))
class List extends React.Component {
  static propTypes = {
    onProjectSelected: React.PropTypes.func
  };

  state = {
    selectedProject: 0,
    selectedShortcut: 0,
    filter: '',
    filteredProjects: []
  };

  componentWillMount() {
    this.filterProjects('');
  }

  componentWillUpdate() {
    this.focus();
  }

  onKeyUp(event) {
    if (event.which === 13) { // Enter
      this.goToShortcutLink(this.state.selectedShortcut);
    } else if (event.which === 27) { // Escape
      this.hideProjectsList();
    } else if (event.which < 37 || event.which > 40) { // Filter
      this.filterProjects(event.currentTarget.value);
    } else {
      this.refs.navigableList.handleNavigation(event);
    }

    event.stopPropagation();
  }

  hideProjectsList() {
    this.props.dispatch(MainMenuActions.hideProjects());
  }

  filterProjects(value) {
    const filteredProjects = this.props.projects.filter(project => (
      value.length === 0 || project.node.name.indexOf(value) > -1
    ));
    this.setState({
      filteredProjects,
      selectedProject: 0
    });
  }

  changeSelectedRow(index) {
    this.setState({selectedProject: index});
  }

  changeSelectedShortcut(index) {
    this.setState({selectedShortcut: index});
  }

  goToShortcutLink(index) {
    const
      project = this.state.filteredProjects[this.state.selectedProject].node,
      shortcutLinks = [
        routes.project.show(project.organization.slug, project.slug),
        routes.task.new(project.organization.slug, project.slug),
      ];

    this.props.dispatch(routeActions.push(shortcutLinks[index]));
    this.triggerOnProjectSelected();
  }

  triggerOnProjectSelected() {
    this.props.onProjectSelected();
  }

  focus() {
    this.refs.filter.focus();
  }

  getHeader() {
    return (
      <div className="resource-preview__header">
        <Row>
          <RowColumn>
            <ShortcutHelp does="to select action" keyboard="← →"/>
            <ShortcutHelp does="to select project" keyboard="↑ ↓"/>
            <ShortcutHelp does="go to project" keyboard="↵"/>
            <ShortcutHelp does="to dismiss" keyboard="esc"/>
          </RowColumn>
        </Row>
      </div>
    );
  }

  getProjectItems() {
    return this.state.filteredProjects.map((project, index) => (
      <ResourcePreview
        key={index}
        resource={project.node}
        shortcuts={
          <div>
            <Icon
              className={classNames({
                'resource-preview__shortcut': true,
                'resource-preview__shortcut--selected': 0 === this.state.selectedShortcut
              })}
              glyph={ListIcon}
              onClick={this.goToShortcutLink.bind(this, 0)}
              onMouseEnter={this.changeSelectedShortcut.bind(this, 0)}
            />
            <Icon
              className={classNames({
                'resource-preview__shortcut': true,
                'resource-preview__shortcut--selected': 1 === this.state.selectedShortcut
              })}
              glyph={AddIcon}
              onClick={this.goToShortcutLink.bind(this, 1)}
              onMouseEnter={this.changeSelectedShortcut.bind(this, 1)}
            />
          </div>
        }
        type="project"
      />
    ));
  }

  render() {
    return (
      <div>
        {this.getHeader()}
        <input
          className="resource-preview__filter"
          onKeyUp={this.onKeyUp.bind(this)}
          placeholder="Type the project"
          ref="filter"
          type="text"
        />
        <NavigableList
          className="resource-preview__list"
          classNameSelected="resource-preview--selected"
          onElementSelected={this.triggerOnProjectSelected.bind(this)}
          onXChanged={this.changeSelectedShortcut.bind(this)}
          onYChanged={this.changeSelectedRow.bind(this)}
          ref="navigableList"
          xLength={2}
          xSelected={this.state.selectedShortcut}
          yLength={this.state.filteredProjects.length}
          ySelected={this.state.selectedProject}
        >
          {this.getProjectItems()}
        </NavigableList>
      </div>
    );
  }
}

export default List;
