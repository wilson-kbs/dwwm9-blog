<template>
  <div class="header-nav-bar">
    <div
      class="md:container md:mx-auto h-24 flex content-center justify-between"
    >
      <span id="logoApp" class="font-extrabold">
        { } {{ $store.state.appName }}</span
      >
      <Nav />
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { CategoriesActionTypes } from "../store/modules/categories/action-types";
import Nav from "./header/Nav.vue";

export default defineComponent({
  components: {
    Nav,
  },
  setup() {
    return {};
  },
  async mounted() {
    // console.log(
    //   await this.$store.dispatch(CategoriesActionTypes.GET_CATEGORIES)
    // );
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