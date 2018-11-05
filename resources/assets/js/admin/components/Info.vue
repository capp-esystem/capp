<template>
  <section class="info" v-if="course">
    <div class="page-header">
      <h1>Info of
        <small>{{course.code}} {{course.name}}</small>
      </h1>
    </div>
    <div class="waterfall col-4 info-panels">
      <div class="text-center panel panel-primary">
        <h2>{{course.students_count}}</h2>
        <p>students</p>
      </div>
      <div class="text-center panel panel-primary">
        <h2>{{course.groups_count}}</h2>
        <p>groups</p>
      </div>
      <div class="text-center panel panel-primary">
        <h2>{{course.group_assessments_count}}</h2>
        <p>inter-group assessments</p>
      </div>
      <div class="text-center panel panel-primary">
        <h2>{{course.intra_group_assessments_count}}</h2>
        <p>intra-group assessments</p>
      </div>
    </div>
  </section>
</template>

<script>
import { getCourse } from './../api-client';

export default {
  props: ['courseId'],
  data: function() {
    return {
      course: undefined
    };
  },
  methods: {
    fetchCourse: function() {
      getCourse(this.courseId, undefined, ['groups', 'students', 'groupAssessments', 'intraGroupAssessments'])
        .then(function(response) {
          this.course = response.data;
        }.bind(this))
        .catch(function(error) {
          console.error(error);
        });
    }
  },
  created: function() {
    this.fetchCourse();
  }
}
</script>
