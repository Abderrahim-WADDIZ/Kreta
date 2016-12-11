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
import CreateOrganizationMutationRequest from './../api/graphql/mutation/CreateOrganizationMutationRequest';
import TaskManagerGraphQl from './../api/graphql/TaskManagerGraphQl';

const Actions = {
  createOrganization: (organizationInputData) => (dispatch) => {
    dispatch({
      type: ActionTypes.ORGANIZATION_CREATING
    });
    const mutation = CreateOrganizationMutationRequest.build(organizationInputData);

    TaskManagerGraphQl.mutation(mutation);
    mutation
      .then(data => {
        const organization = data.response.createOrganization.organization;

        dispatch({
          type: ActionTypes.ORGANIZATION_CREATED,
          organization,
        });
        dispatch(
          routeActions.push(`/organization/${organization.id}`)
        );
      })
      .catch((response) => {
        response.then((errors) => {
          dispatch({
            type: ActionTypes.ORGANIZATION_CREATE_ERROR,
            errors
          });
        });
      });
  }
};

export default Actions;
