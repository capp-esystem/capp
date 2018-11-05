<template>
  <base-modal title="Create Course" :model="model" :create="create" @created="$emit('created')" @close="$emit('close')">
    <model-form slot="form" :form="model"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/Course';
import CreateModal from './Create';
import { createCourse } from './../../api-client';

export default {
  components: {
    'base-modal': CreateModal,
    'model-form': Form,
  },
  methods: {
    create: createCourse
  },
  data: function() {
    return {
      model: {
        code: "",
        name: "",
        start_at: undefined,
        end_at: undefined
      }
    };
  }
}
</script>
