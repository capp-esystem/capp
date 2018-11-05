<template>
  <base-modal title="Update Intra-Group Assessment" :model="model" :update="update" @updated="$emit('updated')" @close="$emit('close')">
    <model-form slot="form" :form="model"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/IntraGroupAssessment';
import UpdateModal from './Update';
import { updateIntraGroupAssessment } from './../../api-client';

export default {
  components: {
    'base-modal': UpdateModal,
    'model-form': Form,
  },
  methods: {
    update: updateIntraGroupAssessment
  },
  props: ['model']
}
</script>