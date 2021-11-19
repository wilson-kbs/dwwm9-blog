<template>
  <div>
    <PostFullItem v-if="ok && post" :post="post" />
    <div v-else>404</div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import { FullPost } from "../interfaces";
import { useStore } from "../store";
import { PostsActionTypes } from "../store/modules/posts/action-types";
import { SlugFromChar } from "../utils";

import PostFullItem from "../components/post/PostFullItem.vue";

SlugFromChar;

export default defineComponent({
  components: { PostFullItem },
  async setup() {
    const store = useStore();
    const route = useRoute();
    const router = useRouter();
    const post = ref<FullPost>();

    const ok = ref(false);

    function getPostId(): number | undefined {
      if (Array.isArray(route.params.postId)) return undefined;

      const postId = parseInt(route.params.postId);
      if (isNaN(postId)) return undefined;

      return postId;
    }
    async function getPost(id: number) {
      const post = await store.dispatch(PostsActionTypes.FIND_ONE, id);
      if (post) {
        const newRoute = `/article/${route.params.postId}/${SlugFromChar(
          post.title
        )}`;
        if (route.fullPath != newRoute) router.replace(newRoute);
      }
      return post;
    }

    async function setPost() {
      try {
        const postId = getPostId();
        if (!postId) throw new Error("invalide Id");
        const postItem = await getPost(postId);
        console.log(postItem);

        post.value = postItem;
        ok.value = true;
      } catch (error) {
        post.value = undefined;
        ok.value = false;
      }
    }

    watch(
      () => route.params.id,
      async () => {
        setPost();
      }
    );
    await setPost();
    return {
      post,
      ok,
    };
  },
  methods: {
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
});
</script>

<style scoped>
</style>