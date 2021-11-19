<template>
  <div class="post-container md:container md:mx-auto">
    <div class="container-header">
      <h2 class="font-extrabold">Articles</h2>
    </div>
    <div class="container-content grid grid-cols-2">
      <slot></slot>
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

<style lang='scss' scoped>
.post-container {
  padding: 5rem 0;
}
.container-header {
  margin-bottom: 5rem;
  display: flex;
  h2 {
    flex: 1;
    text-align: center;
    font-size: 7vmin;
    color: rgb(201, 201, 201);
    // text-decoration: underline;
    text-decoration-thickness: 0.2vmin;
  }
}

.container-content {
  & > * {
    border-color: white;
  }
  & > *:nth-child(2n) {
    border-top-width: 2px;
  }
}
</style>

<style lang="scss">
.post-container .container-content > * {
  border-color: rgba(255, 255, 255, 0.645);
  padding: 2rem 2rem;
  &:nth-child(even) {
    border-top-width: 1px;
    padding-right: 0;
  }

  &:nth-child(odd) {
    border-top-width: 1px;
    border-right-width: 1px;
    padding-left: 0;
  }

  &:nth-child(1),
  &:nth-child(2) {
    border-top-width: 0px;
    padding-top: 1rem;
  }

  &:nth-last-child(1):not(:nth-child(even)) {
    border-top-width: 0;
  }

  &:nth-last-child(2):not(:nth-child(odd)),
  &:nth-last-child(3):not(:nth-child(even)) {
    border-bottom-width: 1px;
  }
}
</style>