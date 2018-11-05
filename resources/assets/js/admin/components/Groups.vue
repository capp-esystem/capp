<template>
    <section class="admin groups">
        <div class="page-header">
            <h1>Groups</h1>
        </div>
        <div class="toolbar">
            <button type="button" class="btn btn-primary" @click="show_create_modal = true">Create</button>
        </div>
        <table v-if="groups.length" class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>No of students</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="group in groups" :key="group.id">
                    <td>{{group.name}}</td>
                    <td>{{group.members_count || 0}}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-warning" @click="update(group)">Update</button>
                            <button type="button" class="btn btn-sm btn-danger" @click="delete_model = group">Delete</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <create-modal v-if="show_create_modal" @close="show_create_modal = false" @created="fetch" :course_id="courseId"></create-modal>
        <update-modal v-if="update_model" @close="update_model = false" @updated="fetch" :model="update_model"></update-modal>
        <delete-modal v-if="delete_model" @close="delete_model = false" @removed="fetch" :model="delete_model"></delete-modal>
    </section>
</template>

<script>
import { getGroups } from './../api-client';
import CreateModal from './modals/CreateGroup';
import UpdateModal from './modals/UpdateGroup';
import DeleteModal from './modals/DeleteGroup';
const clone = require('lodash/cloneDeep');
const sortBy = require('lodash/sortBy');

export default {
    components: {
        'create-modal': CreateModal,
        'update-modal': UpdateModal,
        'delete-modal': DeleteModal,
    },
    data: function() {
        return {
            'groups': [],
            show_create_modal: false,
            delete_model: undefined,
            update_model: undefined,
        }
    },
    created: function() {
        this.fetch();
    },
    methods: {
        fetch: function() {
            getGroups(this.courseId).then(function(response) {
                this.groups = sortBy(response.data, ['name']);
            }.bind(this)).catch(function(error) {
                console.error(error);
            });
        },
        update: function(model) {
            this.update_model = clone(model);
        }
    },
    props: ['courseId']
}
</script>