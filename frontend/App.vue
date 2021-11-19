<template>
  <router-view v-slot="{ Component, route }">
    <transition :name="route.meta.transition || 'fade'" mode="out-in">
      <Suspense timeout="0">
        <template #default>
          <component
            :is="Component"
            :key="route.meta.usePathKey ? route.path : undefined"
          />
        </template>
        <template #fallback> Loading... </template>
      </Suspense>
    </transition>
  </router-view>
  <Footer />
</template>


<script lang="js">
import { defineComponent } from "vue";
import Nav from "./components/Nav.vue";
import Footer from "./components/Footer.vue";
import { useStore } from "./store";
import { AuthActionTypes } from "./store/modules/auth/action-types";
import { CategoriesActionTypes } from "./store/modules/categories/action-types";

export default defineComponent({
  setup() {
    const store = useStore();
    store.dispatch(AuthActionTypes.CHECK_USER_IS_AUTH);
    store.dispatch(CategoriesActionTypes.GET_CATEGORIES);
  },
  components: {
    Nav,
    Footer
  },
  data() {
    return {};
  },
  methods: {},
});
</script>


<style lang="scss">
html,
body {
  margin: 0;
  height: 100%;
}
html {
  font-size: 10px;
}
body {
  font-size: 16px;
}

body {
  overflow: overlay;
}

::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}

::-webkit-scrollbar-thumb {
  background: rgb(194, 194, 194);
}

::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.2);
}
#app {
  font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
    "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif,
    "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
  position: relative;
  height: 100%;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
</style>