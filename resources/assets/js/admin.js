require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);
Vue.component('dashboard', require('./admin/components/Dashboard.vue'));

const routes = [
  { path: '/', redirect: { name: 'courses' } },
  { path: '/courses', name: 'courses', component: require('./admin/components/Courses.vue'), props: true },
  { path: '/courses/:courseId/info', name: 'info', component: require('./admin/components/Info.vue'), props: true },
  { path: '/courses/:courseId/groups', name: 'groups', component: require('./admin/components/Groups.vue'), props: true },
  { path: '/courses/:courseId/students', name: 'students', component: require('./admin/components/Students.vue'), props: true },
  { path: '/courses/:courseId/group_assessments', name: 'group_assessments', component: require('./admin/components/GroupAssessments.vue'), props: true },
  { path: '/courses/:courseId/group_assessments/:assessmentId/marks_config', name: 'group_assessment_marks_config', component: require('./admin/components/GroupAssessmentMarksConfig.vue'), props: true },
  { path: '/courses/:courseId/intra_group_assessments', name: 'intra_group_assessments', component: require('./admin/components/IntraGroupAssessments.vue'), props: true },
  { path: '/courses/:courseId/intra_group_assessments/:assessmentId/criterias', name: 'criterias', component: require('./admin/components/Criterias.vue'), props: true },
  { path: '/courses/:courseId/intra_group_assessments/:assessmentId/marks_config', name: 'intra_group_assessment_marks_config', component: require('./admin/components/IntraGroupAssessmentMarksConfig.vue'), props: true },
  { path : '/courses/:courseId/emails', name: 'emails', component: require('./admin/components/Emails.vue'), props: true }
]

const router = new VueRouter({
  routes
})

const app = new Vue({
  router
}).$mount('#app')