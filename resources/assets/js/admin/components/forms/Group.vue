<template>
  <form id="model-form">
    <div class="form-group">
      <label>Name *</label>
      <input type="text" class="form-control" v-model="form.name" required>
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