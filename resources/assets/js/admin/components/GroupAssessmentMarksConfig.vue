<template>
  <section class="admin group-assessment" v-if="assessment">
    <div class="row mt10">
      <div class="col-xs-12">
        <router-link :to="{name: 'group_assessments', params: {courseId}}">
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
            <config-form :form="assessment.marks_config" @set="notifySet" @err="notifyErr"></config-form>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-md-6 col-lg-8">
        <div class="panel panel-primary" v-if="marks.length">
          <div class="panel-heading">
            <div class="flexbox">
              <div class="flex-one">Marks of students if parameters are set</div>
              <div class="flex-none"><button type="button" class="btn btn-sm btn-success" @click="downloadCurrentMarksData()">Export CSV</button></div>
            </div>
          </div>
          <table class="table">
            <thead>
              <tr>
                <th>Group</th>
                <th>Student</th>
                <th>Raw Peer Marks (a)</th>
                <th>Tutor Marks (b)</th>
                <th>Moderated Peer Marks (c)</th>
                <th>Moderated Peer Marks after Penalties (d)</th>
                <th>Final Marks (e)</th>
                <th>Different (c) - (d)</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="mark in marks" :key="mark.student.id">
                <td>{{ mark.group.name }}</td>
                <td>{{ mark.student.user.name }}</td>
                <td>{{ mark.total_marks | fixDigits(2) }}</td>
                <td>{{ mark.tutor_marks | fixDigits(2) }}</td>
                <td>{{ mark.moderated_marks | fixDigits(2) }}</td>
                <td>{{ mark.final_peer_marks | fixDigits(2) }}</td>
                <td>{{ mark.final_marks | fixDigits(2) }}</td>
                <td>{{ mark.tutor_marks - mark.final_marks | fixDigits(2) }}</td>
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
const csvParser = require('json2csv').Parser;
const download = require('downloadjs');
import { Notification } from 'uiv';
import Form from './forms/GroupAssessmentMarksConfig';
import { getGroupAssessment, calculateGroupAssessmentMarks } from './../api-client';

export default {
  components: {
    'config-form': Form
  },
  props: ['courseId', 'assessmentId'],
  data: function() {
    return {
      assessment: undefined,
      showAlert: undefined,
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
      return getGroupAssessment(this.assessmentId, ['marksConfig', 'marks'])
        .then(
          function(response) {
            const assessment = response.data;
            assessment.marks_config = assessment.marks_config || this.getDefaultConfig(assessment);
            this.assessment = assessment;
          }.bind(this)
        )
        .catch(function(error) {
          console.error(error);
        });
    },
    getDefaultConfig: function(assessment) {
      return {
        asg_id: assessment.id,
        moderate_method: 'high_only',
        upper_limit: _(assessment.marks)
          .map('marks')
          .max(),
        lower_limit: _(assessment.marks)
          .map('marks')
          .min(),
        penalty: 0
      };
    },
    fetchFinalMarks: function() {
      const form = document.querySelector('#model-form');
      if (form && form.checkValidity()) {
        return calculateGroupAssessmentMarks(this.assessmentId, this.assessment.marks_config)
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
    },
    goBack: function() {
      this.$router.push({ name: 'group_assessments', params: { courseId: this.courseId } });
    },
    downloadCurrentMarksData: function() {
      const fields = [
        {
          label: 'Group',
          value: 'group.name'
        },
        {
          label: 'Student',
          value: 'student.user.name'
        },
        {
          label: 'Raw Peer Marks (a)',
          value: 'total_marks'
        },
        {
          label: 'Tutor Marks (b)',
          value: 'tutor_marks'
        },
        {
          label: 'Moderated Peer Marks (c)',
          value: 'moderated_marks'
        },
        {
          label: 'Moderated Peer Marks after Penalties (d)',
          value: 'final_peer_marks'
        },
        {
          label: 'Final Marks (e)',
          value: 'final_marks'
        },
        {
          label: 'Different (c) - (d)',
          value: mark => mark.tutor_marks - mark.final_marks
        }
      ];
      const parser = new csvParser({ fields });
      const csv = parser.parse(this.marks);
      download(csv, `Current marks of ${this.assessment.name}.csv`, 'text/plain');
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