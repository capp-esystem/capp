<template>
  <div class="page-header flexbox vcenter">
      <h1 class="flex-one">{{title}}</h1>
      <button type="button" class="btn btn-primary flex-none" @click="$emit('create')">Create</button>
  </div>
</template>

<script>
export default {
    props: ['title']
}
</script>
