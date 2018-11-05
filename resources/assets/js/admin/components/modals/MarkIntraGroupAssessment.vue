<template>
  <base-modal v-if="model.length" :title="title" :model="model" :upsert="upsert" @marked="$emit('marked')" @close="$emit('close')">
    <p slot="content">
      <b>Max Marks: 100</b>
    </p>
    <model-form slot="form" @import="importFromGpAssessment" :form="model" :groups="groups" :group_assessments="group_assessments"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/IntraGroupAssessmentMark';
import MarkModal from './Mark';
import { getGroupAssessmentMarks, getIntraGroupAssessmentMarks, upsertIntraGroupAssessmentMarks } from './../../api-client';

export default {
  components: {
    'base-modal': MarkModal,
    'model-form': Form,
  },
  created: function() {
    getIntraGroupAssessmentMarks(this.assessment.id)
      .then(function(response) {
        this.model = response.data;
      }.bind(this))
      .catch(function(error) {
        console.error(error);
      });
  },
  computed: {
    title: function() {
      return `Marks for assessment - ${this.assessment.name}`;
    }
  },
  methods: {
    upsert: function(form) {
      return upsertIntraGroupAssessmentMarks(this.assessment.id, form);
    },
    importFromGpAssessment: function(assessmentId) {
      getGroupAssessmentMarks(assessmentId)
      .then(function(response) {
        const targetAssessmentId = this.assessment.id;
        this.model = response.data.map(function(mark){
          mark.asg_id = targetAssessmentId;
          return mark;
        });
      }.bind(this))
      .catch(function(error) {
        console.error(error);
      });
    }
  },
  data: function() {
    return {
      model: []
    }
  },
  props: ['groups', 'assessment', 'group_assessments']
}
</script>