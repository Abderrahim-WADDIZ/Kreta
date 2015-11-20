/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import './../../../scss/components/_issue-preview';

import classnames from 'classnames';
import React from 'react';

import UserImage from './UserImage';

class IssuePreview extends React.Component {
  render() {
    const priority = this.props.issue.get('priority'),
      statusType = this.props.issue.get('status').type,
      classes = classnames({
        'issue-preview': true,
        'issue-preview--highlight': this.props.selected,
        'issue-preview--closed': statusType === 'final'
      });

    return (
      <div className={classes} onClick={this.props.onClick}>
        <a className="issue-preview__title">
          {this.props.issue.get('title')}
        </a>
        <div className="issue-preview__icons">
          <span data-tooltip-text={`
              ${this.props.issue.get('assignee').first_name}
              ${this.props.issue.get('assignee').last_name}`}>
            <svg className={`issue-preview__priority issue-preview__priority--${statusType}`}>
              <circle r="20" cx="21" cy="21"
                      className="issue-preview__priority-back"
                      style={{stroke: priority.color}}/>
              <circle r="20" cx="21" cy="21" transform="rotate(-90, 21, 21)"
                      className="issue-preview__priority-front"
                      style={{stroke: priority.color}}/>
            </svg>
            <UserImage user={this.props.issue.get('assignee')}/>
          </span>
        </div>
      </div>
    );
  }
}

export default IssuePreview;
