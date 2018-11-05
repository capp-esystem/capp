<template>
  <base-modal :title="`Reset Password for ${model.name}`" :model="model" :update="update" @updated="$emit('updated')" @close="$emit('close')">
    <model-form slot="form" :form="model"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/ResetPassword';
import UpdateModal from './Update';
import { updateStudentPassword } from './../../api-client';

export default {
  components: {
    'base-modal': UpdateModal,
    'model-form': Form,
  },
  methods: {
    update: updateStudentPassword
  },
  props: ['model']
}
</script>