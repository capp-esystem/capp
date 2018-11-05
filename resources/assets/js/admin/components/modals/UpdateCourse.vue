<template>
  <base-modal title="Update Course" :model="model" :update="update" @updated="$emit('updated')" @close="$emit('close')">
    <model-form slot="form" :form="model"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/Course';
import UpdateModal from './Update';
import { updateCourse } from './../../api-client';

export default {
  components: {
    'base-modal': UpdateModal,
    'model-form': Form,
  },
  methods: {
    update: updateCourse
  },
  props: ['model']
}
</script>