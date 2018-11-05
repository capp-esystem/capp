<template>
  <modal v-model="open" :footer="false" :title="model.name" @hide="$emit('close')">
    <ul class="list-group">
      <li class="list-group-item">
        <h4 class="list-group-item-heading">Description</h4>
        <p class="list-group-item-text">{{model.description || 'Nil'}}</p>
      </li>
      <li class="list-group-item">
        <h4 class="list-group-item-heading">Crtieria Reference</h4>
        <p class="list-group-item-text">{{model.criterita_reference || 'Nil'}}</p>
      </li>
      <li class="list-group-item">
        <h4 class="list-group-item-heading">Response Type</h4>
        <p class="list-group-item-text">{{model.response_type}}</p>
      </li>
      <li class="list-group-item">
        <h4 class="list-group-item-heading">No of responses required</h4>
        <p class="list-group-item-text">{{model.response_no_required}}</p>
      </li>
      <li class="list-group-item">
        <h4 class="list-group-item-heading">Start At</h4>
        <p class="list-group-item-text">{{model.start_at}}</p>
      </li>
      <li class="list-group-item">
        <h4 class="list-group-item-heading">End At</h4>
        <p class="list-group-item-text">{{model.end_at}}</p>
      </li>
    </ul>
  </modal>
</template>

<script>
import { Modal } from 'uiv';

export default {
  components: {
    'modal': Modal,
  },
  data: function() {
    return {
      open: true
    };
  },
  props: [
    'model'
  ]
}
</script>
