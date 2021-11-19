<template>
  <div class="nav-bar">
    <div
      class="md:container md:mx-auto h-24 flex content-center justify-between"
    >
      <span id="logoApp" class="font-extrabold">
        { } {{ $store.state.appName }}</span
      >
      <nav>
        <span>SEARCH | </span>
        <span>
          <span></span>
        </span>
        <span>CATAGORIES | </span>
        <span>SIGN IN | </span>
        <span>SIGN UP</span>
      </nav>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  setup() {
    return {};
  },
});
</script>

<style lang="scss" scoped>
#logoApp {
  font-size: 2.5vmin;
  background: linear-gradient(270deg, rgba(44, 95, 102, 0.85) 0%, #42b883 90%);
  background-clip: text;
  -webkit-background-clip: text;
  color: transparent;
}
</style>