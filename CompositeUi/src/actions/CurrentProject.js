/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import {routeActions} from 'react-router-redux';

import ActionTypes from './../constants/ActionTypes';
import TaskApi from './../api/Task';
import ProjectQueryRequest from './../api/graphql/query/ProjectQueryRequest';
import TasksQueryRequest from './../api/graphql/query/TasksQueryRequest';
import TaskManagerGraphQl from './../api/graphql/TaskManagerGraphQl';

const Actions = {
  fetchProject: (projectId) => (dispatch) => {
    dispatch({
      type: ActionTypes.CURRENT_PROJECT_FETCHING
    });
    const query = ProjectQueryRequest.build(projectId);

    TaskManagerGraphQl.query(query);
    query.then(data => {
      dispatch({
        type: ActionTypes.CURRENT_PROJECT_RECEIVED,
        project: data.response.project,
      });
    });
  },
  selectCurrentTask: (task) => (dispatch) => (
    dispatch({
      type: ActionTypes.CURRENT_PROJECT_SELECTED_TASK_CHANGED,
      selectedTask: task
    })
  ),
  loadFilters: (assignee) => (dispatch) => {
    dispatch({
      type: ActionTypes.CURRENT_PROJECT_TASK_FILTERS_LOADED,
      assignee
    });
  },
  filterTasks: (filters) => (dispatch) => {
    dispatch({
      type: ActionTypes.CURRENT_PROJECT_TASK_FILTERING
    });
    const query = TasksQueryRequest.build(filters);

    TaskManagerGraphQl.query(query);
    query.then(data => {
      dispatch({
        type: ActionTypes.CURRENT_PROJECT_TASK_FILTERED,
        tasks: data.response.tasks,
      });
    });
  },
  createTask: (taskData) => (dispatch) => {
    dispatch({
      type: ActionTypes.CURRENT_PROJECT_TASK_CREATING
    });
    TaskApi.postTask(taskData)
      .then((response) => {
        dispatch({
          type: ActionTypes.CURRENT_PROJECT_TASK_CREATED,
          status: response.status,
          task: response.data
        });
        dispatch(
          routeActions.push(`/project/${response.data.project}/task/${response.data.id}`)
        );
      })
      .catch((response) => {
        response.then((errors) => {
          dispatch({
            type: ActionTypes.CURRENT_PROJECT_TASK_CREATE_ERROR,
            status: response.status,
            errors
          });
        });
      });
  },
  updateTask: (taskData) => (dispatch) => {
    dispatch({
      type: ActionTypes.CURRENT_PROJECT_TASK_UPDATE
    });
    TaskApi.putTask(taskData.id, taskData)
      .then((response) => {
        dispatch({
          type: ActionTypes.CURRENT_PROJECT_TASK_UPDATED,
          status: response.status,
          task: response.data
        });
      })
      .catch((response) => {
        response.then((errors) => {
          dispatch({
            type: ActionTypes.CURRENT_PROJECT_TASK_UPDATE_ERROR,
            status: response.status,
            errors
          });
        });
      });
  },
  addParticipant: (user) => (dispatch) => {
    const participant = {
      role: 'ROLE_PARTICIPANT',
      user
    };

    setTimeout(() => {
      dispatch({
        type: ActionTypes.CURRENT_PROJECT_PARTICIPANT_ADDED,
        participant
      });
    });
  }
};

export default Actions;
