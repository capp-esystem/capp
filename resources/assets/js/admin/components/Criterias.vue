<template>
  <section class="criterias">
    <div class="row mt10">
      <div class="col-xs-12">
        <router-link :to="{name: 'intra_group_assessments', params: {courseId}}">
          <span aria-hidden="true">&larr;</span> Back
        </router-link>
      </div>
    </div>
    <div class="page-header" v-if="assessment">
      <h1>Criterias for
        <small>{{assessment.name}}</small>
      </h1>
    </div>
    <div class="toolbar">
      <button type="button" class="btn btn-primary" @click="show_create_modal = true">Create</button>
      <button type="button" class="btn btn-primary" @click="show_batch_create_modal = true">Create Multiples</button>
      <button type="button" class="btn btn-primary" @click="show_import_modal = true">Import</button>
    </div>
    <div class="waterfall mt10">
      <div class="panel panel-success" v-for="(list, group_name) in criterias" :key="group_name">
        <div class="panel-heading">
          <div class="panel-title flexbox vcenter">
            <h3 class="flex-one panel-title">{{group_name || 'Ungrouped'}}</h3>
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-sm btn-warning" @click="update_models = list">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
              </button>
              <button type="button" class="btn btn-sm btn-danger" @click="delete_models = list">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
              </button>
            </div>
          </div>
        </div>
        <ul class="list-group">
          <li class="list-group-item flexbox" v-for="criteria in list" :key="criteria.id">
            <div class="flex-one">
              {{criteria.name}}
            </div>
            <div class="flex-none">
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-warning" @click="update(criteria)">
                  <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn btn-sm btn-danger" @click="delete_model = criteria">
                  <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </button>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <import-modal v-if="show_import_modal" @close="show_import_modal = false" @created="fetch" :assessment="assessment"></import-modal>
    <create-modal v-if="show_create_modal" @close="show_create_modal = false" @created="fetch" :assessment_id="assessmentId"></create-modal>
    <batch-create-modal v-if="show_batch_create_modal" @close="show_batch_create_modal = false" @created="fetch" :assessment_id="assessmentId"></batch-create-modal>
    <update-modal v-if="update_model" @close="update_model = false" @updated="fetch" :model="update_model"></update-modal>
    <delete-modal v-if="delete_model" @close="delete_model = false" @removed="fetch" :model="delete_model"></delete-modal>
    <update-group-modal v-if="update_models" @close="update_models = false" @updated="fetch" :model="update_models"></update-group-modal>
    <delete-group-modal v-if="delete_models" @close="delete_models = false" @removed="fetch" :model="delete_models"></delete-group-modal>
  </section>
</template>

<script>
import { getIntraGroupAssessment, getCriterias } from './../api-client';
import CreateModal from './modals/CreateCriteria';
import BatchCreateModal from './modals/CreateCriterias';
import UpdateModal from './modals/UpdateCriteria';
import UpdateGroupModal from './modals/UpdateCriteriaGroup';
import ImportModal from './modals/ImportCriterias';
import DeleteModal from './modals/DeleteCriteria';
import DeleteGroupModal from './modals/DeleteCriteriaGroup';
const groupBy = require('lodash').groupBy;
const clone = require('lodash').cloneDeep;

export default {
  components: {
    'create-modal': CreateModal,
    'batch-create-modal': BatchCreateModal,
    'update-modal': UpdateModal,
    'update-group-modal': UpdateGroupModal,
    'import-modal': ImportModal,
    'delete-modal': DeleteModal,
    'delete-group-modal': DeleteGroupModal
  },
  created() {
    this.fetchAssessment(this.assessmentId);
    this.fetchCriterias(this.assessmentId);
  },
  methods: {
    fetch() {
      return this.fetchCriterias(this.assessmentId)
    },
    fetchAssessment(id) {
      return getIntraGroupAssessment(id).then(function(response) {
        this.assessment = response.data;
      }.bind(this)).catch(function(error) {
        console.error(error);
      });
    },
    fetchCriterias(assessmentId) {
      return getCriterias(assessmentId).then(function(response) {
        const criterias = response.data;
        this.criterias = groupBy(criterias, 'group_name');
      }.bind(this)).catch(function(error) {
        console.error(error);
      });
    },
    update: function(model) {
      this.update_model = clone(model);
    },
  },
  data: function() {
    return {
      assessment: undefined,
      criterias: [],
      show_create_modal: false,
      show_import_modal: false,
      show_batch_create_modal: false,
      delete_model: undefined,
      update_model: undefined,
      update_models: undefined,
      marks_model: undefined,
      delete_models: undefined,
    }
  },
  props: [
    'courseId', 'assessmentId'
  ]
}
</script>