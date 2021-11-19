<template>
  <div class="post-preview">
    <div :title="title">
      <img :src="imageSrc" :alt="title" />
      <div>
        <div class="post-head">
          <span class="post-title">{{ title }}</span>
          <span class="post-date">{{ date }}</span>
        </div>
        <div class="post-description">{{ description }}</div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  props: {
    title: String,
    date: Number,
    description: String,
    imageSrc: String,
  },
  setup() {
    return {};
  },
});
</script>

<style scoped>
.post-preview {
  color: white;
  margin: 50px;
}
</style>