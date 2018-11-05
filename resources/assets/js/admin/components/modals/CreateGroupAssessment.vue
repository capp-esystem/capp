<template>
  <base-modal title="Create Inter-Group Assessment" :model="model" :create="create" @created="$emit('created')" @close="$emit('close')">
    <model-form slot="form" :form="model"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/GroupAssessment';
import CreateModal from './Create';
import { createGroupAssessment } from './../../api-client';

export default {
  components: {
    'base-modal': CreateModal,
    'model-form': Form,
  },
  methods: {
    create: createGroupAssessment
  },
  data: function() {
    return {
      model: {
        name: "",
        description: "",
        criteria_reference: "",
        peer_contribution_percentage: 0,
        response_type: "questions",
        response_no_required: 0,
        start_at: undefined,
        end_at: undefined,
        course_id: this.course_id
      }
    };
  },
  props: ['course_id']
}
</script>