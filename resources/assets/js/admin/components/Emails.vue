<template>
  <section class="emails">
    <div class="page-header" v-if="course">
      <h1>Send Emails for
        <small>{{course.code}} - {{course.name}}</small>
      </h1>
    </div>
    <div class="row">
      <div class="hidden-xs col-md-6 col-lg-8"></div>
      <div class="col-xs-12 col-md-6 col-lg-4" v-if="err_msg">
        <div class="alert alert-danger" role="alert">{{ err_msg }}</div>
      </div>
      <div class="col-xs-12 col-md-6 col-lg-4" v-if="success_msg">
        <div class="alert alert-success" role="alert">{{ success_msg }}</div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-md-6">
        <div class="panel panel-primary" v-if="config.intra_group_assessments.length">
          <div class="panel-heading">Intra Group Assessments to be included</div>
          <ul class="list-group">
            <li class="list-group-item" v-for="assessment in config.intra_group_assessments" :key="assessment.id">
              <div class="checkbox">
                <label>
                  <input type="checkbox" v-model="assessment.marked" :disabled="shouldDisable(assessment)" /> {{assessment.name}}
                </label>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-xs-12 col-md-6">
        <div class="panel panel-success" v-if="config.group_assessments.length">
          <div class="panel-heading">Group Assessments to be included</div>
          <ul class="list-group">
            <li class="list-group-item" v-for="assessment in config.group_assessments" :key="assessment.id">
              <div class="checkbox">
                <label>
                  <input type="checkbox" v-model="assessment.marked" :disabled="shouldDisable(assessment)" /> {{assessment.name}}
                </label>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 text-center">
        <input type="submit" class="btn btn-primary" @click="submitForm" value="Send Emails" :disabled="this.loading" />
      </div>
    </div>
  </section>
</template>

<script>
import { getCourse, getGroupAssessments, getIntraGroupAssessments, sendEmails } from './../api-client';

export default {
  props: ['courseId'],
  data: function() {
    return {
      loading: false,
      course: undefined,
      err_msg: undefined,
      success_msg: undefined,
      config: {
        intra_group_assessments: [],
        group_assessments: [],
      }
    };
  },
  methods: {
    fetchCourse: function() {
      return getCourse(this.courseId)
        .then(function(response) {
          this.course = response.data;
        }.bind(this))
        .catch(function(error) {
          console.error(error);
        });
    },
    fetchGroupAssessments: function() {
      return getGroupAssessments(this.courseId)
        .then(function(response) {
          const assessments = response.data.map(function(assessment) {
            assessment.marked = this.shouldDisable(assessment) ? false : true;
            return assessment;
          }.bind(this));
          this.config.group_assessments = assessments;
        }.bind(this))
        .catch(function(error) {
          console.error(error);
        });
    },
    fetchIntraGroupAssessments: function() {
      return getIntraGroupAssessments(this.courseId)
        .then(function(response) {
          const assessments = response.data.map(function(assessment) {
            assessment.marked = this.shouldDisable(assessment) ? false : true;
            return assessment;
          }.bind(this));
          this.config.intra_group_assessments = assessments;
        }.bind(this))
        .catch(function(error) {
          console.error(error);
        });
    },
    submitForm: function() {
      this.loading = true;
      this.success_msg = undefined;
      this.err_msg = undefined;
      sendEmails(this.courseId, this.config)
        .then(function() {
          this.success_msg = 'Emails are sent successfully! They will receive shortly.';
          this.loading = false;
        }.bind(this))
        .catch(function(error) {
          console.error(error);
          this.err_msg = 'Oops! Something is wrong with the server!';
          this.loading = false;
        }.bind(this));
    },
    shouldDisable: function(assessment) {
      return !assessment.marks_config || assessment.marks_count <= 0;
    }
  },
  created: function() {
    this.fetchCourse();
    this.fetchGroupAssessments();
    this.fetchIntraGroupAssessments();
  }
}
</script>
