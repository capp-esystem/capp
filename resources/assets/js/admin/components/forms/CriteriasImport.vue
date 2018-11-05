<template>
  <form id="model-form" class="form-horizontal">
    <div class="form-group">
      <label class="col-xs-6">Import criterias from another intra-group assessment</label>
      <div class="col-xs-6">
        <select class="form-control" v-model="import_assessment">
          <option :value="undefined">Nil</option>
          <option v-for="assessment in assessments" :key="assessment.id" :value="assessment.id">{{ assessment.name }}</option>
        </select>
      </div>
    </div>
    <div class="panel panel-warning" v-if="form.length">
      <div class="panel-heading">Criterias to be imported</div>
      <ul class="list-group">
        <li v-for="criteria in form" :key="criteria.name" class="list-group-item">{{criteria.name}}</li>
      </ul>
    </div>
  </form>
</template>

<script>
export default {
  data: function() {
    return {
      import_assessment: undefined,
      required: true
    }
  },
  watch: {
    import_assessment: function(assessmentId) {
      this.$emit('import', assessmentId);
    }
  },
  props: ['form', 'assessments'],
}
</script>