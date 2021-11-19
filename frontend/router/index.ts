import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
// import Home from "../views/Home.vue";
import NotFound from "../views/NotFound.vue";
import SignUp from "../views/SignUp.vue";
import SignIn from "../views/SignIn.vue";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    name: "Home",
    component: () => import("../views/Home.vue"),
  },
  {
    path: "/articles",
    name: "Posts",
    component: () => import("../views/Posts.vue"),
  },
  {
    path: "/article/:postId(\\d+)/:pathMatch(.*)*",
    name: "SiglePost",
    component: () => import("../views/SinglePost.vue"),
  },
  {
    path: "/category/:categoryId(\\d+)/:pathMatch(.*)*",
    name: "PostCategory",
    component: () => import("../views/PostCategory.vue"),
  },
  {
    path: "/signup",
    name: "Register",
    component: SignUp,
  },
  {
    path: "/signin",
    name: "Auth",
    component: SignIn,
  },
  {
    path: "/:pathMatch(.*)*",
    name: "NotFound",
    component: NotFound,
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
