<template>
  <base-modal title="Create Group" :model="model" :create="create" @created="$emit('created')" @close="$emit('close')">
    <model-form slot="form" :form="model"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/Group';
import CreateModal from './Create';
import { createGroup } from './../../api-client';

export default {
  components: {
    'base-modal': CreateModal,
    'model-form': Form,
  },
  methods: {
    create: createGroup
  },
  data: function() {
    return {
      model: {
        name: "",
        course_id: this.course_id
      }
    };
  },
  props: ['course_id']
}
</script>