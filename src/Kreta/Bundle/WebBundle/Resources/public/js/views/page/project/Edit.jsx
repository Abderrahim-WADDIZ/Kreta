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

import Button from './../../component/Button';
import Form from './../../component/Form';
import FormInput from './../../component/FormInput';
import Project from './../../../models/Project';

class Edit extends React.Component {
  static propTypes = {
    project: React.PropTypes.object
  };

  render() {
    return (
      <Form model={Project}>
        <div className="section-header">
          <div className="section-header-title"></div>
          <div>
            <Button color="green"
                    tabIndex="3"
                    type="submit">
              Done
            </Button>
          </div>
        </div>
        <input name="id" type="hidden" value={this.props.project.id}/>
        <FormInput label="Project name"
                   name="name"
                   tabIndex="1"
                   type="text"
                   value={this.props.project.get('name')}/>
        <FormInput label="Project short name"
                   name="short_name"
                   tabIndex="2"
                   type="text"
                   value={this.props.project.get('short_name')}/>
      </Form>
    );
  }
}

export default Edit;
