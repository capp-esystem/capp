<template>
  <div class="inter_group_assessment" v-if="assessment">
    <div class="page-header">
      <h1>Assessment
        <small>{{assessment.name}}</small>
      </h1>
    </div>
    <div class="row" v-if="assessment">
      <div class="col-xs-12">
        <p class="bg-info p10">
          You are asked to rank the performance of each presenting groups, excluding your group, with reference to the listed criteria / assessment rubrics.<br/>
          A higher rank represents the group presentation is better, and a lower rank represents the group presentation is relatively worse.<br/>
          The peer assessment on group performance will contribute <strong>{{assessment.peer_contribution_percentage}}%</strong> to the overall group mark for the project.
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-md-6">
        <section class="groups">
          <h4>Groups</h4>
          <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default" v-for="group in groups" :key="group.id">
              <div class="panel-heading" role="tab" :id="`group-${group.id}`">
                <h4 class="panel-title">
                  <a class="flexbox" role="button" data-toggle="collapse" data-parent="#accordion" :href="`#collapse-${group.id}`" aria-expanded="false" :aria-controls="`collapse-${group.id}`">
                    <div class="flex-one">
                      {{group.name}}
                    </div>
                    <div class="flex-none">
                      <span class="pl5 glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                    </div>
                  </a>
                </h4>
              </div>
              <div :id="`collapse-${group.id}`" class="panel-collapse collapse" role="tabpanel" :aria-labelledby="`group-${group.id}`">
                <ul class="list-group">
                  <li class="list-group-item" v-for="member in group.members" :key="member.id">{{ member.user.name }}</li>
                </ul>
              </div>
            </div>
          </div>
        </section>
        <div class="description panel panel-primary" v-if="assessment.description">
          <div class="panel-heading">Description</div>
          <p class="panel-body">
            {{assessment.description}}
          </p>
        </div>
        <div class="criteria_reference panel panel-primary" v-if="assessment.criteria_reference">
          <div class="panel-heading">Criteria Reference</div>
          <p class="panel-body">
            {{assessment.criteria_reference}}
          </p>
        </div>
      </div>
      <div class="col-xs-12 col-md-6">
        <div class="alert alert-danger" role="alert" v-if="marks_err_msg">
          {{marks_err_msg}}
        </div>
        <div class="marks panel panel-primary" v-if="marks.length">
          <div class="panel-heading">Rank the overall performance of each group in the order of competence <br/> ({{groups.length - 1}} is the best, 1 is the worst)</div>
          <ul class="list-group">
            <form id="marks-form">
              <li class="list-group-item flexbox vcenter" v-for="group in groups" :key="group.id" v-if="userNotInGroup(group)">
                <div class="flex-one">{{ group.name }}</div>
                <div class="flex-none">
                  <input type="number" class="form-control" step="1" min="1" :max="groups.length - 1" required v-model="getMarks(group.id).marks" :disabled="!assessment.available" />
                </div>
              </li>
            </form>
          </ul>
        </div>
        <div class="responses panel panel-primary" v-if="assessment.response_no_required && responses.length">
          <div class="panel-heading">{{assessment.response_type | capitalize}} to groups</div>
          <ul class="list-group">
            <form id="responses-form">
              <li class="list-group-item" v-for="group in groups" :key="group.id" v-if="userNotInGroup(group)">
                <h4>{{ assessment.response_type | capitalize | singularize }}(s) for {{ group.name }}</h4>
                <section class="response" v-for="(response, index) in getResponses(group.id)" :key="index">
                  <input type="text" class="form-control mb10" placeholder="Place your response here" v-model="response.content" :disabled="!assessment.available" required />
                </section>
              </li>
            </form>
          </ul>
        </div>
      </div>
    </div>
    <div class="row" v-if="assessment.available">
      <div class="col-xs-12 text-center mb30">
        <input type="submit" class="btn btn-primary" @click="submitForm" :disabled="loading" />
        <input type="reset" class="btn btn-danger" @click="resetForm" :disabled="loading" />
      </div>
    </div>
  </div>
</template>

<script>
const _ = require('lodash');
_.mixin(require('lodash-inflection'));
import {
  getInterGroupAssessment,
  getGroups,
  getCourseStudent,
  getInterGroupAssessmentMarks,
  getInterGroupAssessmentResponses,
  submitInterGroupAssessmentMarks,
  submitInterGroupAssessmentResponses
} from './../api-client';

