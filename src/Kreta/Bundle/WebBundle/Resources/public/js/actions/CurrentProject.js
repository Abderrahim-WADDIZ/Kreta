/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import ActionTypes from './../constants/ActionTypes';
import {routeActions} from 'react-router-redux';

import IssueApi from './../api/Issue';
import ProjectApi from './../api/Project';

const Actions = {
  fetchProject: (projectId) => {
    return dispatch => {
      dispatch({type: ActionTypes.CURRENT_PROJECT_FETCHING});

      ProjectApi.getProject(projectId)
        .then((project) => {
          dispatch({
            type: ActionTypes.CURRENT_PROJECT_RECEIVED,
            project: project
          });
        });

      IssueApi.getIssues({project: projectId})
        .then((issues) => {
          dispatch({
            type: ActionTypes.CURRENT_PROJECT_ISSUES_RECEIVED,
            issues: issues
          });
        });
    };
  },
  selectCurrentIssue: (issue) => {
    if (typeof issue === 'object' || issue === null) {
      return {
        type: ActionTypes.CURRENT_PROJECT_SELECTED_ISSUE_CHANGED,
        selectedIssue: issue
      };
    } else {
      return dispatch => {
        dispatch({
          type: ActionTypes.CURRENT_PROJECT_SELECTED_ISSUE_FETCHING
        });
        IssueApi.getIssue(issue)
          .then((receivedIssue) => {
            dispatch({
              type: ActionTypes.CURRENT_PROJECT_SELECTED_ISSUE_CHANGED,
              selectedIssue: receivedIssue
            })
          })
        }
    }
  },
  createIssue: (issueData) => {
    return dispatch => {
      dispatch({type: ActionTypes.CURRENT_PROJECT_ISSUE_CREATING});

      IssueApi.postIssue(issueData)
        .then((createdIssue) => {
          dispatch({
            type: ActionTypes.CURRENT_PROJECT_ISSUE_CREATED,
            issue: createdIssue
          });
          dispatch(
            routeActions.push(`/project/${issueData.project}/issue/${createdIssue.id}`)
          );
        })
        .catch((errors) => {
          dispatch({
            type: ActionTypes.CURRENT_PROJECT_ISSUE_CREATE_ERROR,
            errors: errors
          });
        });
    }
  },
  filterIssues: (filter) => {

  }
};

export default Actions;
