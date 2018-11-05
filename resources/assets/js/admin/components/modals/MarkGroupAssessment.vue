<template>
  <base-modal v-if="model.length" :title="title" :model="model" :upsert="upsert" @marked="$emit('marked')" @close="$emit('close')">
    <p slot="content"><b>Max Marks: 100</b></p>
    <model-form slot="form" :form="model" :groups="groups" :students="students"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/GroupAssessmentMark';
import MarkModal from './Mark';
import { getGroupAssessmentMarks, upsertGroupAssessmentMarks } from './../../api-client';

export default {
  components: {
    'base-modal': MarkModal,
    'model-form': Form,
  },
  created: function() {
    return getGroupAssessmentMarks(this.assessment.id)
      .then(function(response) {
        this.model = response.data;
      }.bind(this));
  },
  computed: {
    title: function() {
      return `Marks for assessment - ${this.assessment.name}`;
    }
  },
  methods: {
    upsert: function(form) {
      return upsertGroupAssessmentMarks(this.assessment.id, form);
    }
  },
  data: function() {
    return {
      model: []
    }
  },
  props: ['groups', 'students', 'assessment']
}
</script>