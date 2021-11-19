<template>
  <div>
    <Header :size="'xxl'">
      <div class="">
        <h1 class="font-extrabold underline">
          {{ ok && category ? category.name : 404 }}
        </h1>
      </div>
    </Header>
    <div class="md:mt-5 sm:mt-10">
      <PostContainer>
        <PostItem
          v-for="index in 15"
          :key="index"
          :title="
            'Appliquer la technologie 4.0 pour promouvoir la valeur du Temple de la Littérature #' +
            (index + 1)
          "
          :postId="index + 1"
          :slug="'article' + (index + 1)"
          :date="'01/06/2048'"
          :img="'https://www.ionos.fr/digitalguide/fileadmin/DigitalGuide/Teaser/code-editoren-t.jpg'"
          :redactor="redactor"
          :shortContent="'BLA BLA BLA BLA BLA BLA BLA BLA'"
        />
      </PostContainer>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import { Category, FullPost, Redactor } from "../interfaces";
import { useStore } from "../store";
import { CategoriesActionTypes } from "../store/modules/categories/action-types";
import { PostsActionTypes } from "../store/modules/posts/action-types";
import Header from "../components/Header.vue";
import PostItem from "../components/post/PostItem.vue";
import PostContainer from "../components/post/PostContainer.vue";

export default defineComponent({
  layout: "category-post",
  components: {
    Header,
    PostItem,
    PostContainer,
  },
  async setup() {
    const store = useStore();
    const route = useRoute();
    const router = useRouter();
    const category = ref<Category>();
    const ok = ref<boolean>(false);
    const posts = ref();

    function getCategoryId(): number | undefined {
      if (Array.isArray(route.params.categoryId)) return undefined;

      const categoryId = parseInt(route.params.categoryId);
      if (isNaN(categoryId)) return undefined;

      return categoryId;
    }

    async function getCategory(id: number) {
      const category = await store.dispatch(
        CategoriesActionTypes.GET_CATEGORY,
        id
      );
      const newRoute = `/category/${route.params.categoryId}/${category.slug}`;
      if (route.fullPath != newRoute) router.replace(newRoute);
      return category;
    }

    try {
      const categoryId = getCategoryId();
      if (!categoryId) throw new Error("invalide Id");
      const categoryItem = await getCategory(categoryId);

      console.log(categoryItem);

      category.value = categoryItem;
      ok.value = true;

      watch(
        () => route.params.id,
        async () => {
          const categoryId = getCategoryId();
          if (!categoryId) {
            category.value = undefined;
            ok.value = false;
            return;
          }
          const categoryItem = await getCategory(categoryId);
          if (!categoryItem) {
            category.value = null;
            ok.value = false;
            return;
          }
          category.value = categoryItem;
          ok.value = true;
        }
      );
    } catch (error) {
      ok.value = false;
    }
    return {
      category,
      ok,
    };
  },
  data() {
    return {
      redactor: {
        id: 1,
        firstname: "The",
        lastname: "Putcher",
        bio: "Je suis le putcher et je suis là pour putch !! ahahah",
        img: "https://www.soladis.com/wp-content/uploads/2017/06/personne-1-1.png",
      } as Redactor,
    };
  },
});
</script>

<style scoped>
h1 {
  font-size: 13vmin;
  color: #2c3a4b;
  text-decoration-thickness: 0.5vmin;
}
</style>