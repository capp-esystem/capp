<template>
  <form id="model-form" class="form-horizontal">
    <div class="form-group">
      <label class="col-xs-6">Import from inter-group assessment</label>
      <div class="col-xs-6">
        <select class="form-control" v-model="import_assessment">
          <option :value="undefined">Nil</option>
          <option v-for="assessment in group_assessments" :key="assessment.id" :value="assessment.id">{{ assessment.name }}</option>
        </select>
      </div>
    </div>
    <div class="form-group" v-for="model in form" :key="model.group_id">
      <label class="col-xs-8">Marks for Group {{ model.group_id | getGroupName(groups) }}</label>
      <div class="col-xs-4">
        <input type="number" class="form-control" v-model="model.marks" min="0" step="0.001" max="100" required>
      </div>
    </div>
  </form>
</template>

<script>
const find = require('lodash').find;

export default {
  data: function() {
    return {
      import_assessment: undefined,
      required: true
    }
  },
  watch: {
    import_assessment: function (assessmentId) {
      if(assessmentId) {
        this.$emit('import', assessmentId);
      }
    }
  },
  filters: {
    getGroupName: function(id, groups) {
      const group = find(groups, {id});
      if(group) {
        return group.name;
      } else {
        return '';
      }
    }
  },
  props: ['form', 'groups', 'group_assessments'],  
}
</script>