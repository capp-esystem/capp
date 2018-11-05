<template>
  <base-modal :title="title" :model="model" :update="update" @updated="$emit('updated')" @close="$emit('close')">
    <model-form slot="form" :form="form"></model-form>
  </base-modal>
</template>

<script>
import Form from './../forms/CriteriaGroup';
import UpdateModal from './Update';
import { updateCriterias } from './../../api-client';
const clone = require('lodash').cloneDeep;

export default {
  components: {
    'base-modal': UpdateModal,
    'model-form': Form,
  },
  methods: {
    update: function(){
      return updateCriterias(this.list);
    }
  },
  data: function(){
    return {
      original_group_name: this.model.length ? this.model[0].group_name : '',
      form: {
        group_name: this.model.length ? this.model[0].group_name : ''
      },
      list: clone(this.model)
    }
  },
  watch: {
    'form.group_name': function(name) {
      this.list = this.list.map(function(item){
        item.group_name = name;
        return item;
      });
    }
  },
  computed: {
    title: function() {
      return `Update Criteria Group ${this.original_group_name || 'Null'}`;
    }
  },
  props: ['model']
}
</script>