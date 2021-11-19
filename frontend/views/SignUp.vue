<template>
  <div>
    <Header :size="'medium'">
      <h1 class="font-extrabold">Inscription</h1>
    </Header>
    <main>
      <div id="auth-form">
        <SignUp />
      </div>
    </main>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "../store";

import Header from "../components/Header.vue";
import SignUp from "../components/AuthSignUp.vue";

export default defineComponent({
  setup() {
    const store = useStore();
    const router = useRouter();
    if (store.getters.AUTH__IsAuth) {
      router.push("/");
    }
  },
  components: {
    Header,
    SignUp,
  },
});
</script>

<style scoped>
h1 {
  font-size: 11vmin;
  color: #2c3a4b;
  transform: translateY(-60px);
}
</style>