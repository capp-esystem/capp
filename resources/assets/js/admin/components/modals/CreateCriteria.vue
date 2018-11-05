<template>
  <base-modal title="Create Criteria" :model="model" :create="create" @created="$emit('created')" @close="$emit('close')">
    <model-form slot="form" :form="model"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/Criteria';
import CreateModal from './Create';
import { createCriteria } from './../../api-client';

export default {
  components: {
    'base-modal': CreateModal,
    'model-form': Form,
  },
  methods: {
    create: createCriteria
  },
  data: function() {
    return {
      model: {
        asg_id: this.assessment_id,
        name: '',
        group_name: ''
      }
    };
  },
  props: ['assessment_id']
}
</script>
