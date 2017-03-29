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

const initialState = {
    potential_members: []
};

export default function reducer(state = initialState, action = {}) {
    switch (action.type) {
        case ActionTypes.MEMBERS_TO_ADD_RECEIVED: {
            return {
                ...state,
                fetching: false,
                potential_members: action.users
            };
        }

        default: {
            return state;
        }
    }
}
