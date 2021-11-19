<template>
  <nav class="header-nav">
    <button class="editor" @click="openPostEditor">+ article</button>
    <DropdownButton :name="'Categories'">
      <router-link
        v-for="(categ, index) in categories"
        :key="index"
        :to="`/category/${categ.id}/${categ.slug}`"
        >{{ categ.name }}</router-link
      >
    </DropdownButton>
    <div class="auth-button">
      <router-link to="/signin">Connection</router-link>
      <router-link to="/signup">Inscription</router-link>
    </div>
    <teleport to="body">
      <Modal :show="showPostEditor" @close="closePostEditor">
        <Editor></Editor>
      </Modal>
    </teleport>
  </nav>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { CategoriesActionTypes } from "../../store/modules/categories/action-types";
import Editor from "../PostEdit.vue";
import DropdownButton from "../common/DropdownButton.vue";
import Modal from "../common/Modal.vue";

export default defineComponent({
  components: {
    DropdownButton,
    Editor,
    Modal,
  },
  setup() {
    return {};
  },
  data() {
    return {
      showPostEditor: false,
    };
  },

  methods: {
    openPostEditor() {
      this.showPostEditor = true;
    },
    closePostEditor() {
      this.showPostEditor = false;
    },
  },
  computed: {
    categories() {
      return this.$store.state.categories.list;
    },
  },
  async mounted() {
    // await this.$store.dispatch(CategoriesActionTypes.GET_CATEGORIES);
  },
});
</script>

<style lang="scss" scoped>
.header-nav {
  display: flex;
  align-items: center;
  font-weight: bold;
}
.auth-button > * {
  margin: 0 1rem;
  color: rgb(230, 230, 230);
  &:hover {
    text-decoration: underline;
  }
}
</style>
