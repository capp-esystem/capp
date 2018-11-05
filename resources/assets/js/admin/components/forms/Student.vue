<template>
  <form id="model-form">
    <div class="form-group">
      <label>Name *</label>
      <input type="text" class="form-control" v-model="form.name" required>
    </div>
    <div class="form-group">
      <label>Email *</label>
      <input type="email" class="form-control" v-model="form.email" required>
    </div><div class="form-group">
      <label>SID *</label>
      <input type="text" class="form-control" v-model="form.sid" required>
    </div>
    <div class="form-group">
      <label>Group *</label>
      <select class="form-control" v-model="form.group_id">
        <option value="">Nil</option>
        <option v-for="group in groups" :key="group.id" :value="group.id">{{group.name}}</option>
      </select>
    </div>
  </form>
</template>

<script>
export default {
  props: ['form', 'groups'],
  data: function() {
    return {
      required: true
    }
  }
}
</script>