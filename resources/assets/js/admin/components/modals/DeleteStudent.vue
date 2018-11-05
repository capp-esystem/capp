<template>
  <base-modal title="Delete Course" :model="model" :delete="del" @removed="$emit('removed')" @close="$emit('close')">
    <div class="alert alert-danger" role="alert" slot="content">
      Are you sure to remove student - <b>{{model.name}}</b> from this course?
    </div>
  </base-modal>
</template>

<script>
import DelModal from './Delete';
import { delCourseStudent } from './../../api-client';

export default {
  components: {
    'base-modal': DelModal
  },
  methods: {
    del: delCourseStudent
  },
  props: [
    'model'
  ]
}
</script>