<template>
  <form id="model-form">
    <div class="form-group">
      <label>Code *</label>
      <input type="text" class="form-control" v-model="form.code" required>
    </div>
    <div class="form-group">
      <label>Name *</label>
      <input type="text" class="form-control" v-model="form.name" required>
    </div>
    <div class="form-group">
      <label>Start Date *</label>
      <input type="datetime-local" class="form-control" v-model="form.start_at" required/>
    </div>
    <div class="form-group">
      <label>End Date *</label>
      <input type="datetime-local" class="form-control" v-model="form.end_at" required/>
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