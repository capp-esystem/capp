<template>
    <section class="admin courses">
        <page-header title="Courses" @create="show_create_modal = true"></page-header>
        <table v-if="courses.length" class="table table-hover">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Start at</th>
                    <th>End at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="course in courses" :key="course.id">
                    <td>{{course.code}}</td>
                    <td>{{course.name}}</td>
                    <td>
                        <date-time :time="course.start_at"></date-time>
                    </td>
                    <td>
                        <date-time :time="course.end_at"></date-time>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-info" @click="visitDetail(course)">Visit</button>
                            <button type="button" class="btn btn-sm btn-warning" @click="update(course)">Update</button>
                            <button type="button" class="btn btn-sm btn-danger" @click="delete_model = course">Delete</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <create-modal v-if="show_create_modal" @close="show_create_modal = false" @created="refresh"></create-modal>
        <update-modal v-if="update_model" @close="update_model = undefined" @updated="refresh" :model="update_model"></update-modal>
        <delete-modal v-if="delete_model" @close="delete_model = undefined" @removed="refresh" :model="delete_model"></delete-modal>
    </section>
</template>

<script>
import DateTime from './shared/DateTime';
import PageHeader from './shared/PageHeader';
import CreateModal from './modals/CreateCourse';
import UpdateModal from './modals/UpdateCourse';
import DeleteModal from './modals/DeleteCourse';
import { getCourses } from './../api-client';
const clone = require('lodash').cloneDeep;

export default {
    components: {
        'date-time': DateTime,
        'page-header': PageHeader,
        'create-modal': CreateModal,
        'update-modal': UpdateModal,
        'delete-modal': DeleteModal
    },
    data: function() {
        return {
            courses: [],
            show_create_modal: false,
            delete_model: undefined,
            update_model: undefined,
        };
    },
    created: function() {
        this.refresh();
    },
    methods: {
        refresh: function() {
            getCourses().then(function(response) {
                this.courses = response.data;
            }.bind(this)).catch(function(error) {
                console.error(error);
            });
        },
        update: function(course) {
            this.update_model = clone(course);
        },
        visitDetail: function(course) {
            this.$router.push({ name: 'info', params: { courseId: course.id } });
        }
    }
}
</script>
