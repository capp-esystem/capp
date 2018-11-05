<template>
  <form id="model-form">
    <div class="form-group">
      <label>Main Criteria</label>
      <input type="text" class="form-control" v-model="form.group_name" />
    </div>
    <div class="form-group">
      <label>Sub Criteria *</label>
      <input type="text" class="form-control" v-model="form.name" required />
    </div>
  </form>
</template>

<script>
export default {
  props: ['form'],
  data: function() {
    return {
      required: true
    }
  }
}
</script>