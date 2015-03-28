/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

import {SelectorView} from '../component/selector';
import {Issue} from '../../models/issue';
import {UserCollection} from '../../collections/user';
import {ParticipantCollection} from '../../collections/participant';
import {ProjectCollection} from '../../collections/project';
import {TypeCollection} from '../../collections/type';

export class CreateIssueView extends Backbone.View {
  constructor () {
    this.className = 'kreta-create-issue';
    this.template = _.template($('#kreta-create-issue-template').html());
    this.events = {
      'submit #kreta-create-task': 'save'
    };

    super();

    this.projects = new ProjectCollection();
    this.participants = new ParticipantCollection();
    //this.priorities = new PriorityCollection();
    this.types = new TypeCollection();

    this.listenTo(this.projects, 'add', this.updateProjects);
    this.listenTo(this.projects, 'reset', this.updateProjects);
    this.listenTo(this.participants, 'add', this.updateSelectors);
    this.listenTo(this.participants, 'reset', this.updateSelectors);
    this.listenTo(this.types, 'reset', this.updateSelectors);

    this.projects.fetch();

    this.render();
  }

  render () {
    this.$el.html(this.template({}));

    this.$assignee = new SelectorView(this.$el.find('.selector-assignee'));
    this.$priority = new SelectorView(this.$el.find('.selector-priority'));
    this.$dueDate = new SelectorView(this.$el.find('.selector-due-date'));
    this.$type = new SelectorView(this.$el.find('.selector-type'));
    this.$project = new SelectorView(this.$el.find('.selector-project'));

    this.$project.onOptionSelectedCallback = $.proxy(this.onProjectSelected, this);

    return this;
  }

  onProjectSelected (id) {
    this.participants.setProject(id).fetch();
  }

  updateProjects () {
    this.$project.setSelectables(this.projects);
  }

  updateSelectors () {
    var users = new UserCollection();
    this.participants.each((participant) => {
       users.push(participant.get('user'));
    });
    this.$assignee.setSelectables(users);
    this.$type.setSelectables(this.types);
  }

  save (ev) {
    ev.preventDefault();
    var formData = {};
    $.each($('#kreta-create-task').serializeArray(), function (field) {
      formData[this.name] = this.value;
    });

    var issue = new Issue(formData);
    issue.save();
  }
}
