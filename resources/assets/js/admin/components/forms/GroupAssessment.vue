<template>
  <form id="model-form">
    <div class="form-group">
      <label>Name *</label>
      <input type="text" class="form-control" v-model="form.name" required>
    </div>
    <div class="form-group">
      <label>Description</label>
      <textarea class="form-control" v-model="form.description" rows="5"></textarea>
    </div>
    <div class="form-group">
      <label>Criteria Reference</label>
      <textarea class="form-control" v-model="form.criteria_reference" rows="5"></textarea>
    </div>
    <div class="form-group">
      <label>Weighting of peers’ group mark to overall group mark</label>
      <div class="input-group">
        <input type="integer" class="form-control" min="0" max="100" step="1" v-model="form.peer_contribution_percentage" required/>
        <div class="input-group-addon">% of peers’ group mark will contribute to overall group mark</div>
      </div>
      <div class="input-group">
        <input type="integer" class="form-control" min="0" max="100" step="1" :value="100 - form.peer_contribution_percentage" :disabled="true" />
        <div class="input-group-addon">% of tutor’s group mark will contribute to overall group mark</div>
      </div>
    </div>
    <div class="form-group">
      <label>Response Type *</label>
      <select class="form-control" v-model="form.response_type" required>
        <option value="questions">Questions</option>
        <option value="comments">Comments</option>
      </select>
    </div>
    <div class="form-group">
      <label>Response No Required *</label>
      <input type="number" min="0" max="2" step="1" class="form-control" v-model="form.response_no_required" required>
    </div>
    <div class="form-group">
      <label>Start At *</label>
      <input type="datetime-local" class="form-control" v-model="form.start_at" required/>
    </div>
    <div class="form-group">
      <label>End At *</label>
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