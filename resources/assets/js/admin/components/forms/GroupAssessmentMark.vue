<template>
  <form id="model-form" class="form-horizontal">
    <div class="form-group" v-for="model in form" :key="model.group_id">
      <div class="col-xs-8">
        <div class="row">
          <div class="col-xs-8">
            Marks for Group {{ model.group_id | getGroupName(groups) }}
          </div>
          <div class="col-xs-4">
            <button type="button" @click="accordions[model.group_id]=!accordions[model.group_id]" class="btn btn-sm btn-primary">
              {{ accordions[model.group_id] ? 'Hide' : 'Show' }} students
            </button>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <collapse v-model="accordions[model.group_id]">
            <ul>
              <li v-for="student in getGroupStudents(model.group_id)" :key="student.id">
                {{ student.name }} ({{ student.sid }})
              </li>
            </ul>
            </collapse>
          </div>
        </div>
      </div>
      <div class="col-xs-4">
        <input type="number" class="form-control" v-model="model.marks" min="0" step="0.001" max="100" required>
      </div>
    </div>
  </form>
</template>

<script>
import { Collapse } from "uiv";
const filter = require('lodash/filter');
const find = require("lodash/find");

export default {
  components: {
    collapse: Collapse
  },
  data: function() {
    return {
      required: true,
      accordions: this.initAccordions()
    };
  },
  methods: {
    initAccordions: function() {
      const groupIds = this.groups.map(group => group.id);
      const result = {};
      groupIds.forEach(id => (result[id] = false));
      return result;
    },
    getGroupStudents: function(groupId) {
      const students = filter(this.students, { group_id: groupId });
      return students;
    }
  },
  filters: {
    getGroupName: function(id, groups) {
      const group = find(groups, { id });
      return group ? group.name : "";
    }
  },
  created: function() {},
  props: ["form", "groups", "students"]
};
</script>