<template>
  <base-modal :title="title" :model="model" :create="create" @created="$emit('created')" @close="$emit('close')" btn_text="Import">
    <model-form slot="form" @import="importFromAssessment" :form="model" :assessments="assessments"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/CriteriasImport';
import BaseModal from './Create';
import { getIntraGroupAssessments, getCriterias, createCriterias } from './../../api-client';

export default {
  components: {
    'base-modal': BaseModal,
    'model-form': Form,
  },
  created: function() {
    this.fetchAssessments();
  },
  computed: {
    title: function() {
      return `Import criteria for assessment - ${this.assessment.name}`;
    }
  },
  methods: {
    fetchAssessments: function() {
      getIntraGroupAssessments(undefined)
        .then(function(response) {
          this.assessments = response.data;
        }.bind(this))
        .catch(function(error) {
          console.error(error);
        });
    },
    create: function() {
      return createCriterias(this.model);
    },
    importFromAssessment: function(assessmentId) {
      if(assessmentId) {
        getCriterias(assessmentId)
        .then(function(response) {
          const targetAssessmentId = this.assessment.id;
          this.model = response.data.map(function(criteria) {
            criteria.asg_id = targetAssessmentId;
            return criteria;
          });
        }.bind(this))
        .catch(function(error) {
          console.error(error);
        });
      } else {
        this.model = [];
      }
    }
  },
  data: function() {
    return {
      assessments: [],
      model: []
    }
  },
  props: ['assessment']
}
</script>