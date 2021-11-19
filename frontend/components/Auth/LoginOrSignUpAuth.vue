<template>
  <div>
    <div>
      <a href="/login" @click.prevent="setLoginView()">Login</a>
      <br />
      <a href="/signup" @click.prevent="setSignUpView()">Sign Up</a>
      <router-link to="/signin">connection</router-link>
      <router-link to="/signup">register</router-link>
    </div>
    <component :is="nameView"></component>
  </div>
</template>

<script lang="ts">
import { computed, defineComponent, ref, watch, watchEffect } from "vue";
import { useRoute, useRouter } from "vue-router";
import SignUpComponent from "./SignUpAuth.vue";
import LoginComponent from "./LoginAuth.vue";

export default defineComponent({
  components: {
    signup: SignUpComponent,
    login: LoginComponent,
  },
  setup() {
    const route = useRoute();
    let routeName: string = "login";
    // if (route.path === "/login") routeName = "login";
    if (route.path === "/signup") routeName = "signup";

    const nameView = ref(routeName);

    return {
      nameView,
    };
  },
  methods: {
    setLoginView() {
      if (this.nameView !== "login") this.nameView = "login";
    },
    setSignUpView() {
      if (this.nameView !== "signup") this.nameView = "signup";
    },
  },
});
</script>

<style scoped>
</style>