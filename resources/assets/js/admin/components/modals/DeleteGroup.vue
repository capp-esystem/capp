<template>
  <base-modal title="Delete Course" :model="model" :delete="del" @removed="$emit('removed')" @close="$emit('close')">
    <div class="alert alert-danger" role="alert" slot="content">
      Are you sure to delete group - <b>{{model.name}}</b>?<br/>
      All of the current members will have no group!
    </div>
  </base-modal>
</template>

<script>
import DelModal from './Delete';
import { delGroup } from './../../api-client';

export default {
  components: {
    'base-modal': DelModal
  },
  methods: {
    del: delGroup
  },
  props: [
    'model'
  ]
}
</script>