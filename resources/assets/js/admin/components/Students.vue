<template>
    <section class="admin students">
        <div class="page-header">
            <h1>Students</h1>
        </div>
        <div v-show="groups" class="toolbar">
            <button type="button" class="btn btn-primary" @click="show_create_modal = true">Create</button>
            <button type="button" class="btn btn-primary" @click="show_batch_create_modal = true">Batch Add</button>
        </div>
        <table v-if="students.length" class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>SID</th>
                    <th>Email</th>
                    <th>Group</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="student in students" :key="student.id">
                    <td>{{student.name}}</td>
                    <td>{{student.sid}}</td>
                    <td>{{student.email}}</td>
                    <td>{{student.group ? student.group.name : 'Nil'}}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-warning" @click="update(student)">Update</button>
                            <button type="button" class="btn btn-sm btn-danger" @click="resetPassword(student)">Reset Password</button>
                            <button type="button" class="btn btn-sm btn-danger" @click="delete_model = student">Delete</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <create-modal v-if="show_create_modal" @close="show_create_modal = false" @created="fetch" :course_id="courseId" :groups="groups"></create-modal>
        <batch-create-modal v-if="show_batch_create_modal" @close="show_batch_create_modal = false" @created="fetch" :course_id="courseId"></batch-create-modal>
        <reset-password-modal v-if="reset_password_model" @close="reset_password_model = false" :model="reset_password_model"></reset-password-modal>
        <update-modal v-if="update_model" @close="update_model = false" @updated="fetch" :groups="groups" :model="update_model"></update-modal>
        <delete-modal v-if="delete_model" @close="delete_model = false" @removed="fetch" :model="delete_model"></delete-modal>
    </section>
</template>

<script>
import { getCourseStudents, getGroups } from './../api-client';
import BatchCreateModal from './modals/CreateStudents';
import CreateModal from './modals/CreateStudent';
import DeleteModal from './modals/DeleteStudent';
import UpdateModal from './modals/UpdateStudent';
import ResetPasswordModal from './modals/ResetPassword';
const clone = require('lodash/cloneDeep');
const orderBy = require('lodash/orderBy');

export default {
  components: {
    'batch-create-modal': BatchCreateModal,
    'create-modal': CreateModal,
    'delete-modal': DeleteModal,
    'update-modal': UpdateModal,
    'reset-password-modal': ResetPasswordModal
  },
  data: function() {
    return {
      students: [],
      groups: [],
      show_batch_create_modal: false,
      show_create_modal: false,
      delete_model: undefined,
      update_model: undefined,
      reset_password_model: undefined
    };
  },
  methods: {
    update: function(model) {
      this.update_model = clone(model);
    },
    fetch: function() {
      getGroups(this.courseId)
        .then(
          function(response) {
            this.groups = response.data;
          }.bind(this)
        )
        .catch(function(error) {
          console.error(error);
        });
      getCourseStudents(this.courseId)
        .then(
          function(response) {
            this.students = orderBy(response.data, ['group_id', 'name']);
          }.bind(this)
        )
        .catch(function(error) {
          console.error(error);
        });
    },
    resetPassword: function(student) {
      student.password = undefined;
      this.reset_password_model = student;
    }
  },
  created: function() {
    this.fetch();
  },
  props: ['courseId']
};
</script>
