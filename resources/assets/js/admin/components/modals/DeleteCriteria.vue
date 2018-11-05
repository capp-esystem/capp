<template>
  <base-modal title="Delete Criteria" :model="model" :delete="del" @removed="$emit('removed')" @close="$emit('close')">
    <div class="alert alert-danger" role="alert" slot="content">
      Are you sure to delete critera - <b>{{model.name}}</b>?<br/>
      All of the markings and answers under this criteria will be REMOVED!
    </div>
  </base-modal>
</template>

<script>
import DelModal from './Delete';
import { delCriteria } from './../../api-client';

export default {
  components: {
    'base-modal': DelModal
  },
  methods: {
    del: delCriteria
  },
  props: [
    'model'
  ]
}
</script>
