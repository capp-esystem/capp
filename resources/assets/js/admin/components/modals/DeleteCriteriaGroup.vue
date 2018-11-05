<template>
  <base-modal title="Delete Criteria Group" :model="model" :delete="del" @removed="$emit('removed')" @close="$emit('close')">
    <div class="alert alert-danger" role="alert" slot="content">
      Are you sure to delete criteria group - <b>{{model[0].group_name}}</b>?<br/>
      All of the criterias under this group will be REMOVED!
    </div>
  </base-modal>
</template>

<script>
import DelModal from './Delete';
import { delCriterias } from './../../api-client';

export default {
  components: {
    'base-modal': DelModal
  },
  methods: {
    del: delCriterias
  },
  props: [
    'model'
  ]
}
</script>