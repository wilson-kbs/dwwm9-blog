<template>
  <header :class="[headerSize]">
    <Wave
      :contentColor="'linear-gradient(180deg, rgba(44, 95, 102, 0.85) 10%, #42b883 90%)'"
      color="#42b883"
      :position="'top'"
      :heightWave="50"
      :amplitude="65"
      :bones="3"
    >
      <NavBar />
      <div class="header-content flex">
        <div class="m-auto">
          <slot></slot>
        </div>
      </div>
    </Wave>
  </header>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import NavBar from "./HeaderNavBar.vue";
import Wave from "./Wave.vue";

export default defineComponent({
  components: {
    NavBar,
    Wave,
  },
  props: {
    size: {
      type: String,
      required: false,
    },
  },
  computed: {
    headerSize() {
      switch (this.size?.toLowerCase()) {
        case "xl":
          return "xl";
        case "s":
          return "small";
        case "xxl":
          return "xxl";
        case "m":
        default:
          return "medium";
      }
    },
  },
  setup() {
    return {};
  },
});
</script>

<style lang="scss" scoped>
header.xl {
  & .header-content {
    min-height: 50vh;
  }
}
header.medium {
  & .header-content {
    min-height: 40vh;
  }
}
header.small {
  & .header-content {
    min-height: 25vh;
  }
}
header.xxl {
  & .header-content {
    min-height: 70vh;
  }
}
</style>