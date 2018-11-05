<template>
  <modal v-model="open" :title="title" @hide="$emit('close')">
    <alert type="danger" :closable="true" v-if="err_msg" @close="err_msg=undefined">
      {{err_msg}}
    </alert>
    <slot name="content"></slot>
    <div slot="footer">
      <button type="button" class="btn btn-success" @click="open = false">No</button>
      <button type="button" class="btn btn-danger" @click="submit" :disabled="loading">Yes</button>
    </div>
  </modal>
</template>

<script>
import { Modal, Alert } from 'uiv';

export default {
  components: {
    'modal': Modal,
    'alert': Alert,
  },
  methods: {
    submit: function() {
      this.loading = true;
      this.delete(this.model)
        .then(function(response) {
          this.open = false;
          this.$emit('removed', response.data);
        }.bind(this))
        .catch(function(error) {
          this.loading = false;
          this.err_msg = 'Oops! Something is wrong with the server!';
        }.bind(this));
    }
  },
  data: function() {
    return {
      open: true,
      err_msg: undefined,
      loading: false
    };
  },
  props: [
    'title',
    'model',
    'delete'
  ]
}
</script>
