<template>
  <section class="admin group-assessment">
    <div class="page-header">
      <h1>Inter-Group Assessments</h1>
    </div>
    <div class="toolbar">
      <button type="button" class="btn btn-primary" @click="show_create_modal = true">Create</button>
    </div>
    <table v-if="assessments.length" class="table table-hover">
      <thead>
        <tr>
          <th>Name</th>
          <th>Start at</th>
          <th>End at</th>
          <th>Marked</th>
          <th>Calculation Parameters Set</th>
          <th>Actions</th>
          <th>Download Data</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="assessment in assessments" :key="assessment.id">
          <td>{{assessment.name}}</td>
          <td>
            <date-time :time="assessment.start_at"></date-time>
          </td>
          <td>
            <date-time :time="assessment.end_at"></date-time>
          </td>
          <td>{{assessment.marks_count ? '✔' : '✘'}}</td>
          <td>{{assessment.marks_config ? '✔' : '✘'}}</td>
          <td>
            <div>
              <button type="button" class="btn btn-sm btn-info" @click="detail_model = assessment">Detail</button>
              <button type="button" class="btn btn-sm btn-success" v-show="groups.length" @click="marks_model = assessment">Marks</button>
              <router-link :to="{name: 'group_assessment_marks_config', params: {courseId, assessmentId: assessment.id}}" class="btn btn-sm btn-success">
                Set Calculation Parameters
              </router-link>
              <button type="button" class="btn btn-sm btn-warning" @click="update(assessment)">Update</button>
              <button type="button" class="btn btn-sm btn-danger" @click="delete_model = assessment">Delete</button>
            </div>
          </td>
          <td>
            <button type="button" class="btn btn-sm btn-primary" @click="downloadRawData(assessment)">Responses</button>
            <button type="button" class="btn btn-sm btn-primary" :disabled="!assessment.marks_count || !assessment.marks_config" @click="downloadFinalMarks(assessment)">Final Marks</button>
          </td>
        </tr>
      </tbody>
    </table>
    <create-modal v-if="show_create_modal" @close="show_create_modal = false" @created="fetch" :course_id="courseId"></create-modal>
    <update-modal v-if="update_model" @close="update_model = false" @updated="fetch" :model="update_model"></update-modal>
    <delete-modal v-if="delete_model" @close="delete_model = false" @removed="fetch" :model="delete_model"></delete-modal>
    <detail-modal v-if="detail_model" @close="detail_model = false" :model="detail_model"></detail-modal>
    <mark-modal v-if="marks_model" @close="marks_model = false" @marked="fetch" :groups="groups" :students="students" :assessment="marks_model"></mark-modal>
  </section>
</template>

<script>
import DateTime from './shared/DateTime';
import { getCourse, getGroups, getCourseStudents, getGroupAssessments, downloadGroupAssessmentRawData, downloadGroupAssessmentMarks } from './../api-client';
import CreateModal from './modals/CreateGroupAssessment';
import UpdateModal from './modals/UpdateGroupAssessment';
import DeleteModal from './modals/DeleteGroupAssessment';
import DetailModal from './modals/DetailGroupAssessment';
import MarkModal from './modals/MarkGroupAssessment';
const clone = require('lodash').cloneDeep;

export default {
  components: {
    'date-time': DateTime,
    'create-modal': CreateModal,
    'update-modal': UpdateModal,
    'delete-modal': DeleteModal,
    'detail-modal': DetailModal,
    'mark-modal': MarkModal
  },
  data: function() {
    return {
      assessments: [],
      groups: [],
      students: [],
      show_create_modal: false,
      course: undefined,
      delete_model: undefined,
      update_model: undefined,
      detail_model: undefined,
      marks_model: undefined
    };
  },
  methods: {
    update: function(model) {
      this.update_model = clone(model);
    },
    fetchGroups: function() {
      getGroups(this.courseId)
        .then(
          function(response) {
            this.groups = response.data;
          }.bind(this)
        )
        .catch(function(error) {
          console.error(error);
        });
    },
    fetchCourseStudents: function() {
      getCourseStudents(this.courseId)
        .then(
          function(response) {
            this.students = response.data;
          }.bind(this)
        )
        .catch(function(error) {
          console.error(error);
        });
    },
    fetch: function() {
      getCourse(this.courseId)
        .then(
          function(response) {
            this.course = response.data;
          }.bind(this)
        )
        .catch(function(error) {
          console.error(error);
        });
      getGroupAssessments(this.courseId)
        .then(
          function(response) {
            this.assessments = response.data;
          }.bind(this)
        )
        .catch(function(error) {
          console.error(error);
        });
    },
    downloadRawData: function(assessment) {
      downloadGroupAssessmentRawData(this.course, assessment).catch(function(error) {
        console.error(error);
      });
    },
    downloadFinalMarks: function(assessment) {
      return downloadGroupAssessmentMarks(this.course, assessment).catch(function(error) {
        console.error(error);
      });
    }
  },
  created: function() {
    this.fetch();
    this.fetchGroups();
    this.fetchCourseStudents();
  },
  props: ['courseId']
};
</script>
