<template>
  <span>
    {{time | format}}
  </span>
</template>

<script>
import * as moment from 'moment';

export default {
  props: ['time'],
  filters: {
    format: function(time) {
      if(!time) {
        return '';
      }
      return moment(time).format('lll');
    }
  }
}
</script>
