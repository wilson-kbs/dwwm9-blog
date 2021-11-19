<template>
  <div class="full-post-container md:container mx-auto">
    <div class="header-post">
      <h1 class="font-extrabold underline">{{ post.title }}</h1>
      <p class="post-decription text-center">{{ post.description }}</p>
    </div>
    <div class="post-image">
      <img :src="post.imgUrl" :alt="post.title" />
    </div>
    <div class="post-info">
      <span
        >Publier par
        <router-link
          :to="`/redacteur/${post.redactor.id}/${SlugFromChar(
            `${post.redactor.firstname} ${post.redactor.lastname}`
          )}`"
        >
          {{ post.redactor.firstname }}
          {{ post.redactor.lastname }}
        </router-link>
      </span>
      <span> le {{ getTimeStringToString(post.publishedAt) }}</span>
    </div>
    <div class="post-content"></div>
    <div class="post-redactor flex">
      <div class="redactor-img">
        <img
          :src="post.redactor.img"
          :alt="`${post.redactor.firstname} ${post.redactor.lastname}`"
        />
      </div>
      <div class="redactor-info flex flex-col">
        <div class="redactor-name">
          <router-link
            :to="`/redacteur/${post.redactor.id}/${SlugFromChar(
              `${post.redactor.firstname} ${post.redactor.lastname}`
            )}`"
          >
            <h2>
              {{ post.redactor.firstname }}
              {{ post.redactor.lastname }}
            </h2>
          </router-link>
        </div>
        <p class="redactor-bio">{{ post.redactor.bio }}</p>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Quill, { QuillOptionsStatic } from "quill";
import { defineComponent, PropType } from "vue";
import { FullPost } from "../../interfaces";
import { SlugFromChar } from "../../utils";

export default defineComponent({
  props: {
    post: {
      type: Object as PropType<FullPost>,
      required: true,
    },
  },
  setup() {
    return {};
  },
  data() {
    return {
      quillInstance: null as Quill | null,
    };
  },
  methods: {
    SlugFromChar,
    getTimeStringToString(time: string) {
      const date = new Date(time);
      return `${date.getDay()}.${date.getMonth()}.${date
        .getUTCFullYear()
        .toString()
        .split("")
        .slice(2)
        .join("")}`;
    },
  },
  mounted() {
    const option: QuillOptionsStatic = {
      readOnly: true,
      theme: "bubble",
    };
    this.quillInstance = new Quill(".post-content", option);
    this.quillInstance.setContents(JSON.parse(this.post.content));
  },
});
</script>

<style lang="scss" scoped>
.post-redactor {
  // height: 100px;
  // overflow: hidden;
  .redactor-img img {
    height: 20rem;
    width: 20rem;
    border-radius: 50%;
    object-fit: cover;
  }
}
</style>

<style>
.full-post-container {
  color: white;
}
</style>