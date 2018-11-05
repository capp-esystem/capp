require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);
Vue.component('main-app', require('./student/components/Main.vue'));

const routes = [
  { path: '/', redirect: { name: 'courses' } },
  { path: '/courses', name: 'courses', component: require('./student/components/Courses.vue'), props: true },
  { path: '/courses/:course_id', name: 'assessments', component: require('./student/components/Assessments.vue'), props: true},
  { path: '/courses/:course_id/intra_group_assessments/:assessment_id', name: 'intra_group_assessment', component: require('./student/components/IntraGroupAssessment.vue'), props: true},
  { path: '/courses/:course_id/inter_group_assessments/:assessment_id', name: 'inter_group_assessment', component: require('./student/components/InterGroupAssessment.vue'), props: true}
]

const router = new VueRouter({
  routes
})

const app = new Vue({
  router
}).$mount('#app')