export default {
  created: function() {
    Promise.all([this.fetchAssessment(), this.fetchGroups(), this.fetchCourseStudent()]).then(
      function() {
        this.fetchMarks();
        this.fetchResponses();
      }.bind(this)
    );
  },
  methods: {
    fetchAssessment: function() {
      return getInterGroupAssessment(this.assessment_id)
        .then(
          function(response) {
            this.assessment = response.data;
          }.bind(this)
        )
        .catch(function(error) {
          console.error(error);
        });
    },
    fetchGroups: function() {
      return getGroups(this.course_id)
        .then(
          function(response) {
            this.groups = response.data;
          }.bind(this)
        )
        .catch(function(error) {
          console.error(error);
        });
    },
    fetchCourseStudent: function() {
      return getCourseStudent(this.course_id)
        .then(
          function(response) {
            this.relation = response.data;
          }.bind(this)
        )
        .catch(function(error) {
          console.error(error);
        });
    },
    fetchMarks: function() {
      return getInterGroupAssessmentMarks(this.assessment_id)
        .then(
          function(response) {
            this.marks = this.initMarks(response.data);
          }.bind(this)
        )
        .catch(function(error) {
          console.error(error);
        });
    },
    fetchResponses: function() {
      return getInterGroupAssessmentResponses(this.assessment_id)
        .then(
          function(response) {
            this.responses = this.initResponses(response.data);
          }.bind(this)
        )
        .catch(function(error) {
          console.error(error);
        });
    },
    initMarks: function(marks) {
      const relation = this.relation;
      const assessment = this.assessment;
      return this.groups
        .filter(
          function(group) {
            return this.userNotInGroup(group);
          }.bind(this)
        )
        .map(function(group) {
          const submittedMarks = _.find(marks, { group_id: group.id });
          if (submittedMarks) {
            return submittedMarks;
          }
          return {
            asg_id: assessment.id,
            from_id: relation.id,
            group_id: group.id,
            marks: undefined
          };
        });
    },
    initResponses: function(responses) {
      const relation = this.relation;
      const assessment = this.assessment;
      const groups = this.groups.filter(
        function(group) {
          return this.userNotInGroup(group);
        }.bind(this)
      );
      return _.flatMap(
        groups.map(function(group) {
          const submitted = _.filter(responses, { group_id: group.id }) || [];
          const emptyResponse = {
            asg_id: assessment.id,
            from_id: relation.id,
            group_id: group.id,
            content: undefined
          };
          if (submitted.length < assessment.response_no_required) {
            return submitted.concat(_.range(assessment.response_no_required - submitted.length).map(() => Object.assign({}, emptyResponse)));
          }
          return _.take(submitted, assessment.response_no_required);
        })
      );
    },
    userNotInGroup: function(group) {
      return group.id !== this.relation.group_id;
    },
    getMarks: function(groupId) {
      return _.find(this.marks, { group_id: groupId });
    },
    getResponses: function(groupId) {
      return _.filter(this.responses, { group_id: groupId });
    },
    submitForm: function() {
      return Promise.all([this.submitMarksForm(), this.submitResponsesForm()])
        .then(
          function() {
            this.$router.push({ name: 'assessments', params: { course_id: this.course_id } });
          }.bind(this)
        )
        .catch(function(error) {
          console.error(error);
        });
    },
    submitMarksForm: function() {
      this.marks_err_msg = undefined;
      //validate if the marks are correct
      const uniqueMarks = _.uniq(this.marks.map(mark => mark.marks));
      if (uniqueMarks.length !== this.marks.length) {
        this.marks_err_msg = 'Please make sure all groups has its unique rank, no duplication accepted!';
        return Promise.reject('duplicated ranks');
      }
      if (!document.querySelector('#marks-form').reportValidity()) {
        return Promise.reject('invalid');
      }
      return submitInterGroupAssessmentMarks(this.assessment_id, this.marks);
    },
    submitResponsesForm: function() {
      if (!document.querySelector('#responses-form').reportValidity()) {
        return Promise.reject('invalid');
      }
      return submitInterGroupAssessmentResponses(this.assessment_id, this.responses);
    },
    resetForm: function() {
      this.marks = this.marks.map(function(mark) {
        mark.marks = undefined;
        return mark;
      });
      this.responses = this.responses.map(function(response) {
        response.content = undefined;
        return response;
      });
    }
  },
  filters: {
    capitalize: _.capitalize,
    singularize: word => _.singularize(word)
  },
  data: function() {
    return {
      loading: false,
      assessment: undefined,
      marks_err_msg: undefined,
      relation: undefined,
      groups: [],
      marks: [],
      responses: []
    };
  },
  props: ['assessment_id', 'course_id']
};
</script>
