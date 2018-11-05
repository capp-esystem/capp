<template>
  <base-modal title="Delete Inter-Group Assessment" :model="model" :delete="del" @removed="$emit('removed')" @close="$emit('close')">
    <div class="alert alert-danger" role="alert" slot="content">
      Are you sure to delete inter-group assessment - <b>{{model.name}}</b>?<br/>
      All of the answers and markings will be deleted.
    </div>
  </base-modal>
</template>

<script>
import DelModal from './Delete';
import { delGroupAssessment } from './../../api-client';

export default {
  components: {
    'base-modal': DelModal
  },
  methods: {
    del: delGroupAssessment
  },
  props: [
    'model'
  ]
}
</script>