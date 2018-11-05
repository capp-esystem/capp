<template>
  <form id="model-form">
    <div class="form-group">
      <label>Main Criteria</label>
      <input type="text" class="form-control" v-model="form.group_name" />
    </div>
    <div class="form-group" v-for="(name, index) in form.names" :key="index">
      <label>Sub Criteria *</label>
      <div class="flexbox">
        <div class="flex-one">
          <input type="text" class="form-control" v-model="form.names[index]" required />
        </div>
        <div class="flex-none">
          <button type="button" class="btn btn-danger" @click="delName(index)">
            <span aria-hidden="true" class="glyphicon glyphicon-minus"></span>
          </button>
        </div>
      </div>
    </div>
    <button type="button" class="btn btn-sm btn-primary" @click="pushName">Create Sub Criteria</button>
  </form>
</template>

<script>
export default {
  props: ['form'],
  data: function() {
    return {
      required: true
    }
  },
  methods: {
    pushName: function() {
      this.form.names.push('');
    },
    delName: function(index) {
      this.form.names.splice(index, 1);
    }
  }
}
</script>