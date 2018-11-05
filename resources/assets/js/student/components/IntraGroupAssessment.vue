<template>
  <div class="intra_group_assessment" v-if="assessment">
    <div class="page-header">
      <h1>Assessment
        <small>{{assessment.name}}</small>
      </h1>
    </div>
    <div class="alert alert-info info-panel" role="alert">
      <h4>Rate each group member, including yourself, by awarding</h4>
      <ul>
        <li>-1 = a hindrance to the group in this respect</li>
        <li>0 = no help at all in this aspect</li>
        <li>1 = not as good as most of the group in this respect</li>
        <li>2 = about average in this respect</li>
        <li>3 = better than most of the group in this respect</li>
      </ul>
    </div>
    <div class="alert alert-danger alert-dismissible" role="alert" v-show="err_msg">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <strong>{{err_msg}}</strong>
    </div>
    <div class="row" v-if="marks.length">
      <form id="assessment-form">
        <div class="col-md-6 col-xs-12" v-for="member in group_members" :key="member.id">
          <div class="panel panel-primary">
            <div class="panel-heading">{{ member.user.name }}</div>
            <ul class="list-group" v-for="(list, group_name) in criterias" :key="group_name">
              <li class="list-group-item">
                <b>{{group_name}}</b>
              </li>
              <li class="list-group-item flexbox" v-for="criteria in list" :key="criteria.id">
                <div class="flex-one">{{criteria.name}}</div>
                <div class="flex-none">
                  <input class="form-control" type="number" min="-1" max="3" step="1" :disabled="!assessment.available" v-model="getMarks(member.id, criteria.id).marks" />
                </div>
              </li>
            </ul>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 text-center mb30">
            <input type="submit" class="btn btn-primary" @click="submitForm" :disabled="loading" />
            <input type="reset" class="btn btn-danger" @click="resetForm" :disabled="loading" />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import * as _ from 'lodash';
import { getIntraGroupAssessment, getIntraGroupAssessmentMarks, getGroupMembers, submitIntraGroupAssessmentMarks } from './../api-client';

export default {
  created: function() {
    Promise.all([this.fetchAssessment(), this.fetchGroupMembers()]).then(function() {
      this.fetchMarks();
    }.bind(this));
  },
  methods: {
    fetchAssessment: function() {
      return getIntraGroupAssessment(this.assessment_id)
        .then(function(response) {
          this.assessment = response.data;
        }.bind(this))
        .catch(function(error) {
          console.error(error);
        });
    },
    fetchGroupMembers: function() {
      return getGroupMembers(this.course_id)
        .then(function(response) {
          this.group_members = response.data;
        }.bind(this))
        .catch(function(error) {
          console.error(error);
        });
    },
    fetchMarks: function() {
      return getIntraGroupAssessmentMarks(this.assessment_id)
        .then(function(response) {
          this.marks = this.initMarks(response.data);
        }.bind(this))
        .catch(function(error) {
          console.error(error);
        });
    },
    initMarks: function(marks) {
      const criterias = this.assessment.criterias;
      const assessmentId = this.assessment.id;
      return _.flatMap(this.group_members.map(function(member) {
        return criterias.map(function(criteria) {
          const currentMarks = _.find(marks, { 'to_id': member.id, 'criteria_id': criteria.id });
          if (currentMarks) {
            return currentMarks;
          }
          return {
            'to_id': member.id,
            'asg_id': assessmentId,
            'criteria_id': criteria.id,
            'marks': undefined
          };
        });
      }));
    },
    getMarks: function(toId, criteriaId) {
      return _.find(this.marks, { 'to_id': toId, 'criteria_id': criteriaId });
    },
    submitForm: function() {
      if (!document.querySelector('#assessment-form').reportValidity()) {
        return;
      }
      this.loading = true;
      submitIntraGroupAssessmentMarks(this.assessment.id, this.marks)
        .then(function(response) {
          this.$router.push({ name: 'assessments', params: { course_id: this.course_id } })
        }.bind(this))
        .catch(function(error) {
          this.loading = false;
          this.err_msg = 'Oops! Something is wrong with the server!';
        }.bind(this));
    },
    resetForm: function() {
      this.marks = this.marks.map(function(mark) {
        mark.marks = undefined;
        return mark;
      });
    }
  },
  computed: {
    'criterias': function() {
      return _.groupBy(this.assessment.criterias, 'group_name');
    }
  },
  data: function() {
    return {
      'loading': false,
      'assessment': undefined,
      'err_msg': undefined,
      'group_members': [],
      'marks': []
    };
  },
  props: [
    'assessment_id', 'course_id'
  ]
}
</script>
