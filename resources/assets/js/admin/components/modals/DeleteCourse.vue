<template>
  <base-modal title="Delete Course" :model="model" :delete="del" @removed="$emit('removed')" @close="$emit('close')">
    <div class="alert alert-danger" role="alert" slot="content">
      Are you sure to delete course - <b>{{model.code}} {{model.name}}</b>?<br/>
      THERE IS NO TURNING BACK!
    </div>
  </base-modal>
</template>

<script>
import DelModal from './Delete';
import { delCourse } from './../../api-client';

export default {
  components: {
    'base-modal': DelModal
  },
  methods: {
    del: delCourse
  },
  props: [
    'model'
  ]
}
</script>
