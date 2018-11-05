<template>
  <modal v-model="open" :title="title" @hide="$emit('close')">
    <alert type="danger" :closable="true" v-if="err_msg" @close="err_msg=undefined">
      {{err_msg}}
    </alert>
    <slot name="form"></slot>
    <div slot="footer">
      <button type="button" class="btn btn-primary" @click="submit" :disabled="loading">{{btn_text}}</button>
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
      if (!document.querySelector('#model-form').reportValidity()) {
        this.err_msg = 'Please check your form to make sure everything is correct';
        return;
      }
      this.loading = true;
      this.create(this.model)
        .then(function(response) {
          this.open = false;
          this.$emit('created', response.data);
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
  props: {
    'title': String,
    'model': [Object, Array],
    'create': Function,
    'btn_text': {
      type: String,
      default: 'Create'
    }
  }
}
</script>
