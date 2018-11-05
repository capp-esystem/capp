<template>
  <base-modal title="Update Group" :model="model" :update="update" @updated="$emit('updated')" @close="$emit('close')">
    <model-form slot="form" :form="model"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/Group';
import UpdateModal from './Update';
import { updateGroup } from './../../api-client';

export default {
  components: {
    'base-modal': UpdateModal,
    'model-form': Form,
  },
  methods: {
    update: updateGroup
  },
  props: ['model']
}
</script>