<template>
  <base-modal title="Delete Intra-Group Assessment" :model="model" :delete="del" @removed="$emit('removed')" @close="$emit('close')">
    <div class="alert alert-danger" role="alert" slot="content">
      Are you sure to delete intra-group assessment - <b>{{model.name}}</b>?<br/>
      All of the answers and markings will be deleted.
    </div>
  </base-modal>
</template>

<script>
import DelModal from './Delete';
import { delIntraGroupAssessment } from './../../api-client';

export default {
  components: {
    'base-modal': DelModal
  },
  methods: {
    del: delIntraGroupAssessment
  },
  props: [
    'model'
  ]
}
</script>