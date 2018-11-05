import * as axios from 'axios';
import * as _ from 'lodash';

function getCourses() {
  return axios.get('/student-api/courses');
}

function getCourse(id, attributes) {
  return axios.get(`/student-api/courses/${id}`, {
    params: {
      with: attributes
    }
  });
}

function getGroups(courseId) {
  return axios.get(`/student-api/courses/${courseId}/groups`);
}

function getIntraGroupAssessment(id) {
  return axios.get(`/student-api/intra_group_assessments/${id}`);
}

function getInterGroupAssessment(id) {
  return axios.get(`/student-api/inter_group_assessments/${id}`);
}

function getGroupMembers(courseId) {
  return axios.get(`/student-api/courses/${courseId}/group_members`, {
    params: {
      with: ['user']
    }
  });
}

function getIntraGroupAssessmentMarks(id) {
  return axios.get(`/student-api/intra_group_assessments/${id}/marks`);
}

function submitIntraGroupAssessmentMarks(id, marks) {
  return axios.post(`/student-api/intra_group_assessments/${id}/marks`, {
    marks
  });
}

function getCourseStudent(courseId) {
  return axios.get(`/student-api/course_student/${courseId}`);
}

function getInterGroupAssessmentMarks(assessmentId) {
  return axios.get(`/student-api/inter_group_assessments/${assessmentId}/marks`);
}

function getInterGroupAssessmentResponses(assessmentId) {
  return axios.get(`/student-api/inter_group_assessments/${assessmentId}/responses`);
}

function submitInterGroupAssessmentMarks(assessmentId, marks) {
  return axios.post(`/student-api/inter_group_assessments/${assessmentId}/marks`, {
    marks
  });
}

function submitInterGroupAssessmentResponses(assessmentId, responses) {
  return axios.post(`/student-api/inter_group_assessments/${assessmentId}/responses`, {
    responses
  });
}

function getAnsweredInterGroupAssessments(courseId) {
  return axios.get(`/student-api/courses/${courseId}/inter_group_assessments_answered`);
}

function getAnsweredIntraGroupAssessments(courseId) {
  return axios.get(`/student-api/courses/${courseId}/intra_group_assessments_answered`);
}

export {
  getCourses,
  getCourse,
  getIntraGroupAssessment,
  getInterGroupAssessment,
  getGroupMembers,
  getGroups,
  getIntraGroupAssessmentMarks,
  submitIntraGroupAssessmentMarks,
  getCourseStudent,
  getInterGroupAssessmentMarks,
  getInterGroupAssessmentResponses,
  submitInterGroupAssessmentMarks,
  submitInterGroupAssessmentResponses,
  getAnsweredInterGroupAssessments,
  getAnsweredIntraGroupAssessments
};
