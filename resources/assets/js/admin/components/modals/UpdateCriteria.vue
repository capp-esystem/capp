<template>
  <base-modal title="Update Criteria" :model="model" :update="update" @updated="$emit('updated')" @close="$emit('close')">
    <model-form slot="form" :form="model"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/Criteria';
import UpdateModal from './Update';
import { updateCriteria } from './../../api-client';

export default {
  components: {
    'base-modal': UpdateModal,
    'model-form': Form,
  },
  methods: {
    update: updateCriteria
  },
  props: ['model']
}
</script>