<template>
  <form id="model-form">
    <div class="form-group">
      <label>Method of moderating marks</label>
      <select v-model="form.moderate_method" class="form-control">
        <option value="high_only">Method 1 - Set upper limit to score</option>
        <option value="high_low">Method 2 - Set upper and lower limits to score</option>
      </select>
    </div>
    <div class="bg-info p10">
      <h4>
        Set upper limit to score
      </h4>
      <p>
        Set an upper limit to the peers’ group mark. (See the equation below.)<br/>
        The upper limit score can be set as 100 or highest tutor’s group mark or other score set by the tutor.
      </p>
      <p>
        <var>Peer’s group mark</var> = <var>Upper limit score</var> x <var>peer score to the group</var> / <var>highest peer score</var>
      </p>
    </div>
    <div class="bg-info p10">
      <h4>
        Set upper and lower limits to score
      </h4>
      <p>
        Set the peers’ group mark by upper and lower limits. (See the equation below.)<br/>
        The upper limit score can be set as 100 or highest tutor’s group mark or other score set by the tutor. Lower limit score can be set as the lowest tutor’s group mark or other score set by the tutor.
      </p>
      <p>
        <var>Peer’s group mark</var> = <var>lower limit score</var> + <var>range of upper limit and lower limit</var> x (<var>peer score to the group - lowest peer score</var>) / <var>peer score range</var>
      </p>
    </div>
    <div class="form-group">
      <label>Upper Limit (default is the highest mark you have given)</label>
      <input type="number" class="form-control" v-model.number="form.upper_limit" max="100" required />
    </div>
    <div class="form-group" v-if="form.moderate_method == 'high_low'">
      <label>Lower Limit (default is the lowest mark you have given)</label>
      <input type="number" class="form-control" v-model.number="form.lower_limit" min="0" required />
    </div>
    <div class="form-group">
      <label>Penalty for students who don't complete (Individual Mark * (1 - n %))</label>
      <input type="number" class="form-control" v-model.number="form.penalty" min="0" max="100" required />
    </div>
    <div class="bg-info p10">
      <p>
        This is to set a penalty by applying a percentage to reduce an individual’s group mark when student fails to complete the peer assessment. If this is chosen, the system will automatically apply the penalty percentage to the student concerned according to the following equation to calculate a penalised peers’ group mark to the group mark.
      </p>
      <p>
        <var>Penalised peers' group score</var> = <var>penalty percentage</var> x <var>(number of non-assessed groups - 1)</var> / <var>(total number of groups to be assessed - 1)</var>
      </p>
    </div>
    <div class="form-group mt10">
      <button type="button" class="btn btn-primary" @click="submitForm" :disabled="loading">save</button>
    </div>
  </form>
</template>

<script>
import { setGroupAssessmentMarksConfig } from './../../api-client';

export default {
  props: ['form'],
  data: function() {
    return {
      loading: false
    };
  },
  watch: {},
  methods: {
    submitForm: function() {
      return setGroupAssessmentMarksConfig(this.form)
        .then(
          function(response) {
            this.$emit('set');
          }.bind(this)
        )
        .catch(
          function(error) {
            console.error(error);
            this.$emit('err');
          }.bind(this)
        );
    }
  }
};
</script>