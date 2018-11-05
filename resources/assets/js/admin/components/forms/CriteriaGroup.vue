<template>
  <form id="model-form">
    <div class="form-group">
      <label>New Group Name</label>
      <input type="text" class="form-control" v-model="form.group_name">
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