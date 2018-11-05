<template>
  <base-modal title="Update Inter-Group Assessment" :model="model" :update="update" @updated="$emit('updated')" @close="$emit('close')">
    <model-form slot="form" :form="model"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/GroupAssessment';
import UpdateModal from './Update';
import { updateGroupAssessment } from './../../api-client';

export default {
  components: {
    'base-modal': UpdateModal,
    'model-form': Form,
  },
  methods: {
    update: updateGroupAssessment
  },
  props: ['model']
}
</script>