<template>
  <div class="courses">
    <div class="page-header">
      <h1>Courses</h1>
    </div>
    <div class="list-group">
      <router-link :to="{name: 'assessments', params: {course_id: course.id}}" class="list-group-item" v-for="course in courses" :key="course.id">
        <h4 class="list-group-item-heading">{{ course.name }}</h4>
        <p class="list-group-item-text">{{ course.available ? 'Available' : 'Not available' }}</p>
      </router-link>
    </div>
  </div>
</template>

<script>
import { getCourses } from './../api-client';

export default {
  created: function() {
    this.fetchCourses();
  },
  methods: {
    fetchCourses: function() {
      return getCourses()
      .then(function(response){
        this.courses = response.data;
      }.bind(this))
      .catch(function(error){
        console.error(error);
      });
    }
  },
  data: function() {
    return {
      'courses': []
    };
  }
}
</script>
