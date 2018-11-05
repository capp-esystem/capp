<template>
  <div class="assessments" v-if="course">
    <div class="page-header">
      <h1>Assessments for <small>{{course.code}} {{course.name}}</small> </h1>
    </div>
    <div class="row">
      <div class="col-md-6 col-xs-12" v-if="course.intra_group_assessments.length">
        <div class="panel panel-primary">
          <div class="panel-heading">Intra-group peer assessments and self-assessments on individual’s contribution</div>
          <ul class="list-group">
            <router-link :to="{name: 'intra_group_assessment', params: {course_id: course_id, assessment_id: assessment.id}}" class="list-group-item flexbox vcenter" v-for="assessment in course.intra_group_assessments" :key="assessment.id">
              <div class="flex-one">
                <h4>{{assessment.name}}</h4>
                <div>Due date: {{assessment.end_at}}</div>
              </div>
              <div class="flex-none">{{'answered' in assessment ? (assessment.answered ? 'Answered' : '') : '' }}</div>
            </router-link>
          </ul>
        </div>
      </div>
      <div class="col-md-6 col-xs-12" v-if="course.group_assessments.length">
        <div class="panel panel-primary">
          <div class="panel-heading">Inter-group peer assessment on presentation/report</div>
          <ul class="list-group">
            <router-link :to="{name: 'inter_group_assessment', params: {course_id: course_id, assessment_id: assessment.id}}" class="list-group-item flexbox vcenter" v-for="assessment in course.group_assessments" :key="assessment.id">
              <div class="flex-one">
                <h4>{{assessment.name}}</h4>
                <div>Due date: {{assessment.end_at}}</div>
              </div>
              <div class="flex-none">{{'answered' in assessment ? (assessment.answered ? 'Answered' : '') : '' }}</div>
            </router-link>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const includes = require('lodash').includes;
import { getCourse, getAnsweredInterGroupAssessments, getAnsweredIntraGroupAssessments } from './../api-client';

export default {
  data: function() {
    return {
      course: undefined
    }
  },
  created: function() {
    this.fetchCourse(this.course_id).then(function(){
      this.fetchAnsweredInterGroupAssessments(this.course_id);
      this.fetchAnsweredIntraGroupAssessments(this.course_id);
    }.bind(this));
  },
  methods: {
    fetchCourse: function(courseId) {
      return getCourse(courseId, ['groupAssessments', 'intraGroupAssessments'])
        .then(function(response) {
          this.course = response.data;
        }.bind(this))
        .catch(function(error) {
          console.error(error);
        });
    },
    fetchAnsweredInterGroupAssessments: function(courseId) {
      return getAnsweredInterGroupAssessments(courseId).then(function(response){
        const answeredList = response.data;
        this.course.group_assessments = this.course.group_assessments.map(function(assessment){
          assessment.answered = includes(answeredList, assessment.id);
          return assessment;
        });
      }.bind(this))
      .catch(function(error){
      })
    },
    fetchAnsweredIntraGroupAssessments: function(courseId) {
      return getAnsweredIntraGroupAssessments(courseId).then(function(response){
        const answeredList = response.data;
        this.course.intra_group_assessments = this.course.intra_group_assessments.map(function(assessment){
          assessment.answered = includes(answeredList, assessment.id);
          return assessment;
        });
      }.bind(this))
      .catch(function(error){
      })
    }
  },
  props: ['course_id']
}
</script>
