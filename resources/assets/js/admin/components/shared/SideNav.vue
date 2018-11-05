<template>
<nav v-if="course" class="side-nav">
  <h2>{{course.code}}</h2>
  <h4>{{course.name}}</h4>
  <ul class="nav nav-pills nav-stacked">
    <router-link tag="li" :to="{name: 'info', params: { courseId }}">
      <a>Info</a>
    </router-link>
    <router-link tag="li" :to="{name: 'groups', params: { courseId }}">
      <a>Groups</a>
    </router-link>
    <router-link tag="li" :to="{name: 'students', params: { courseId }}">
      <a>Students</a>
    </router-link>
    <router-link tag="li" :to="{name: 'group_assessments', params: { courseId }}">
      <a>Inter-Group Assessments</a>
    </router-link>
    <router-link tag="li" :to="{name: 'intra_group_assessments', params: { courseId }}">
      <a>Intra-Group Assessments</a>
    </router-link>
    <router-link tag="li" :to="{name: 'emails', params: { courseId }}">
      <a>Send Emails</a>
    </router-link>
  </ul>
</nav>
</template>

<script>
import { getCourse } from './../../api-client';

export default {
  data: function() {
    return {
      courseId: undefined,
      course: undefined
    }
  },
  created: function() {
    const courseId = this.$route.params.courseId;
    if(courseId) {
      this.courseId = courseId;
      this.fetch(courseId);
    }
  },
  watch: {
    '$route.params.courseId' (to, from) {
      if(!to) {
        this.course = undefined;
        this.courseId = undefined;
      } else if(to !== from) {
        this.courseId = to;
        this.fetch(to);
      }
    }
  },
  methods: {
    fetch: function(id) {
        getCourse(id).then(function(response){
          this.course = response.data;
        }.bind(this)).catch(function(){
          this.course = undefined;
        }.bind(this));
    }
  }
}
</script>
