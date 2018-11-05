import * as axios from 'axios';
const download = require('downloadjs');

function getCourses() {
  return axios.get('/api/courses');
}

function getCourse(id, attributes, withCount) {
  return axios.get(`/api/courses/${id}`, {
    params: {
      with: attributes,
      withCount
    }
  });
}

function createCourse(data) {
  return axios.post('/api/courses', data);
}

function updateCourse(data) {
  data['_method'] = 'PUT';
  return axios.post(`/api/courses/${data.id}`, data);
}

function delCourse(data) {
  return axios.delete(`/api/courses/${data.id}`);
}

function getGroups(courseId) {
  return axios.get('/api/groups', {
    params: {
      course_id: courseId
    }
  });
}

function createGroup(data) {
  return axios.post('/api/groups', data);
}

function createGroups(courseId, file) {
  const data = new FormData();
  data.append('course_id', courseId);
  data.append('students_file', file);
  return axios.post('/api/groups/batch', data);
}

function updateGroup(data) {
  data['_method'] = 'PUT';
  return axios.post(`/api/groups/${data.id}`, data);
}

function delGroup(data) {
  return axios.delete(`/api/groups/${data.id}`);
}

function getCourseStudents(courseId) {
  return axios.get('/api/courses_students', {
    params: {
      course_id: courseId
    }
  });
}

function createStudent(data) {
  return axios.post('/api/students', data);
}

function createStudents(courseId, file) {
  const data = new FormData();
  data.append('course_id', courseId);
  data.append('students_file', file);
  return axios.post('/api/students/batch', data);
}

function createCourseStudent(data) {
  return axios.post('/api/courses_students', data);
}

function createCourseStudents(courseId, file) {
  const data = new FormData();
  data.append('course_id', courseId);
  data.append('students_file', file);
  return axios.post('/api/courses_students/batch', data);
}

function updateStudent(data) {
  data['_method'] = 'PUT';
  return axios.post(`/api/students/${data.id}`, data);
}

function updateStudentPassword(data) {
  return axios.post(`/api/students/${data.id}/change_password`, data);
}

function updateCourseStudent(data) {
  data['_method'] = 'PUT';
  return axios.post(`/api/courses_students/${data.course_relation_id}`, data);
}

function delCourseStudent(data) {
  return axios.delete(`/api/courses_students/${data.course_relation_id}`);
}

function getGroupAssessments(courseId) {
  return axios.get('/api/group_assessments', {
    params: {
      course_id: courseId
    }
  });
}

function getGroupAssessment(id, columns) {
  return axios.get(`/api/group_assessments/${id}`, {
    params: {
      with: columns
    }
  });
}

function createGroupAssessment(data) {
  return axios.post('/api/group_assessments', data);
}

function updateGroupAssessment(data) {
  data['_method'] = 'PUT';
  return axios.post(`/api/group_assessments/${data.id}`, data);
}

function delGroupAssessment(data) {
  return axios.delete(`/api/group_assessments/${data.id}`);
}

function getIntraGroupAssessment(id) {
  return axios.get(`/api/intra_group_assessments/${id}`, {
    params: {
      with: ['marksConfig']
    }
  });
}

function getIntraGroupAssessments(courseId) {
  return axios.get('/api/intra_group_assessments', {
    params: {
      course_id: courseId
    }
  });
}

function createIntraGroupAssessment(data) {
  return axios.post('/api/intra_group_assessments', data);
}

function updateIntraGroupAssessment(data) {
  data['_method'] = 'PUT';
  return axios.post(`/api/intra_group_assessments/${data.id}`, data);
}

function delIntraGroupAssessment(data) {
  return axios.delete(`/api/intra_group_assessments/${data.id}`);
}

function getGroupAssessmentMarks(assessmentId) {
  return axios.get(`/api/group_assessments/${assessmentId}/marks`);
}

function upsertGroupAssessmentMarks(assessmentId, marks) {
  return axios.post(`/api/group_assessments/${assessmentId}/marks`, {
    marks
  });
}

function getIntraGroupAssessmentMarks(assessmentId) {
  return axios.get(`/api/intra_group_assessments/${assessmentId}/marks`);
}

function upsertIntraGroupAssessmentMarks(assessmentId, marks) {
  return axios.post(`/api/intra_group_assessments/${assessmentId}/marks`, {
    marks
  });
}

