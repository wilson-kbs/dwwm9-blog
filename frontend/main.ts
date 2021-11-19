import Vue, { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import { store } from "./store";
import { Directives } from "./directives";
import "./assets/styles/main.scss";
import "./assets/styles/index.css";




createApp(App)
  .use(store)
  .use(router)
  /* .use(Directives) */
  .mount("#app");
