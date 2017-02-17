/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import React from 'react';
import {connect} from 'react-redux';
import {Field, reduxForm} from 'redux-form';

import Button from './../component/Button';
import Form from './../component/Form';
import FormActions from './../component/FormActions';
import FormInput from './../component/FormInput';
import FormInputFile from './../component/FormInputFile';
import {Row, RowColumn} from './../component/Grid';

const validate = (values) => {
  const errors = {},
    requiredFields = ['first_name', 'last_name', 'username', 'email'];

  if (typeof values.username !== 'undefined' && values.username.length < 3) {
    errors.username = 'Username must be at least 3 characters';
  }

  if (typeof values.username !== 'undefined' && values.username.length > 20) {
    errors.username = 'Username should not have more than 20 characters';
  }

  if (!/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i.test(values.email)) {
    errors.email = 'Invalid email address';
  }

  requiredFields.forEach(field => {
    if (!values[field]) {
      errors[field] = 'Required';
    }
  });

  return errors;
};

@connect(state => ({initialValues: state.profile.profile}))
@reduxForm({form: 'profileEdit', validate})
class ProfileEdit extends React.Component {
  render() {
    const {handleSubmit} = this.props;

    return (
      <Form onSubmit={handleSubmit}>
        <Row>
          <RowColumn>
            <Field component={FormInputFile} name="image" type="file"/>
            <Field component={FormInput} label="First Name" name="first_name" tabIndex={2}/>
            <Field component={FormInput} label="Last Name" name="last_name" tabIndex={3}/>
            <Field component={FormInput} label="Username" name="username" tabIndex={4}/>
            <Field component={FormInput} label="Email" name="email" tabIndex={5}/>
            <FormActions>
              <Button color="green" type="submit">Update</Button>
            </FormActions>
          </RowColumn>
        </Row>
      </Form>
    );
  }
}

export default ProfileEdit;