function getCriterias(assessmentId) {
  return axios.get('/api/criterias', {
    params: {
      asg_id: assessmentId
    }
  });
}

function createCriteria(data) {
  return axios.post(`/api/criterias/`, data);
}

function createCriterias(list) {
  const data = { list };
  return axios.post(`/api/criterias/batch`, data);
}

function updateCriteria(data) {
  data['_method'] = 'PUT';
  return axios.post(`/api/criterias/${data.id}`, data);
}

function updateCriterias(data) {
  data = { list: data };
  data['_method'] = 'PUT';
  return axios.post(`/api/criterias/`, data);
}

function delCriteria(data) {
  return axios.delete(`/api/criterias/${data.id}`);
}

function delCriterias(data) {
  data = { list: data };
  data['_method'] = 'DELETE';
  return axios.post(`/api/criterias`, data);
}

function downloadGroupAssessmentRawData(course, assessment) {
  return axios.get(`/api/group_assessments/${assessment.id}/download/raw_data`, { responseType: 'arraybuffer' }).then(function(response) {
    download(response.data, `${course.code}_Inter-gp_RawData_${assessment.name}.xlsx`);
  });
}

function downloadIntraGroupAssessmentRawData(course, assessment) {
  return axios.get(`/api/intra_group_assessments/${assessment.id}/download/raw_data`, { responseType: 'arraybuffer' }).then(function(response) {
    download(response.data, `${course.code}_Intra-gp_RawData_${assessment.name}.xlsx`);
  });
}

function setIntraGroupAssessmentMarksConfig(config) {
  return axios.post(`/api/intra_group_assessment_marks_config`, config);
}

function setGroupAssessmentMarksConfig(config) {
  return axios.post(`/api/group_assessment_marks_config`, config);
}

function calculateIntraGroupAssessmentMarks(id, config) {
  return axios.post(`api/intra_group_assessments/${id}/calculate_marks`, {
    config
  });
}

function calculateGroupAssessmentMarks(id, config) {
  return axios.post(`api/group_assessments/${id}/calculate_marks`, {
    config
  });
}

function downloadGroupAssessmentMarks(course, assessment) {
  return axios.get(`api/group_assessments/${assessment.id}/download/marks`, { responseType: 'arraybuffer' }).then(function(response) {
    download(response.data, `${course.code}_Inter-gp_FinalMarks_${assessment.name}.xlsx`);
  });
}

function downloadIntraGroupAssessmentMarks(course, assessment) {
  return axios.get(`api/intra_group_assessments/${assessment.id}/download/marks`, { responseType: 'arraybuffer' }).then(function(response) {
    download(response.data, `${course.code}_Intra-gp_RawData_${assessment.name}.xlsx`);
  });
}

function sendEmails(courseId, data) {
  return axios.post(`api/emails/${courseId}`, {
    config: data
  });
}

export {
  getCourses,
  createCourse,
  getCourse,
  updateCourse,
  delCourse,
  getGroups,
  createGroup,
  createGroups,
  updateGroup,
  delGroup,
  getCourseStudents,
  createCourseStudents,
  updateCourseStudent,
  delCourseStudent,
  createStudent,
  createCourseStudent,
  updateStudent,
  createStudents,
  updateStudentPassword,
  getGroupAssessments,
  getGroupAssessment,
  createGroupAssessment,
  updateGroupAssessment,
  delGroupAssessment,
  calculateGroupAssessmentMarks,
  downloadGroupAssessmentMarks,
  getIntraGroupAssessment,
  getIntraGroupAssessments,
  createIntraGroupAssessment,
  updateIntraGroupAssessment,
  delIntraGroupAssessment,
  getGroupAssessmentMarks,
  upsertGroupAssessmentMarks,
  getIntraGroupAssessmentMarks,
  upsertIntraGroupAssessmentMarks,
  calculateIntraGroupAssessmentMarks,
  downloadIntraGroupAssessmentMarks,
  getCriterias,
  createCriteria,
  createCriterias,
  updateCriteria,
  delCriteria,
  updateCriterias,
  delCriterias,
  downloadGroupAssessmentRawData,
  downloadIntraGroupAssessmentRawData,
  setIntraGroupAssessmentMarksConfig,
  setGroupAssessmentMarksConfig,
  sendEmails
};
