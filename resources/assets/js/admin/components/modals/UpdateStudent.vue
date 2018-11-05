<template>
  <base-modal title="Update Student" :model="model" :update="update" @updated="$emit('updated')" @close="$emit('close')">
    <model-form slot="form" :form="model" :groups="groups"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/Student';
import UpdateModal from './Update';
import { updateStudent, updateCourseStudent } from './../../api-client';

export default {
  components: {
    'base-modal': UpdateModal,
    'model-form': Form,
  },
  methods: {
    update: function(student) {
      return updateStudent(student)
        .then(function(response) {
          return updateCourseStudent(student);
        })
    }
  },
  props: ['model', 'groups']
}
</script>