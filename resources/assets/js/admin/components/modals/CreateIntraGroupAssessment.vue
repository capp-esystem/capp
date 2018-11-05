<template>
  <base-modal title="Create Intra-Group Assessment" :model="model" :create="create" @created="$emit('created')" @close="$emit('close')">
    <model-form slot="form" :form="model"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/IntraGroupAssessment';
import CreateModal from './Create';
import { createIntraGroupAssessment } from './../../api-client';

export default {
  components: {
    'base-modal': CreateModal,
    'model-form': Form,
  },
  methods: {
    create: createIntraGroupAssessment
  },
  data: function() {
    return {
      model: {
        name: "",
        description: "",
        start_at: undefined,
        end_at: undefined,
        course_id: this.course_id
      }
    };
  },
  props: ['course_id']
}
</script>