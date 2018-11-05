<template>
  <modal v-model="open" title="Batch Add students" @hide="$emit('close')">
    <p>Please upload *.csv file with the following format</p>
    <pre>[SID][Name][Email][Group Name]</pre>
    <p>The first row is acted as header and will be ignored</p>
    <p>You can download the template file <a href="./sample.csv" download>here</a></p>
    <div class="form-group">
      <label for="message" class="control-label">Student Information File</label>
      <input type="file" class="form-control ml10" @change="updateFile" accept=".csv" />
    </div>
    <pre v-show="status.length">{{ status | join }}</pre>
    <div slot="footer">
      <button type="button" class="btn btn-primary" @click="submit" :disabled="loading">Add</button>
    </div>
  </modal>
</template>

<script>
import { Modal, Alert } from 'uiv';
import { createStudents, createGroups, createCourseStudents } from './../../api-client';

export default {
  components: {
    modal: Modal,
    alert: Alert
  },
  methods: {
    importStudents: function(courseId, file) {
      this.status.push(`Importing students`);
      return createStudents(courseId, file)
        .then(
          function(response) {
            const size = response.data.length;
            this.status.push(`Imported ${size} students`);
          }.bind(this)
        )
        .catch(
          function(error) {
            throw `Import students error`;
          }.bind(this)
        );
    },
    importGroups: function(courseId, file) {
      this.status.push(`Importing groups`);
      return createGroups(courseId, file)
        .then(
          function(response) {
            const size = response.data.length;
            this.status.push(`Imported ${size} groups`);
          }.bind(this)
        )
        .catch(
          function(error) {
            throw `Import groups error`;
          }.bind(this)
        );
    },
    importRelations: function(courseId, file) {
      this.status.push(`Assigning relations`);
      return createCourseStudents(courseId, file)
        .then(
          function(response) {
            const size = response.data.length;
            this.status.push(`Assigned ${size} relations`);
          }.bind(this)
        )
        .catch(
          function(error) {
            throw `Assign relations error`;
          }.bind(this)
        );
    },
    submit: function() {
      this.status = [];
      this.loading = true;
      this.importStudents(this.course_id, this.file)
        .then(
          function() {
            return this.importGroups(this.course_id, this.file);
          }.bind(this)
        )
        .then(
          function() {
            return this.importRelations(this.course_id, this.file);
          }.bind(this)
        )
        .then(
          function(response) {
            this.loading = false;
            this.status.push('Import completed');
            this.$emit('created');
          }.bind(this)
        )
        .catch(
          function(error) {
            this.open = false;
            this.loading = false;
            this.status.push(error);
          }.bind(this)
        );
    },
    updateFile: function(event) {
      this.file = event.target.files[0];
    }
  },
  data: function() {
    return {
      open: true,
      status: [],
      loading: false,
      file: undefined
    };
  },
  filters: {
    join: function(arr) {
      return arr.join('\n');
    }
  },
  props: ['course_id']
};
</script>
