<template>
  <base-modal title="Create Criterias" :model="model" :create="create" @created="$emit('created')" @close="$emit('close')">
    <model-form slot="form" :form="model"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/Criterias';
import CreateModal from './Create';
import { createCriterias } from './../../api-client';

export default {
  components: {
    'base-modal': CreateModal,
    'model-form': Form,
  },
  methods: {
    create: function(data) {
      const criterias = data.names.filter(name => name && name.trim().length > 0).map(name => ({
        asg_id: data.asg_id,
        group_name: data.group_name.trim(),
        name: name.trim()
      }));
      return createCriterias(criterias);
    }
  },
  data: function() {
    return {
      model: {
        asg_id: this.assessment_id,
        group_name: '',
        names: ['']
      }
    };
  },
  props: ['assessment_id']
}
</script>
