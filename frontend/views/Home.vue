<template>
  <div>
    <Header>
      <div class="">
        <h1 class="font-extrabold">{{ $store.state.appName }}</h1>
        <span class="px-5 text-center">Your daily chunk of code content</span>
      </div>
    </Header>
    <div class="md:mt-5 sm:mt-10">
      <PostContainer>
        <PostItem
          v-for="post in posts"
          :key="post.id"
          :title="post.title"
          :postId="post.id"
          :slug="SlugFromChar(post.title)"
          :date="'01/06/2048'"
          :img="post.imgUrl"
          :redactor="post.redactor"
          :shortContent="post.description"
        />
      </PostContainer>
    </div>
  </div>
</template>

<script lang='ts'>
import Header from "../components/Header.vue";
import { useStore } from "../store";
import { PostsActionTypes } from "../store/modules/posts/action-types";
import { SlugFromChar } from "../utils";
import PostItem from "../components/post/PostItem.vue";
import PostContainer from "../components/post/PostContainer.vue";

export default {
  layout: "home",
  components: {
    Header,
    PostItem,
    PostContainer,
  },

  async setup() {
    const posts = await useStore().dispatch(
      PostsActionTypes.GET_LAST_PUBLISHED,
      5
    );
    console.log(posts);

    return {
      posts,
      SlugFromChar,
    };
  },

  methods: {},
};
</script>

<style lang="scss" scoped>
h1 {
  font-size: 12vmin;
  color: #2c3a4b;
}
</style>