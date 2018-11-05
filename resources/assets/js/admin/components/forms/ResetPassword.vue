<template>
  <form id="model-form">
    <div class="form-group">
      <label>New Password</label>
      <input type="password" class="form-control" v-model="form.password" required>
    </div>
  </form>
</template>

<script>
export default {
  data: function() {
    return {
      required: true
    }
  },
  props: ['form']
}
</script>