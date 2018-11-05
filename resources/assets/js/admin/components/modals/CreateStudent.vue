<template>
  <base-modal title="Create Student" :model="model" :create="create" @created="$emit('created')" @close="$emit('close')">
    <model-form slot="form" :form="model" :groups="groups"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/Student';
import CreateModal from './Create';
import { createStudent, createCourseStudent } from './../../api-client';

export default {
  components: {
    'base-modal': CreateModal,
    'model-form': Form,
  },
  methods: {
    create: function(student) {
      return createStudent(student)
        .then(function(response) {
          student.user_id = response.data.id;
          return createCourseStudent(student);
        });
    }
  },
  data: function() {
    return {
      model: {
        name: "",
        sid: "",
        email: "",
        course_id: this.course_id,
        group_id: ""
      },
    };
  },
  props: ['course_id', 'groups']
}
</script>