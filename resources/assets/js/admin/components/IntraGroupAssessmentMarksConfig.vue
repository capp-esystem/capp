<template>
  <section class="admin intra-group-assessment" v-if="assessment">
    <div class="row mt10">
      <div class="col-xs-12">
        <router-link :to="{name: 'intra_group_assessments', params: {courseId}}">
          <span aria-hidden="true">&larr;</span> Back
        </router-link>
      </div>
    </div>
    <div class="page-header">
      <h1>Set Calculation Parameters for
        <small>{{ assessment.name }}</small>
      </h1>
    </div>
    <div class="row">
      <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="panel panel-primary">
          <div class="panel-body">
            <config-form :form="assessment.marks_config" @set="notifySet" @err="notifyErr" @updateVal="updateMarksConfigAdjustValue"></config-form>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-md-6 col-lg-8">
        <div class="panel panel-primary" v-if="marks.length">
          <div class="panel-heading">
            Marks of students if config are set
          </div>
          <table class="table">
            <thead>
              <tr>
                <th>Group</th>
                <th>Student</th>
                <th>Raw Marks</th>
                <th>PA Score</th>
                <th>Adjusted PA Score</th>
                <th>Final Marks</th>
                <th>Adjusted Final Marks</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="mark in marks" :key="mark.student.id">
                <td>{{ mark.student.group.name }}</td>
                <td>{{ mark.student.user.name }}</td>
                <td>{{ mark.raw_marks | fixDigits(2) }}</td>
                <td>{{ mark.pa_score | fixDigits(2) }}</td>
                <td>{{ mark.adjusted_pa_score | fixDigits(2) }}</td>
                <td>{{ mark.final_marks | fixDigits(2) }}</td>
                <td>{{ mark.adjusted_final_marks | fixDigits(2) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
const debounce = require('lodash/debounce');
import Form from './forms/IntraGroupAssessmentMarksConfig';
import { Notification } from 'uiv';
import { getIntraGroupAssessment, calculateIntraGroupAssessmentMarks } from './../api-client';

export default {
  components: {
    'config-form': Form
  },
  props: ['courseId', 'assessmentId'],
  data: function() {
    return {
      assessment: undefined,
      marks: []
    };
  },
  watch: {
    'assessment.marks_config': {
      handler: debounce(function(newVal) {
        this.fetchFinalMarks();
      }, 1000),
      deep: true
    }
  },
  methods: {
    fetchAssessment: function() {
      return getIntraGroupAssessment(this.assessmentId)
        .then(
          function(response) {
            const assessment = response.data;
            assessment.marks_config = assessment.marks_config || this.getDefaultConfig();
            this.assessment = assessment;
          }.bind(this)
        )
        .catch(function(error) {
          console.error(error);
        });
    },
    fetchFinalMarks: function() {
      if (this.assessment.marks_config.pa_score_adjust_method === 'default' || this.assessment.marks_config.pa_score_adjust_value) {
        return calculateIntraGroupAssessmentMarks(this.assessmentId, this.assessment.marks_config)
          .then(
            function(response) {
              this.marks = response.data;
            }.bind(this)
          )
          .catch(function(error) {
            console.error(error);
          });
      }
    },
    updateMarksConfigAdjustValue: function(val) {
      this.assessment.marks_config.pa_score_adjust_value = val;
    },
    getDefaultConfig: function() {
      return {
        asg_id: this.assessmentId,
        pa_score_adjust_method: 'default',
        pa_score_adjust_value: undefined,
        max_cap: 100,
        penalty: 0
      };
    },
    notifySet: function() {
      Notification.notify({
        type: 'success',
        title: 'Change Saved',
        duration: 5000
      });
    },
    notifyErr: function() {
      Notification.notify({
        type: 'danger',
        title: 'Change FAILED to save, please retry',
        duration: 5000
      });
    }
  },
  filters: {
    fixDigits: function(number, digits) {
      return number.toFixed(digits);
    }
  },
  created: function() {
    this.fetchAssessment();
  }
};
</script>