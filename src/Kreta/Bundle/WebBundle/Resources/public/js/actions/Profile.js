import ActionTypes              from '../constants/ActionTypes';
import { routeActions }       from 'react-router-redux';

const Actions = {
  fetchProfile: () => {
    return dispatch => {
      dispatch({type: ActionTypes.PROFILE_FETCHING});

      // Simulate API call
      setTimeout(() => {
        dispatch({
          type: ActionTypes. PROFILE_RECEIVED,
          profile: {
            id: '0',
            username: 'user',
            email: 'user@kreta.com',
            enabled: true,
            last_login: null,
            created_at: '2014-10-20T00:00:00+0200',
            first_name: 'Kreta',
            last_name: 'User',
            photo: {
              id: '1',
              created_at: '2014-10-30T00:00:00+0100',
              name: 'http://kreta.test:8000/media/image/user-1.jpg',
              updated_at: null
            }
          }
        });
      }, 200);
    };
  }
};

export default Actions;
