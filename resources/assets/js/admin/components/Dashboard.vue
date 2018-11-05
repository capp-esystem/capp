<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-2">
        <side-nav></side-nav>
      </div>
      <div class="col-lg-10">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script>
import SideNav from './shared/SideNav.vue';

export default {
  components: {
    'side-nav': SideNav
  },
  
}
</script>
