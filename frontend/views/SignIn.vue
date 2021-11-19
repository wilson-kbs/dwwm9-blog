<template>
  <div>
    <Header :size="'medium'">
      <h1 class="font-extrabold">Login</h1>
    </Header>
    <main>
      <div id="auth-form">
        <SignIn />
      </div>
    </main>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "../store";
import SignIn from "../components/AuthSignIn.vue";
import Header from "../components/Header.vue";

export default defineComponent({
  setup() {
    const store = useStore();
    const router = useRouter();
    if (store.getters.AUTH__IsAuth) {
      router.push("/");
    }
  },
  components: {
    SignIn,
    Header,
  },
});
</script>

<style scoped>
h1 {
  font-size: 11vmin;
  color: #2c3a4b;
}
</style>