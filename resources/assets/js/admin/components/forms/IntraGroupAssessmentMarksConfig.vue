<template>
  <form id="model-form">
    <div class="form-group">
      <label>Method of calculating PA score *</label>
      <div class="radio">
        <label><input type="radio" value="default" v-model="form.pa_score_adjust_method" />Use default pre-calculated PA score</label>
      </div>
      <div class="bg-info p10">
        <p>
          Pre‐calculated PA score is an average peer and self‐assessment score calculated by the system.
        </p>
        <p>
          The precalculated PA score is to calculate the individual mark based on the average individual score of the group that the individual belongs to. (See below equation.)
        </p>
        <p>
          <var>Pre‐calculated PA score</var> = <var>Total score of an individual obtained from peer and self-assessments</var> / <var>Average score of the group that the individual belongs to</var>
        </p>
      </div>
      <div class="radio">
        <label><input type="radio" value="power" v-model="form.pa_score_adjust_method" />Adjust PA score by returning (PA score to the power of <input type="number" min="0" max="10" v-model.number="power_value" :required="form.pa_score_adjust_method == 'power'" /> )</label>
      </div>
      <div class="radio">
        <label><input type="radio" value="percentage" v-model="form.pa_score_adjust_method" />Adjust PA score by returning (PA score multiples by <input type="number" min="0" max="100" v-model.number="percentage_value" :required="form.pa_score_adjust_method == 'percentage'" /> %)</label>
      </div>
      <div class="bg-info p10">
        <p>
          Adjusted PA score is to adjust the pre‐calculated PA score by applying a factor to reduce / increase the individual score. (See equation below.) You can apply a percentage or square root (^2) to the pre‐calculated PA score.
        </p>
        <p>
          <var>Adjusted PA score</var> = <var>PA score</var> / <var>tutor’s defined factor</var>
        </p>
      </div>
    </div>
    <div class="form-group">
      <label>Max Individual Mark</label>
      <input type="number" class="form-control" v-model.number="form.max_cap" />
    </div>
    <div class="form-group">
      <label>Penalty for students who don't complete (Individual Mark * (1 - n %))</label>
      <input type="number" class="form-control" v-model.number="form.penalty" min="0" max="100" />
    </div>
    <div class="bg-info p10">
      <p>
        This is to set a penalty by applying a percentage to reduce the individual mark when s/he did not complete the peer and/or self‐assessment. If this is chosen, the system will automatically apply the penalty percentage to the student concerned according to the following equation to calculate a penalised individual score.
      </p>
      <p>
        <var>Penalised individual score</var> = <var>Penalty percentage</var> x <var>Number of non-assessed students by an individual</var> / <var>Number of students in the group that the individual belongs to</var>
      </p>
    </div>
    <div class="form-group mt10">
      <button type="button" class="btn btn-primary" @click="submitForm" :disabled="loading">Set Config</button>
    </div>
  </form>
</template>

<script>
import { setIntraGroupAssessmentMarksConfig } from './../../api-client';

export default {
  props: ['form'],
  data: function() {
    return {
      loading: false,
      power_value: undefined,
      percentage_value: undefined,
      required: true
    };
  },
  created: function() {
    this.initValue();
  },
  watch: {
    'form.pa_score_adjust_method': function() {
      this.power_value = undefined;
      this.percentage_value = undefined;
    },
    power_value: function(val) {
      this.updateVal(val);
    },
    percentage_value: function(val) {
      this.updateVal(val);
    }
  },
  methods: {
    initValue: function() {
      this.power_value = undefined;
      this.percentage_value = undefined;
      switch (this.form.pa_score_adjust_method) {
        case 'power':
          this.power_value = this.form.pa_score_adjust_value;
          break;
        case 'percentage':
          this.percentage_value = this.form.pa_score_adjust_value;
          break;
      }
    },
    updateVal: function(newVal) {
      this.$emit('updateVal', newVal);
    },
    submitForm: function() {
      if (!document.querySelector('#model-form').reportValidity()) {
        return;
      }
      this.loading = true;
      return setIntraGroupAssessmentMarksConfig(this.form)
        .then(
          function(response) {
            this.loading = false;
            this.$emit('set');
          }.bind(this)
        )
        .catch(
          function(error) {
            this.loading = false;
            console.error(error);
            this.$emit('err');
          }.bind(this)
        );
    }
  }
};
</script>